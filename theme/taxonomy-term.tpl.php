<?php

/**
 * @file
 * Default theme implementation to display a term.
 *
 * Available variables:
 * - $name: (deprecated) The unsanitized name of the term. Use $term_name
 *   instead.
 * - $content: An array of items for the content of the term (fields and
 *   description). Use render($content) to print them all, or print a subset
 *   such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $term_url: Direct URL of the current term.
 * - $term_name: Name of the current term.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the following:
 *   - taxonomy-term: The current template type, i.e., "theming hook".
 *   - vocabulary-[vocabulary-name]: The vocabulary to which the term belongs to.
 *     For example, if the term is a "Tag" it would result in "vocabulary-tag".
 *
 * Other variables:
 * - $term: Full term object. Contains data that may not be safe.
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $page: Flag for the full page state.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the term. Increments each time it's output.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_taxonomy_term()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>
<div id="taxonomy-term-<?php print $term->tid; ?>" class="<?php print $classes; ?>">
  <?php if (!$page): ?>
    <h2><a href="<?php print $term_url; ?>"><?php print $term_name; ?></a></h2>
  <?php endif; ?>

  <?php if ($page): ?>
    <?php if(!empty($os2web_selfservicelinks)) : ?>
      <div class="col-sm-12 col-md-8 col-md-offset-2">

        <div class="dropdown like-panel like-panel-default">
          <a href="#" data-toggle="dropdown"><?php print t('Nem og hurtig selvbetjening'); ?> <i></i></a>
          <ul class="dropdown-menu">
          <?php foreach ($os2web_selfservicelinks as $link) : ?>
            <li>
              <a href="<?php print $link['url']; ?>"><?php print $link['title']; ?></a>
            </li>
          <?php endforeach; ?>
         </ul>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <div class="col-md-12 col-sm-12 content">
    <?php
      hide($content['os2web_spotbox']);
      hide($content['field_os2web_base_field_spotbox']);
      hide($content['field_list_as_spotboks']);
      hide($content['field_os2web_base_field_selfserv']);
      print render($content); ?>
  </div>

  <?php if($page): ?>
  <div class="row">
    <div class="col-md-9 col-sm-12 clearfix">
      <div class="col-sm-12 col-md-12 extra-bottom-padding">
        <?php
          // Get news carousel.
          $view = views_get_view('os2web_news_lists');
          $view->set_display('panel_pane_1');
          $view->set_arguments(array('Branding', 'none', $term_name));
          $view->set_items_per_page(3);
          $view->pre_execute();
          $view->execute();
          print $view->render();
        ?>

      </div>
      <div class="col-sm-12 col-md-12 bottom-padding">
        <?php
          // Get Sub terms.
          $view = views_get_view('subtermer');
          $view->set_display('panel_pane_1');
          $view->pre_execute();
          $view->execute();
          print $view->render();
        ?>
      </div>
      <div class="os2web_spotboxes">

        <?php print render($content['os2web_spotbox']); ?>

      </div>
    </div>
    <div class="col-md-3 col-sm-12">

    <?php
      // Get news carousel.
      $view = views_get_view('os2web_news_lists');
      $view->set_display('panel_pane_2');
      $view->set_arguments(array('all', 'Branding', $term_name));
      $view->set_items_per_page(3);
      $view->pre_execute();
      $view->execute();
      if (!empty($view->result)) : ?>
      <div class="panel panel-primary with-arrow">
        <div class="panel-heading">
          <h3 class="panel-title"><?php print t('Nyheder og aktuelt'); ?></h3>
        </div>
        <div class="panel-body">
          <div class="panel-body-inner">
          <?php print $view->render(); ?>
          </div>
        </div>
        <div class="panel-footer">
          <a href="/nyheder/<?php print strtolower($term_name); ?>" class="btn btn-primary">
            <?php print t('Se alle nyheder her'); ?>
          </a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php endif; ?>

</div>
