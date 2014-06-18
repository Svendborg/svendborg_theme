/* Svendborg theme script
*/
+function ($) {
  $(document).ready(function(){
    $(window).bind('scroll', function() {
        var navHeight = $( window ).height();
        if ($(window).scrollTop() > 120 && $(window).width() > 768 ) {
          $('.header_svendborg header').addClass('navbar-fixed-top');
          //$('header').removeClass('container');
        }
        else {
          $('.header_svendborg header').removeClass('navbar-fixed-top');
          //$('header').addClass('container');
        }
    });
  });

}(jQuery);
