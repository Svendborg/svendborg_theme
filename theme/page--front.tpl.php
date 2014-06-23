<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see bootstrap_preprocess_page()
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see bootstrap_process_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>
<?php /* region--navigation.tpl.php */ ?>
<?php if ($page['navigation']): ?>
  <?php print render($page['navigation']); ?>
<?php endif; ?>

<div class="main-container container">

  <?php /* region--header.tpl.php */ ?>
  <?php print render($page['header']); ?>

  <div class="row">

    <?php /* region--sidebar.tpl.php */ ?>
    <?php if ($page['sidebar_first']): ?>
      <?php //print render($page['sidebar_first']); ?>
    <?php endif; ?>

    <!-- page--front.tpl.php-->
    <?php
    // Here comes frontpage buttons
      print "<div class='front-buttons col-md-push-1 col-md-10 col-sm-12 col-xs-12'>";

        $tree = menu_tree_all_data('menu-indholdsmenu', $link = NULL, $max_depth = 3);

        foreach ($tree as $key => $menu_item) {

          $title = $menu_item['link']['link_title'];
          switch($title) {
            case "Kommunen":
              $menu_links[0] = array('mlid' => array('title' => $title,'path' => $menu_item['link']['link_path']));
              break;
            case "Borger":
              $menu_links[1] = array('mlid' => array('title' => $title,'path' => $menu_item['link']['link_path']));
              break;
            case "Erhverv":
              $menu_links[2] = array('mlid' => array('title' => $title,'path' => $menu_item['link']['link_path']));
              break;
            case "Politik":
              $menu_links[3] = array('mlid' => array('title' => $title,'path' => $menu_item['link']['link_path']));
              break;
          }
        }
        ksort($menu_links);
        $count = 0;
        foreach ($menu_links as $menus) {
          foreach($menus as $key =>$menu_item) {
            print "<div class='menu-". $key. " front-indholsdmenu col-md-3 col-sm-3 col-xs-12'>";
            print "<h2 class='menu-front " . $menu_item['title']. "'>
            <a title='" . $menu_item['title'] . "' href='". $menu_item['path'] ."' class='" . $menu_item['title']. "'>" . $menu_item['title'] . "</a></h2>";

            print "</div>";
            $count += 1;
          }
        }

      print "</div>";

      //print render($page['content']);
      // Search-box
      print "<div class='frontpage-search-box col-md-push-3 col-md-6 col-sm-push-3 col-sm-6 col-xs-12'>";
      $search_from = drupal_get_form('search_form');
      print drupal_render($search_from);

      print "</div>";

      print "<div class='frontpage-news-branding col-md-push-1 col-md-10 col-sm-12 col-xs-12'>";
      $v = views_get_view('svendborg_news_view');
      $results = views_get_view_result('svendborg_news_view','block','branding');

      print '<div class="frontpage-seperator"></div>';
      print '
      <div id="frontpage-carousel-large" class="carousel slide" data-ride="carousel" data-interval="false">
        <!-- Indicators -->
        <ol class="carousel-indicators col-md-12 col-sm-12 col-xs-12">
        <li data-target="#frontpage-carousel-large" data-slide-to="0" class="active"></li>
        <li data-target="#frontpage-carousel-large" data-slide-to="1"></li>
        <li data-target="#frontpage-carousel-large" data-slide-to="2"></li>
         </ol>

      <!-- Wrapper for slides -->
      <div class="carousel-inner">
      ';
      foreach ($results as $key => $item) {
        if ($key == 0) {
          print '<div class="item active">';
        }
        else {
          print '<div class="item">';
        }
        $node = node_load($item->nid);
        $img = field_get_items('node',$node,'field_os2web_base_field_lead_img');
        $image = $img[0];
        // If you need informations about the file itself (e.g. image resolution):
        image_get_info( $image["filename"] );

        // If you want to access the image, use the URI instead of the filename !
        //$public_filename = file_create_url( $image["uri"] );
        $style = 'svendborg_frontpage_news_large'; 
        $public_filename = image_style_url($style, $image["uri"]);
        // Either output the IMG tag directly,
        print '<div class="row-no-padding col-md-8 col-sm-8 col-xs-12">';
        print $html = '<img title = "'.$image["title"].'" src="'.$public_filename.'""/>';
        print "</div>";
        print
          '<div class="carousel-title col-md-4 col-sm-4 col-xs-12">';

          print '<div class="title col-md-12">';
          print $node->title;
          print '</div>';

          print '<div class="col-md-12">';
          $path = drupal_get_path_alias('node/'.$node->nid);
          print '<a href="' . $path . '" title="'.$node->title.'" class="btn btn-primary">L&aelig;s mere</a>';
          print '</div>
          </div>
        </div>';
      }
      print '</div>';

      //print views_embed_view("svendborg_news_view", "block", 'branding');

      print "</div>";

      print "<div class='frontpage-news-bottom col-md-12 col-sm-12 col-xs-12'>";
      //print drupal_render(drupal_get_form('search_form'));

      print "</div>";
    ?>
  </div>
  </div>
</div>
<?php /* region--footer.tpl.php */ ?>
<?php print render($page['footer']); ?>
