<?php
/**
 * @file
 * region--sidebar.tpl.php
 *
 * Default theme implementation to display the "sidebar_first" and
 * "sidebar_second" regions.
 *
 * Available variables:
 * - $content: The content for this region, typically blocks.
 * - $attributes: String of attributes that contain things like classes and ids.
 * - $content_attributes: The attributes used to wrap the content. If empty,
 *   the content will not be wrapped.
 * - $region: The name of the region variable as defined in the theme's .info
 *   file.
 * - $page: The page variables from bootstrap_process_page().
 *
 * Helper variables:
 * - $is_admin: Flags true when the current user is an administrator.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 *
 * @see bootstrap_preprocess_region().
 * @see bootstrap_process_page().
 *
 * @ingroup themeable
 */
?>
  <footer class="region region_footer lcontainer-fluid">
    <div class="lcontainer-fluid clearfix"  id="footer-menu">
      <div class="container footer-menu">
      <?php
        $tree = menu_tree_all_data('menu-indholdsmenu', $link = NULL, $max_depth = 3);

        foreach ($tree as $key => $menu_item) {
          print "<div class='menu-". $menu_item['link']['mlid']. " footer-indholsdmenu col-xs-12 col-sm-6 col-md-3'>";
          if($menu_item['link']['has_children']) {
            print "<h2 class='menu-footer " . $menu_item['link']['link_title']. "'>
            <a title='" . $menu_item['link']['link_title'] . "' href='". $menu_item['link']['link_path'] ."' class='" . $menu_item['link']['link_title']. "'>" . $menu_item['link']['link_title'] . "</a></h2>";
          
            $tree_display =menu_tree_output($menu_item['below']);
            print render($tree_display);
          }
          print "</div>";
        }

      ?>
      <?php if ($content_attributes): ?><div<?php print $content_attributes; ?>><?php endif; ?>
      <?php //print $content; ?>
      <?php if ($content_attributes): ?></div><?php endif; ?>
      </div>
    </div>
    <!-- footer contacts social-icons -->
    <div class="lcontainer-fluid clearfix" id="footer-contacts">
      <div class="container">
        <div class="col-md-3 col-xs-12 col-sm-6 col-md-push-9 col-sm-push-6 social-icons">
          <a href="https://www.facebook.com/Svendborg" title="Svendborg Kommune Facebook" class="footer_fb">facebook</a>
          <a href="https://www.facebook.com/Svendborg" title="Svendborg Kommune twitter" class="footer_twitter">twitter</a>
          <a href="https://www.facebook.com/Svendborg" title="Svendborg Kommune GooglePlus" class="footer_googleplus">googleplus</a>
          <a href="https://www.facebook.com/Svendborg" title="Svendborg Kommune Linkedin" class="footer_linkedin">linkedin</a>
          <a href="https://www.facebook.com/Svendborg" title="Svendborg Kommune Flickr" class="footer_flickr">flickr</a>
        </div>
        <div class="col-md-9 col-sm-6 col-xs-12 col-md-pull-3 col-sm-pull-6">
          <div class='footer-logo'>
            <img id="footer-logo" src="/profiles/os2web/themes/svendborg_theme/images/footer_logo.png" title="<?php print $page['site_name'] ?>" />
                      
          </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 footer-address">
          <span>Ramsherred 5 ∙ 5700 Svendborg ∙ Telefon 62 23 30 00 ∙ </span>
          <a href="mailto:svendborg@svendborg.dk" target="_top">Send en mail</a>
        </div>

      </div>
    </div>
    <!-- footer bg-image -->
    <div class="lcontainer-fluid clearfix footer-bg-image">
      <img class="" src="/profiles/os2web/themes/svendborg_theme/images/footer_bottom_bg.png" />
    </div>
  </footer>