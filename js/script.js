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
          $('#fixed-navbar').addClass('row');
        }
        else {
          $('.header_svendborg header').removeClass('navbar-fixed-top');
          $('.header_fixed_inner').removeClass('container');
          $('.header_svendborg header').addClass('container');
          $('.main-container').css('padding-top','initial');
          $('#fixed-navbar').removeClass('row');
        }
    });

    $('.node-os2web-base-news').each(function(){
      var $this = $(this);

      $this.parent().attr('data-filter',$this.attr('date-filter'));
      $this.parent().addClass($this.attr('date-filter'));
    });


    var button = 'filter-all';
    var button_class = "btn-primary";
    var button_normal = "btn-blacknblue";

    // Initial masonry
    var $container = $("#nyheder-content-isotoper .view-content");
      $container.imagesLoaded(function(){
      $container.masonry({
        itemSelector: '.switch-elements',
        columnWidth: '.switch-elements',
      });
      // filter elements
      $container.isotope({
        itemSelector: '.switch-elements',
      });
      $(".filter-link").click(function() {
        button = $(this).attr('id');
        var filterValue = $( this ).attr('data-filter');
        filterValue = '.'+filterValue;
        $container.isotope({ filter: filterValue });
        check_button();
      });
      $(".filter-link-all").click(function() {
  
        $container.isotope({ filter: '.all' });
        button = $(this).attr('id');
        check_button();
      });
  
      function check_button(){
        $('.filter-link').removeClass(button_class);
        $(".filter-link-all").removeClass(button_class);
        $('.filter-link').addClass(button_normal);
        $(".filter-link-all").addClass(button_normal);
        $('#'+button).addClass(button_class);
        $('#'+button).removeClass(button_normal);
        
  
      }
      check_button();

    });


  });

})( jQuery );


/**
 * Re-collapse the feedback form after every successful form submission.
 */
Drupal.behaviors.feedbackFormSubmit = {
  attach: function (context) {}
};
