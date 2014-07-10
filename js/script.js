/* Svendborg theme script
*/
( function ($) {
  $(document).ready(function(){
    $(window).bind('scroll', function() {
        var navHeight = $( window ).height();
        if ($(window).scrollTop() > 41 && $(window).width() > 768 ) {
          $('.header_svendborg header').addClass('navbar-fixed-top');
          $('.header_fixed_inner').addClass('container');
          $('.header_svendborg header').removeClass('container');
          $('.main-container').css('padding-top','114px');
        }
        else {
          $('.header_svendborg header').removeClass('navbar-fixed-top');
          $('.header_fixed_inner').removeClass('container');
          $('.header_svendborg header').addClass('container');
          $('.main-container').css('padding-top','initial');
        }
    });
  });

})( jQuery );

/**
 * Re-collapse the feedback form after every successful form submission.
 */
Drupal.behaviors.feedbackFormSubmit = {
  attach: function (context) {}
};
