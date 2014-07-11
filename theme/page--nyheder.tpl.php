
<?php /* region--navigation.tpl.php */ ?>
<?php if ($page['navigation']): ?>
  <?php print render($page['navigation']); ?>
<?php endif; ?>
<div class="front-main-container-wrapper">
<div class="main-container container">

  <?php /* region--header.tpl.php */ ?>
  <?php print render($page['header']); ?>

    <?php /* region--sidebar.tpl.php */ ?>
    <?php if ($page['sidebar_first']): ?>
      <?php //print render($page['sidebar_first']); ?>
    <?php endif; ?>

    <!-- page--nyheder.tpl.php-->
    <div class="col-md-8 col-sm-8 col-xs-12">
      <h1>Nyheder og aktuelt</h1>

    <?php

      // Branding news view

      $view = views_get_view('svendborg_news_view');
      $view->set_display('block');
      $view->set_arguments(array('branding'));
      $filter = $view->get_item('block', 'filter', 'promote');
      $filter['value'] = 1;
      $view->set_item('block', 'filter', 'promote', $filter);
      $view->set_items_per_page(3);

      $view->execute();

      $results = $view->result;
      print '<div  id="nyheder-carousel-large">';
      print '
      <div class="carousel slide" data-ride="carousel" data-interval="false">
        <!-- Indicators -->
        <ol class="carousel-indicators col-md-12 col-sm-12 col-xs-12">
        <li data-target="#nyheder-carousel-large" data-slide-to="0" class="active"></li>
        <li data-target="#nyheder-carousel-large" data-slide-to="1"></li>
        <li data-target="#nyheder-carousel-large" data-slide-to="2"></li>
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
        $style = 'os2demo_indhold'; 
        $public_filename = image_style_url($style, $image["uri"]);
        // Either output the IMG tag directly,
        print '<div class="row-no-padding col-md-8 col-sm-12 col-xs-12">';

        print $html = '<img title = "'.$image["title"].'" src="'.$public_filename.'""/>';
        print "</div>";
        print '<div class="row-no-padding carousel-title col-md-4 col-sm-12 col-xs-12">';

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
      print '</div></div></div>';

    ?>
    <div class="nyheder-seperator"></div>
    <div class="nyheder-content">
    <?php
      $view = views_get_view('svendborg_news_view');
      $view->set_display('block');
      $view->set_arguments(array('nyhed'));
      //$filter = $view->get_item('block', 'filter', 'promote');
      //$filter['value'] = 1;
      //$view->set_item('block', 'filter', 'promote', $filter);
      $view->set_items_per_page(8);

      $view->execute();

      $results = $view->result;
      foreach ($results as $key => $item) {

        print '<div class="row">';

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
            echo '<div class="col-md-6 col-sm-6 col-xs-12">';
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

        print '</div>';
      }
    ?>
    </div>
  </div>

  <!-- right sidebar -->
  <div class="col-md-4 col-sm-4 col-xs-12">
</div>
</div>
<?php /* region--footer.tpl.php */ ?>
<?php print render($page['footer']); ?>
