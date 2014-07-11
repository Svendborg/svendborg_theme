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
<div class="front-main-container-wrapper">
<div class="main-container container">

  <?php /* region--header.tpl.php */ ?>
  <?php print render($page['header']); ?>

  <div class="">

    <?php /* region--sidebar.tpl.php */ ?>
    <?php if ($page['sidebar_first']): ?>
      <?php //print render($page['sidebar_first']); ?>
    <?php endif; ?>

    <!-- page--front.tpl.php-->
    <?php
    // Here comes front buttons
      print "<div class='row-no-padding front-buttons col-md-push-1 col-md-10 col-sm-12 col-xs-12'>";

        $tree = menu_tree_all_data('menu-indholdsmenu', $link = NULL, $max_depth = 3);

        foreach ($tree as $key => $menu_item) {

          $title = $menu_item['link']['link_title'];
          $path = $alias = drupal_get_path_alias($menu_item['link']['link_path']);
          switch($title) {
            case "Kommunen":
              $menu_links[0] = array('mlid' => array('title' => $title,'path' => $path));
              break;
            case "Borger":
              $menu_links[1] = array('mlid' => array('title' => $title,'path' => $path));
              break;
            case "Erhverv":
              $menu_links[2] = array('mlid' => array('title' => $title,'path' => $path));
              break;
            case "Politik":
              $menu_links[3] = array('mlid' => array('title' => $title,'path' => $path));
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
      print "<div class='front-search-box col-md-push-3 col-md-6 col-sm-push-3 col-sm-6 col-xs-12'>";
      $search_from = drupal_get_form('search_form');
      print drupal_render($search_from);

      print "</div>";

      // Branding news view
      print "<div id='front-news-branding' class='front-news-branding col-md-push-1 col-md-10 col-sm-12 col-xs-12'>";
      $view = views_get_view('svendborg_news_view');
      $view->set_display('block');
      $view->set_arguments(array('branding'));
      $filter = $view->get_item('block', 'filter', 'promote');
      $filter['value'] = 1;
      $view->set_item('block', 'filter', 'promote', $filter);
      $view->set_items_per_page(3);

      $view->execute();

      $results = $view->result;

      print '<div class="front-seperator"></div>';
      print '
      <div class="carousel slide" data-ride="carousel" data-interval="false">
        <!-- Indicators -->
        <ol class="carousel-indicators col-md-12 col-sm-12 col-xs-12">
        <li data-target="#front-news-branding" data-slide-to="0" class="active"></li>
        <li data-target="#front-news-branding" data-slide-to="1"></li>
        <li data-target="#front-news-branding" data-slide-to="2"></li>
         </ol>

      <!-- Wrapper for slides -->
      <div class="carousel-inner" id="front-carousel-large" >
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
        $style = 'os2demo_indhold'; 
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

      print "</div></div>";
      print '<div class="front-seperator"></div>';
    ?>

  </div>
</div>
<div class="front-main-container-shadow"></div>
</div>
<?php
  // 3 Small news view
  print '<div class="lcontainer-fluid clearfix front-s-news">';
  print '<div class="container">';
  print '<div class="row front-s-news-inner">';

    print "<div class=' col-md-12 col-sm-12 col-xs-12'>";

    $view = views_get_view('svendborg_news_view');
    $view->set_display('block');
    $view->set_arguments(array('all'));
    $filter = $view->get_item('block', 'filter', 'promote');
    $filter['value'] = 1;
    $view->set_item('block', 'filter', 'promote', $filter);
    $view->set_items_per_page(9);

    $view->execute();

    $results = $view->result;

      print '<div id="front-carousel-small" class="carousel slide" data-ride="carousel" data-interval="false">
        <!-- Indicators -->
        <ol class="carousel-indicators col-md-12 col-sm-12 col-xs-12">
        <li data-target="#front-carousel-small" data-slide-to="0" class="active"></li>
        <li data-target="#front-carousel-small" data-slide-to="1"></li>
        <li data-target="#front-carousel-small" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
        ';

        $samll_news_carousel = array();
        foreach ($results as $key => $item) {
          if($key < 3) {
            $samll_news_carousel[0][] = $item;
          }
          elseif ($key >= 3 && $key <= 5) {
            $samll_news_carousel[1][] = $item;
          }
          elseif ($key >= 6) {
            $samll_news_carousel[2][] = $item;
          }
        }
        foreach ($samll_news_carousel as $key => $items) {
          if ($key == 0) {
            print '<div class="item active">';
          }
          else {
            print '<div class="item">';
          }
          foreach($items as $i=> $item) {
            $node = node_load($item->nid);
            $img = field_get_items('node',$node,'field_os2web_base_field_lead_img');
            $image = $img[0];
            // If you need informations about the file itself (e.g. image resolution):
            image_get_info( $image["filename"] );

            // If you want to access the image, use the URI instead of the filename !
            //$public_filename = file_create_url( $image["uri"] );
            $style = 'os2demo_indhold';
            $public_filename = image_style_url($style, $image["uri"]);
            // Either output the IMG tag directly,
            echo '<div class="col-md-4 col-sm-4 col-xs-12">';
            print '<div class="front-s-news-item front-s-news-item-'.$i.'">';
            print '<div class="front-s-news-item-img">';
            print $html = '<img title = "'.$image["title"].'" src="'.$public_filename.'""/>';
            print '</div>';
            print
              '<div class="front-s-news-item-text">';

            $path = drupal_get_path_alias('node/'.$node->nid);
            print '<div class="bubble"><span><a href="'.$path.'">'. $node->title .'</a>';
            print "</a></span></div>";
            print '</div>
            </div>';
            echo '</div>';
          }
          print '</div>';
        }

      print '</div></div>';

      print "</div>";
      print '<div class="front-seperator"></div>';

    print "</div>";
  print "</div>";
  print "</div>";
  print "</div>";

?>
<div class="lcontainer-fluid clearfix front-news-bottom">
  <div class="container">
    <div class="front-news-bottom-inner">
      <div class=''>
        <span>&#216;nsker du at se alle nyheder   <a href="/nyheder" class="btn btn-primary">Tryk her</a></span>
      </div>
    </div>
  </div>
</div>
<?php /* region--footer.tpl.php */ ?>
<?php print render($page['footer']); ?>
