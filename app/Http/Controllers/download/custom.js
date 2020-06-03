
$(document).ready(function(){
    $('.main-banner').owlCarousel({
        loop:true,
        margin:0,
        nav:true,
        dots:false,
        rtl:true,
        navText: ["<img src='/images/owl-prv.png'>","<img src='/images/owl-nxt.png'>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });

    $('.dictionary-slide').owlCarousel({
        loop:true,
        margin:50,
        nav:true,
        dots:false,
        rtl:true,
        navText: ["<img src='/images/owl-prv-g.png'>","<img src='/images/owl-nxt-g.png'>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    });

    $('.article-slide').owlCarousel({
        loop:true,
        margin:50,
        nav:true,
        dots:false,
        rtl:true,
        navText: ["<img src='/images/owl-prv-g.png'>","<img src='/images/owl-nxt-g.png'>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:2
            }
        }
    });

    $('.category-slide').owlCarousel({
        loop:true,
        margin:12,
        nav:true,
        dots:false,
        rtl:true,
        navText: ["<img src='/images/cate-prev.png'>","<img src='/images/cate-nxt.png'>"],
        responsive:{
            0:{
                items:3
            },
            500:{
                items:5
            },
            760:{
                items:8
            },
            1000:{
                items:10
            },
            1200:{
                items:13
            }
        }
    });

    $('.article-detail-slide').owlCarousel({
        loop:true,
        margin:80,
        nav:true,
        dots:false,
        rtl:true,
        navText: ["<img src='/images/owl-prv-g.png'>","<img src='/images/owl-nxt-g.png'>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2,
                margin:30
            },
            1000:{
                items:3,
                margin:30
            },
            1200:{
                items:3,
                margin:80
            }
        }
    });
});



//Corusel with thumbnail

$(document).ready(function() {
  var bigimage = $("#big");
  var thumbs = $("#thumbs");
  //var totalslides = 10;
  var syncedSecondary = true;

  bigimage
    .owlCarousel({
    items: 1,
    slideSpeed: 2000,
    nav: false,
    autoplay: true,
    dots: false,
    rtl:true,
    loop: true,
    responsiveRefreshRate: 200,
  })
    .on("changed.owl.carousel", syncPosition);

  thumbs
    .on("initialized.owl.carousel", function() {
    thumbs
      .find(".owl-item")
      .eq(0)
      .addClass("current");
  })
    .owlCarousel({
    items: 5,
    dots: false,
    margin: 15,
    nav: false,
    rtl:true,
    smartSpeed: 200,
    slideSpeed: 500,
    slideBy: 5,
    responsiveRefreshRate: 100
  })
    .on("changed.owl.carousel", syncPosition2);

  function syncPosition(el) {
    //if loop is set to false, then you have to uncomment the next line
    //var current = el.item.index;

    //to disable loop, comment this block
    var count = el.item.count - 1;
    var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

    if (current < 0) {
      current = count;
    }
    if (current > count) {
      current = 0;
    }
    //to this
    thumbs
      .find(".owl-item")
      .removeClass("current")
      .eq(current)
      .addClass("current");
    var onscreen = thumbs.find(".owl-item.active").length - 1;
    var start = thumbs
    .find(".owl-item.active")
    .first()
    .index();
    var end = thumbs
    .find(".owl-item.active")
    .last()
    .index();

    if (current > end) {
      thumbs.data("owl.carousel").to(current, 100, true);
    }
    if (current < start) {
      thumbs.data("owl.carousel").to(current - onscreen, 100, true);
    }
  }

  function syncPosition2(el) {
    if (syncedSecondary) {
      var number = el.item.index;
      bigimage.data("owl.carousel").to(number, 100, true);
    }
  }

  thumbs.on("click", ".owl-item", function(e) {
    e.preventDefault();
    var number = $(this).index();
    bigimage.data("owl.carousel").to(number, 300, true);
  });
});


//Search form Pop up=====================

  $(function () {
      $('a[href="#search"]').on('click', function(event) {
          event.preventDefault();
          $('#search').addClass('open');
          $('#search > form > input[type="search"]').focus();
      });
      
      $('#search, #search button.close').on('click keyup', function(event) {
          if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
              $(this).removeClass('open');
          }
      });
      

  });



//Flashy light box

$(document).ready(function() {
  'use strict';

  $('.gallery').flashy({
    prevShowClass: 'fx-bounceInLeft',
    nextShowClass: 'fx-bounceInRight',
    prevHideClass: 'fx-bounceOutRight',
    nextHideClass: 'fx-bounceOutLeft'
  });

  $('.insta-gal').flashy({
    prevShowClass: 'fx-bounceInLeft',
    nextShowClass: 'fx-bounceInRight',
    prevHideClass: 'fx-bounceOutRight',
    nextHideClass: 'fx-bounceOutLeft'
  });

  $('.fb-gal').flashy({
    prevShowClass: 'fx-bounceInLeft',
    nextShowClass: 'fx-bounceInRight',
    prevHideClass: 'fx-bounceOutRight',
    nextHideClass: 'fx-bounceOutLeft'
  });

  $('.custom').flashy({
    showClass: 'fx-fadeIn',
    hideClass: 'fx-fadeOut'
  });
  //Quick Cart

$('#cartEnable').on('click', function (e) {
 
    $(this).addClass('car_act');
    e.preventDefault();
    e.stopPropagation();
    $('.cart_box').toggle(600);

});
$(document).on('click', function (e) {
    $('#cartEnable').removeClass('.car_act');
    if (e.target.id === 'cart_bx' || $(e.target).parents('#cart_bx').length > 0) {
        e.stopPropagation();
    } else {
        $('.cart_box').hide(600);
    }
});

});



