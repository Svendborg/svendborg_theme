<?php
/**
 * @file
 * template.php
 */


/**
 * Implements hook_preprocess_node().
 */
function svendborg_theme_preprocess_node(&$variables) {
  /*
   * If page shouldn't have a right sidebar, this is check by a checkbox on
   * node/term. Remove it from the render array.
   */
/**
 * THIS IS ONLY AN EXAMPLE!!!
  $node = $variables['node'];

  // Make the field variables available with the appropriate language.
  field_attach_preprocess('node', $node, $variables['content'], $variables);

  if (!$variables['has_right_column_field']) {
    $variables['classes_array'][] = 'without-right-sidebar';
  }
*/
}


/**
 * Implements hook_preprocess_region().
 */
function svendborg_theme_preprocess_region(&$variables) {
  $node = NULL;
  if (isset($variables['page']['node']) && !empty($variables['page']['node']->nid)) {
    $node = $variables['page']['node'];
  }

  // Region Sidebar Second.
  if ($variables['region'] === 'sidebar_second') {

    // Show related links in sidebar checkbox on node.
    if ($node && $node->field_os2web_base_field_hidlinks['und'][0]['value'] == '0') {
      // Show related links in reqion. Add them to the render array somehow.
      // $variables['elements']['related_links'] = render(elements);
    }
  }
}

/**
 * Implements template_preprocess_page().
 */
function svendborg_theme_preprocess_page($variables) {

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
