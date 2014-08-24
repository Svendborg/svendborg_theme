(function ($) {

/**
 * Attach collapse behavior to the feedback form block.
 * overwrite the origin js.
 */
Drupal.behaviors.feedbackForm = {
  attach: function (context) {
    $('#block-feedback-form', context).once('feedback', function () {
      var $block = $(this);
      $block.find('span.feedback-link')
        .prepend('<span id="feedback-form-toggle">[ + ]</span> ')
        .css('cursor', 'pointer')
        .toggle(function () {
            Drupal.feedbackFormToggle($block, true);
          },
          function() {
            Drupal.feedbackFormToggle($block, false);
          }
        );
      $block.find('form').hide();
      $block.show();
    });
  }
};

/**
 * Re-collapse the feedback form after every successful form submission.
 */
Drupal.behaviors.feedbackFormSubmit = {
  attach: function (context) {
    var $context = $(context);
    if (!$context.is('#feedback-status-message')) {
      return;
    }
    // Collapse the form.
    $('#block-feedback-form .feedback-link').click();
    // Blend out and remove status message.
    window.setTimeout(function () {
      $context.fadeOut('slow', function () {
        $context.remove();
      });
    }, 3000);
  }
};

/**
 * Collapse or uncollapse the feedback form block.
 */
Drupal.feedbackFormToggle = function ($block, enable) {
  //$block.find('form').slideToggle('medium');
  console.log(enable);
  if (enable) {
    $block.animate({width:"327px"});
    $block.find('form').css('display','block');
    $('#feedback-form-toggle', $block).html('[ + ]');
    console.log("her");
  }
  else {
    $block.animate({width:"27px"});
    //$block.find('form').css('display','none');
    $('#feedback-form-toggle', $block).html('[ &minus; ]');
  }
};

})(jQuery);