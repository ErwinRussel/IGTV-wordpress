(function($) {
  $(document).on('ready', function() {

      var slidesToShow = 6;

      $(window).resize(function(){
        if ($(window).width() > 1200) {
          slidesToShow = 6;
        } else if ($(window).width() > 992) {
          slidesToShow = 4;
        } else if ($(window).width() > 768) {
          slidesToShow = 3;
        } else  {
          slidesToShow = 1;
        } 

        $('.regular').slick('slickSetOption', 'slidesToShow', slidesToShow);

      });

      $(".regular").slick({
        dots: false,
        infinite: false,
        prevArrow: '<button class="left carousel-control" aria-label="Previous" type="button"><i class="glyphicon glyphicon-chevron-left"></i></button>',
        nextArrow: '<button class="right carousel-control" aria-label="Next" type="button"><i class="glyphicon glyphicon-chevron-right"></i></button>',
        slidesToShow: slidesToShow,
        slidesToScroll: 1,
      });
  });
})( jQuery );