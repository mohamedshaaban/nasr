// price range


// price range




// Date get push value
$("#countries_msdd").click(function(){
	$(this)("#countries_child").show();
	});

	$('.next-date').change(function() {
		$('.absolute-date').val($(this).val());
	});
// Date get push value


// upload file get push value
	$('.upld').on('change',function(){
		// output raw value of file input
		$('.abs-upld').html($(this).val()); 
	  
	   // or, manipulate it further with regex etc.
	   var filename = $(this).val();
	   // .. do your magic
	  
	   $('.abs-upld').val(filename);
	  });
// upload file get push value


function showValue(h) {
	console.log(h.name, h.value);
}

$("#tech").change(function () {
	console.log("by jquery: ", this.value);
})
//
$(".inpt").keyup(function () {
	if (this.value.length == this.maxLength) {
		$(this).parent().next(".col-md-2.col-sm-2.col-xs-2").find('.inpt').focus();
	}
});

// $(".dropdown-toggle").mouseover(function () {
// 	if (!$(this).parent().hasClass("open")) {
// 		$(this).trigger("click");
// 		$("ul.dropdown-menu.mega-dropdown-menu.row").show(600)
// 	}
// });



$(window).load(function(){
	if (this.innerWidth > 960) {
		// $(".dropdown-toggle").removeAttr("data-toggle");
		// $(".dropdown-toggle").attr("data-hover","dropdown");
		// alert("asdasddas");
		$(".dropdown-toggle").parent("li").mouseover(function(){
		$("ul.dropdown-menu.mega-dropdown-menu.row").show(200).slideDown(600);
		});
		$(".dropdown-toggle").parent("li").mouseleave(function(){
		$("ul.dropdown-menu.mega-dropdown-menu.row").hide(200).slideUp(600);
		});
	}
	});


$('#search_button').on('click', function (fn) {
	fn.preventDefault();
	$('.search_box_cover').addClass('active');
});
$('#closeButton').on('click', function (fn) {
	fn.preventDefault();
	$('.search_box_cover').removeClass('active');
});

$('.remove').on('click', function (e) {
	e.preventDefault();
	e.stopPropagation();
	$(this).closest('.border_dashed').slideUp('300', deleteData());
});
$('.remove_list').on('click', function (e) {
	e.preventDefault();
	e.stopPropagation();
	$(this).closest('.whish_gray_bx').slideUp('300', deleteData());
});
$('#cartEnable').on('click', function (e) {
	$(this).addClass('car_act');
	e.preventDefault();
	e.stopPropagation();
	$('.cart_box').show(600);

});
$(document).on('click', function (e) {
	$('#cartEnable').removeClass('.car_act');
	if (e.target.id === 'cart_bx' || $(e.target).parents('#cart_bx').length > 0) {
		e.stopPropagation();
	} else {
		$('.cart_box').hide(600);
	}
});

$(".bannerSlider").slick({
	dots: false,
	autoplay: false,
	infinite: true,
	slidesToShow: 1,
	slideswToScroll: 1,
	arrows: true,
	fade: true,
	cssEase: 'cubic-bezier(0.7, 0, 0.3, 1)',
	speed: 900,
	touchThreshold: 100
});

$(".categories-slide").slick({
	dots: false,
	autoplay: false,
	infinite: true,
	slidesToShow: 6,
	slideswToScroll: 1,
	arrows: true,
	responsive: [
		{
			breakpoint: 1024,
			settings: {
			slidesToShow: 6
		  }
		},
		{
			breakpoint: 768,
			settings: {
			slidesToShow: 5
		  }
		},
	    {
	      	breakpoint: 480,
	      	settings: {
	        arrows: false,
	        centerMode: true,
	        centerPadding: '0px',
	        slidesToShow: 2
	      }
	    }
	  ]
});

