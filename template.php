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

  // If node has hidden the sidebar, set content to null and return.
  if ($node &&
      isset($node->field_svendborg_hide_sidebar['und'][0]['value']) &&
      $node->field_svendborg_hide_sidebar['und'][0]['value'] == '1') {

    $variables['page']['sidebar_second'] = array();
  }

  // If the current item is NOT in indholdsmenu, clean the sidebar_first array.
  // Dont show sidebar on nodes if they are not in menu.
  if ($node) {
    $menu_trail = menu_get_active_trail();
    $active = end($menu_trail);
    if ($active['menu_name'] !== 'menu-indholdsmenu') {
      $variables['page']['sidebar_first'] = array();
    }
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
    // elseif ((!empty($element['#original_link']['depth'])) && ($element['#original_link']['depth'] == 1)) {
    //   // Add our own wrapper.
    //   unset($element['#below']['#theme_wrappers']);
    //   $sub_menu = '<ul class="dropdown-menu">' . drupal_render($element['#below']) . '</ul>';
    //   // Generate as standard dropdown.
    //   $element['#title'] .= ' <span class="caret"></span>';
    //   $element['#attributes']['class'][] = 'dropdown';
    //   $element['#localized_options']['html'] = TRUE;

    //   // Set dropdown trigger element to # to prevent inadvertant page loading
    //   // when a submenu link is clicked.
    //   $element['#localized_options']['attributes']['data-target'] = '#';
    //   $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
    //   $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
    // }
    elseif ($element['#original_link']['in_active_trail']) {
      $sub_menu = drupal_render($element['#below']);
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
