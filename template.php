<?php
/**
 * @file
 * template.php
 */

/**
 * Implements template_preprocess_page().
 */
function svendborg_theme_preprocess_page(&$variables) {
  $node = NULL;
  if (isset($variables['node']) && !empty($variables['node']->nid)) {
    $node = $variables['node'];
  }
  $sidebar_second_hidden = FALSE;
  $sidebar_first_hidden = FALSE;

  // If node has hidden the sidebar, set content to null and return.
  if ($node && $hide_sidebar_field = field_get_items('node', $node, 'field_svendborg_hide_sidebar')) {
    if ($hide_sidebar_field[0]['value'] == '1') {
      $variables['page']['sidebar_second'] = array();
      $sidebar_second_hidden = TRUE;
    }
  }

  // If the current item is NOT in indholdsmenu, clean the sidebar_first array.
  // Dont show sidebar on nodes if they are not in menu.
  if ($node) {
    $menu_trail = menu_get_active_trail();
    $active = end($menu_trail);
    if ($active['menu_name'] !== 'menu-indholdsmenu') {
      $variables['page']['sidebar_first'] = array();
      $sidebar_first_hidden = TRUE;
    }
  }

  // Get all the nodes selvbetjeningslinks and give them to the template.
  if ($node && $links = field_get_items('node', $node, 'field_os2web_base_field_selfserv')) {
    $selfservicelinks = array();
    foreach ($links as $link) {
      $selfservicelink = node_load($link['nid']);
      if ($selfservicelink) {
        $selfservicelinks[$link['nid']] = array(
          'nid' => $selfservicelink->nid,
          'title' => $selfservicelink->title,
        );
      }
    }
    $variables['page']['selfservicelinks'] = $selfservicelinks;
  }

  // Get all related links to this node.
  // 1. Get all unique related links from the node.
  $related_links = array();
  if ($node && $links = field_get_items('node', $node, 'field_os2web_base_field_related')) {
    foreach ($links as $link) {
      $link_node = node_load($link['nid']);
      if ($link_node) {
        $related_links[$link['nid']] = array(
          'nid' => $link->nid,
          'title' => $link_node->title,
        );
      }
    }
  }
  // 2. Get all related links related to the KLE number on the node. Only get
  // these if the checkbox "Skjul relaterede links" isn't checked.
  if ($node &&
      (!isset($node->field_os2web_base_field_hidlinks['und'][0]['value']) ||
      $node->field_os2web_base_field_hidlinks['und'][0]['value'] == '0') &&
      $kle_items = field_get_items('node', $node, 'field_os2web_base_field_kle_ref')) {

    foreach ($kle_items as $kle) {
      // Get all nodes which have the same KLE number as this node.
      $query = new EntityFieldQuery();
      $result = $query->entityCondition('entity_type', 'node')
        ->propertyCondition('status', 1)
        ->propertyCondition('nid', $node->nid, '!=')
        ->fieldCondition('field_os2web_base_field_kle_ref', 'tid', $kle['tid'])
        ->propertyOrderBy('title', 'ASC')
        ->execute();
      if (isset($result['node'])) {
        foreach ($result['node'] as $link) {
          if (isset($related_links[$link->nid])) {
            continue;
          }
          $link_node = node_load($link->nid);
          if ($link_node) {
            $related_links[$link->nid] = array(
              'nid' => $link->nid,
              'title' => $link_node->title,
              'class' => 'kle-link',
            );
          }

        }
      }
    }
  }
  // Provide the related links to the templates.
  $variables['page']['related_links'] = $related_links;

  // Hack to force the sidebar_second to be rendered if we have anything to put
  // in it.
  if (!$sidebar_second_hidden  && empty($variables['page']['sidebar_second']) && (!empty($variables['page']['related_links']) || !empty($variables['page']['selfservicelinks']))) {
    $variables['page']['sidebar_second'] = array(
      'dummy_content' => array(
        '#markup' => ' ',
      ),
      '#theme_wrappers' => array('region'),
      '#region' => 'sidebar_second',
    );
  }

  // Spotbox handling. Find all spotboxes for this node, and add them to
  // page_bottom.
  if ($node && $spotboxes = field_get_items('node', $node, 'field_os2web_base_field_spotbox')) {

    $spotbox_nids = array();
    foreach ($spotboxes as $spotbox) {
      $spotbox_nids[$spotbox['nid']] = $spotbox['nid'];
    }
    $spotbox_array = os2web_spotbox_render_spotboxes($spotbox_nids, NULL, NULL, NULL, 'svendborg_spotbox');

    foreach ($spotbox_array['node'] as &$spotbox) {
      $spotbox['#prefix'] = '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">';
      $spotbox['#suffix'] = '</div>';
    }

    $variables['page']['content_bottom'] = array(
      'os2web_spotbox' => array(
        '#markup' => drupal_render($spotbox_array),
      ),
      '#theme_wrappers' => array('region'),
      '#region' => 'content_bottom',
    );
  }

  // Add out fonts from Google Fonts API.
  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => 'http://fonts.googleapis.com/css?family=Titillium+Web:400,700|Open+Sans:400,700',
      'rel' => 'stylesheet',
      'type' => 'text/css',
    ),
  ), 'google_font_svendborg_theme');
}

/**
 * Implements THEME_preprocess_html().
 */
function svendborg_theme_preprocess_html(&$variables) {
  // Add conditional stylesheets for IE.
  drupal_add_css(path_to_theme() . '/css/ie.css', array(
    'group' => CSS_THEME,
    'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE),
    'preprocess' => FALSE,
    'weight' => 115,
  ));
}

/**
 * Implements theme_breadcrumb().
 *
 * Output breadcrumb as an unorderd list with unique and first/last classes.
 */
function svendborg_theme_breadcrumb($variables) {
  $breadcrumbs = $variables['breadcrumb'];
  if (!empty($breadcrumbs)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $crumbs = '<ul class="breadcrumb">';

    foreach ($breadcrumbs as $breadcrumb) {
      $classes = array();
      if ($breadcrumb == reset($breadcrumbs)) {
        $classes[] = 'first';
      }
      if ($breadcrumb == end($breadcrumbs)) {
        $classes[] = 'last';
      }
      if (is_array($breadcrumb)) {
        if (isset($breadcrumb['class'])) {
          $classes = array_merge($classes, $breadcrumb['class']);
        }
        if (isset($breadcrumb['data'])) {
          $breadcrumb = $breadcrumb['data'];
        }
      }
      $crumbs .= '<li class="' . implode(' ', $classes) . '"><i></i>'  . $breadcrumb . '</li>';
    }
    $crumbs .= '</ul>';
    return $crumbs;
  }
}

/**
 * Overrides theme_menu_link().
 *
 * Overrides Bootstrap version. Enables to show active trails childrens.
 */
function svendborg_theme_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  if ($element['#below']) {
    // Prevent dropdown functions from being added to management menu so it
    // does not affect the navbar module.
    if (($element['#original_link']['menu_name'] == 'management') && (module_exists('navbar'))) {
      $sub_menu = drupal_render($element['#below']);
    }
    elseif ($element['#original_link']['in_active_trail']) {
      $sub_menu = drupal_render($element['#below']);
    }
    else {
      $element['#attributes']['class'][] = 'has-children';
    }
  }
  // On primary navigation menu, class 'active' is not set on active menu item.
  // @see https://drupal.org/node/1896674
  if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
    $element['#attributes']['class'][] = 'active';
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