$(".sploffer-slide").slick({
	dots: false,
	autoplay: false,
	infinite: true,
	slidesToShow: 4,
	slideswToScroll: 1,
	arrows: true,
	responsive: [
		{
			breakpoint: 1024,
			settings: {
			slidesToShow: 3
		  }
		},
		{
			breakpoint: 768,
			settings: {
			slidesToShow: 3
		  }
		},
		{
			breakpoint: 680,
			settings: {
			slidesToShow: 2
		  }
		},
	    {
	      	breakpoint: 480,
	      	settings: {
	        arrows: false,
	        centerMode: true,
	        centerPadding: '50px',
	        slidesToShow: 1
	      }
	    }
	  ]
});

$(".new-arrvl-slide").slick({
	dots: false,
	autoplay: false,
	infinite: true,
	slidesToShow: 4,
	slideswToScroll: 1,
	arrows: true,
	responsive: [
		{
			breakpoint: 1024,
			settings: {
			slidesToShow: 3
		  }
		},
		{
			breakpoint: 768,
			settings: {
			slidesToShow: 3
		  }
		},
	    {
	      	breakpoint: 480,
	      	settings: {
	        arrows: false,
	        centerMode: true,
	        centerPadding: '50px',
	        slidesToShow: 1
	      }
	    }
	  ]
});

var quantitiy = 0;
$('.quantity-right-plus').click(function (e) {
	e.preventDefault();
	var quantity = parseInt($('#quantity').val());
	if (quantity < 20) {
	$('#quantity').val(quantity + 1);
	}
});

$('.quantity-left-minus').click(function (e) {
	e.preventDefault();
	var quantity = parseInt($('#quantity').val());

	if (quantity > 0) {
		$('#quantity').val(quantity - 1);
	}
});


// when product size checkbox checked change label color to red
$('.product-size-checkbox-container input[type="checkbox"]').click(function () {
	if ($(this).is(':checked')) {
		$(this).closest('li').find('label').css("color", "#be1522");
	}
	else {
		$(this).closest('li').find('label').css("color", "black");
	}
})
$(".chat-clk").click(function(){
	$(".chat-box").toggle(400);
});
$(".clox").click(function(){
	$(".chat-box").hide(400);
});

// cehckout review tab click show panels
$('.sec-checkout .review-tab').click(function(){
	$('.sec-checkout .discount-li').removeClass('hidden');
	$('.sec-checkout .panel-ship-to').removeClass('hidden');
	$('.sec-checkout .panel-ship-method').removeClass('hidden');
	$('.sec-checkout .btn-place-oreder').removeClass('hidden');
});
// shipping tab click hide panels
$('.sec-checkout .shipping-tab').click(function(){
	$('.sec-checkout .discount-li').addClass('hidden');
	$('.sec-checkout .panel-ship-to').addClass('hidden');
	$('.sec-checkout .panel-ship-method').addClass('hidden');
	$('.sec-checkout .btn-place-oreder').addClass('hidden');
});

// Item slider inner details page
	$('.slider-for').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			centerMode: true,
			asNavFor: '.slider-nav'
	});
	$('.slider-nav').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			dots: false,
			arrows: false,
			centerMode: false,
			focusOnSelect: true,
	});


$(".remove-disabled").click(function (q) {
	q.stopPropagation();
	$(".inpt").removeAttr("disabled");
	$(".aftr-edt").show();
});
$(".chg-psw").change(function () {
	if ($(this).is(':checked')) {
		$(".Change-pswd").show();
	}
	else {
		$(".Change-pswd").hide();
	};


});



//###############################check box Tab#################################

$('#myTab li').click(function (e) {
// e.preventDefault();
  $(this).find('a').tab('show');
// $(this).tab('show');
   $(this).closest('ul').find('input[type="checkbox"]').prop('checked','');
   $(this).closest('li').find('input[type="checkbox"]').prop('checked','checked');
 
});

//###############################File Upload#################################

//$(document).ready(function(){
	//document.getElementById("uploadBtn").onchange = function () {
	   // document.getElementById("uploadFile").value = this.value.substring(12);
	//};
// });


//###############################Search form Pop up#################################


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


