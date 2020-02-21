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
      slidesToShow: slidesToShow,
      slidesToScroll: 1,
    });
});