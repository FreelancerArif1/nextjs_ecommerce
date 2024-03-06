$(document).on('click', '.size', function() {
    $('.size').removeClass("sizeBg");
    $(this).addClass("sizeBg");
});


//Share
$(document).on('mouseover', '.share_parent', function() {
    $('.share_box').show();
});
$(document).on('mouseleave', '.share_parent, .share_box', function() {
    $('.share_box').hide();
});



//My account
$(document).on('mouseover', '.userfull', function() {
    $('.table-parrent').hide();
    $('.notification-dropdwon-parent').hide();
    $('.user-account').show();
});
$(document).on('mouseleave', '#searchbar_section, .user-account', function() {
    $('.user-account').hide();
});



//Cart
$(document).on('click', '.view-cart', function() {
    $('.table-parrent').hide();
});
$(document).on('mouseover', '.fa-shopping-cart', function() {
    $('.table-parrent').show();
    $('.notification-dropdwon-parent').hide();
    $('.user-account').hide();
});
$(document).on('mouseleave', '#searchbar_section, .table-parrent', function() {
    $('.table-parrent').hide();
});




//Desktop category
$(document).on('click', '.desktop-bars-btn', function() {
    $('.mobile-categories').css({ 'left': '0', 'transition': '0.5s' });
    $('.mobile_nav').show();
    setTimeout(() => {
        $('.categories-close').show();
    }, 400);
});


//Notification
$(document).on('mouseover', '.notificationfull', function() {
    $('.notification-dropdwon-parent').show();
    $('.table-parrent').hide();
    $('.user-account').hide();
});
$(document).on('mouseleave', '#searchbar_section, .notification-dropdwon-parent', function() {
    $('.notification-dropdwon-parent').hide();
});

//Mobile category
$(document).on('click', '.categories-bars-btn', function() {
    $('.mobile-categories').css({ 'left': '0', 'transition': '0.5s' });
    setTimeout(() => {
        $('.categories-close').show();
    }, 400);
});

$(document).on('click', '.categories-close,.cat_cc i', function() {
    $('.mobile-categories').css({ 'left': '-100%', 'transition': '0.5s' });
    $('.categories-close').hide();
   
    if($(window).width() > 991){
        $('.mobile_nav, #fixed_bar').hide();
    }

});





$(document).on('click', '#navbarDropdownMenuLink', function() {
    $('.mobile-categories').css({ 'left': '-100%', 'transition': '0.5s' });
    $('.categories-close').hide();
});
$(document).on('click', '#last_level a', function(e) {
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
    return false;
});

$(document).on('click', '#dropstep_2', function(e) {
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
    console.log('dddddd');
    return false;
});

jQuery(document).on('click','.all_property',function(){
    jQuery('#allProperty').trigger('click');
});



/**Mobile Menu **/

jQuery(document).on('click','.close_mobile_nav',function(){
    jQuery('.categories-close').trigger('click');
});

jQuery(document).ready(function(){
	var modernAccordion = $('.mdn-accordion');
	if( modernAccordion.length > 0 ) {
		modernAccordion.each(function(){
			var each_accordion = $(this);
			$('.accordion-toggle:checked').siblings('ul').attr('style', 'display:none;').stop(true,true).slideDown(300);
			each_accordion.on('change', '.accordion-toggle', function(){
				var toggleAccordion = $(this);
				if (toggleAccordion.is(":radio")) {
					toggleAccordion.closest('.mdn-accordion').find('input[name="' + $(this).attr('name') + '"]').siblings('ul')
					.attr('style', 'display:block;').stop(true,true).slideUp(300); 
					toggleAccordion.siblings('ul').attr('style', 'display:none;').stop(true,true).slideDown(300);									
			   } else {				
					(toggleAccordion.prop('checked')) ? toggleAccordion.siblings('ul')
					.attr('style', 'display:none;').stop(true,true).slideDown(300) : toggleAccordion.siblings('ul')
					.attr('style', 'display:block;').stop(true,true).slideUp(300); 
			   }
			});
		});
	}
	$(document).on('click', '.mdn-accordion .accordion-title', function(e) {
		var $mdnRippleElement = $('<span class="mdn-accordion-ripple" />'),
		$mdnButtonElement = $(this),
		mdnBtnOffset = $mdnButtonElement.offset(),
		mdnXPos = e.pageX - mdnBtnOffset.left,
		mdnYPos = e.pageY - mdnBtnOffset.top,
		mdnSize = parseInt(Math.min($mdnButtonElement.height(), $mdnButtonElement.width()) * 0.5),
		mdnAnimateSize = parseInt(Math.max($mdnButtonElement.width(), $mdnButtonElement.height()) * Math.PI);
		$mdnRippleElement
		.css({
			top: mdnYPos,
			left: mdnXPos,
			width: mdnSize,
			height: mdnSize,
			backgroundColor: $mdnButtonElement.data("accordion-ripple-color")
		})
		.appendTo($mdnButtonElement)
		.animate({
			width: mdnAnimateSize,
			height: mdnAnimateSize,
			opacity: 0
		}, 800, function() {
			$(this).remove();
		});
	});	
});




//main menu
$(document).on('click', '.mobile-main-menu-btn', function() {
    $('.mobile-main-menu').css({ 'right': '0', 'transition': '0.5s' });
    setTimeout(() => {
        $('.main-menu-close').show();
    }, 400);

});
$(document).on('click', '.main-menu-close, .mobile-main-menu ul li', function() {
    $('.mobile-main-menu').css({ 'right': '-100%', 'transition': '0.5s' });
    $('.main-menu-close').hide();
});




$(document).on('click', '.mobile-account-btn', function() {
    $('.mobile-my-account').show();
});

$(document).on('click', '.mobile-my-account', function() {
    $('.mobile-my-account').show();
});

$(document).on('mouseout', '.mobile-my-account', function() {
    $('.mobile-my-account').hide();
});



//Search
$(document).on('click', '.mobile-account-btn', function() {
    $('.mobile-my-account').show();
});

$('body').on('click', '*:not(.mobile-my-account)', function() {
    $('.mobile-my-account').hide();
});

$('body').on('click', '#nextBtn', function() {
    $('.finish').closest('.col-md-4').find('.step-text').css({ 'color': 'rgb(0 137 87)' });

});



// $(document).on('click', '#brand_all_product', function(e) {
//     e.stopImmediatePropagation();
//     $('.profile_section').hide();
//     $('.search_products').show();
//     $('.promotion_page').show();
//     $('#brand_profile').removeClass('active');
//     $('#brand_all_product').addClass('active');
// });
// $(document).on('click', '#brand_profile', function() {
//     $('.search_products').hide();
//     $('.promotion_page').hide();
//     $('.profile_section').show();
//     $('#brand_all_product').removeClass('active');
//     $('#brand_profile').addClass('active');
// });

jQuery(document).on('click','.triger_tab',function(){
    jQuery('.section_shop,.profile_section').hide();
    jQuery('.'+jQuery(this).attr('data-tab-name')).show();
});


$(document).on('click', '.category_class', function() {
    $('.nav_wrapper').slideToggle();
    if ($('.changeableDrop').hasClass('fa-chevron-down')) {
        $('.changeableDrop').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    } else {
        $('.changeableDrop').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    }
});



$(document).on('click', '.same_info', function(e) {
    e.stopImmediatePropagation();
    if ($(this).is(':checked')) {
        $('.shipping_info').slideUp();
    } else {
        $('.shipping_info').slideDown();
    }
});
$(document).on('click', '.single-method', function(e) {
    e.stopImmediatePropagation();
    $('.paymentcheck').attr('checked', false);
    $(this).parent('li').find('.paymentcheck').attr('checked', true);
});
$(document).on('click', '.shipping_method_select', function(e) {
    e.stopImmediatePropagation();
    $('.info_career').attr('checked', false);
    $(this).find('.info_career').attr('checked', true);
    var cost = $(this).find('.info_career').attr('data-shippingCost');
    var total = $('.sumary_total').attr('data-total');
    $('.shopping_cost').text('BDT ' + cost);
    $('.sumary_total').text('BDT ' + (Number(cost) + Number(total)));
});


$(document).ready(function(e) {
    $('.circle-1').css({ 'background': '#0093d9' });
    $('.circle-1-text').css({ 'color': '#0093d9' });
    $('.circle-1').html('<i class="fa fa-check" aria-hidden="true"></i>');
});


$(document).on('click', '.single_shipping', function() {
    $('.shipping_input').attr('checked', false);
    $(this).find('.shipping_input').attr('checked', true);

    $('.selectedShippingTitle').text($(this).find('.shipping_title').text());

    $('.selectedShippingSubtitle').text($(this).find('small').text());
    $('.total_shipping').text($(this).attr('data-shippingcost'));


    //$('.shipping_options').slideUp();
});
$(document).on('click', '.edit_shipping_btn', function(e) {
    e.stopImmediatePropagation();
    $('.shipping_options').slideToggle();
});

jQuery(document).on('click', '.change_location', function() {
    jQuery('#sOption').slideToggle();
});

jQuery(document).on('click', '.select_shipping_options .list-group-item', function() {
    jQuery(this).closest('.select_shipping_options').find('.list-group-item').removeClass('selected_shipping');
    jQuery(this).addClass('selected_shipping');
});


jQuery(document).on('click', '.paymentmethod .list-group-item', function() {
    jQuery(this).closest('.paymentmethod').find('.list-group-item').removeClass('selected_payment');
    jQuery(this).addClass('selected_payment');
});


jQuery(document).ready(function() {
    calculateShipping();
})
jQuery(document).on('click', '.select_shipping_options .list-group-item, .reload_calculation', function() {
    calculateShipping();
});




function calculateShipping() {
    let totalShippingCost = Number(0);
    let pickPointCost = Number(jQuery('#pickPointCost').attr('data-pickpoint-cost')) > 0 ? Number(jQuery('#pickPointCost').attr('data-pickpoint-cost')) : 0;
    if(pickPointCost > 0){
        totalShippingCost = Number(pickPointCost);
    }else{
        jQuery('.select_shipping_options .selected_shipping').each(function(key, val) {
            let singleShipping = Number(jQuery(this).attr('data-shipping-cost'));
            let singleQty = Number(jQuery(this).attr('data-qty'));
            totalShippingCost += Number((Number(singleShipping) * Number(singleQty)));
        });
        let grocery_shipping = Number(jQuery('.grocery_shipping_cost').attr('data-shipping-cost')) > 0 ? Number(jQuery('.grocery_shipping_cost').attr('data-shipping-cost')):0;
        if(grocery_shipping){
            totalShippingCost = Number(totalShippingCost) + Number(grocery_shipping);
        }
    }
    let total_packaging_cost = Number(jQuery('.data_packaging_cost').attr('data-packaging-cost-amount')) > 0 ? Number(jQuery('.data_packaging_cost').attr('data-packaging-cost-amount')):0;
    let total_security_charge = Number(jQuery('.data_security_charge').attr('data-security-charge-amount')) > 0 ? Number(jQuery('.data_security_charge').attr('data-security-charge-amount')):0;
    let data_sub_total = Number(jQuery('.data_sub_total').attr('data-subtotal-amount'));
    let coupon_discount = Number(jQuery('.coupon_discount').attr('data-coupon-discount')) > 0 ? Number(jQuery('.coupon_discount').attr('data-coupon-discount')) : 0;
    let voucher_discount = 0;
    if(coupon_discount > 0){
        jQuery('.coupon_discount').show();
    }
    if(voucher_discount > 0){
        jQuery('.show_voucher_discount').show();
    }
    let data_discount = coupon_discount+voucher_discount;
    let data_vat = Number(jQuery('.data_vat').attr('data-vat-amount')) > 0 ? Number(jQuery('.data_vat').attr('data-vat-amount')) : 0;
    let total = (data_sub_total + totalShippingCost +total_packaging_cost+total_security_charge+data_vat) - data_discount;
    jQuery('.shipping_cost_li').attr('data-shipping-cost', totalShippingCost)
    jQuery('.calculatedTotal').html(total);
    jQuery('.calculatedShipping').html(totalShippingCost);
    //console.log('totalShippingCost='+totalShippingCost+' data_discount='+data_discount+' data_vat='+data_vat+' total='+total+' total_packaging_cost='+total_packaging_cost+' total_security_charge='+total_security_charge);

}







jQuery(document).on('click', '.chating-box', function() {
    $('.chating_wrapper').fadeIn();
});
jQuery(document).on('click', '.chating-box-header', function() {
    $('.chating_wrapper').fadeToggle();
});


jQuery(document).on('click', '.close_chating', function() {
    $('.chating_wrapper').fadeOut();
});


// $(window).scroll(function() {
//     var body = $('body'),
//         scroll = $(window).scrollTop();
//     if (scroll >= 100) body.addClass('sticky_nav');
//     else body.removeClass('sticky_nav');
// });



$('#loginModal').on('hidden.bs.modal', function () {
    $('.buyNow').attr('disabled', false);
    $('.buyNow').html('Buy Now');
    // $('.buy_now').attr('disabled', false);
    // $('.buy_now').html('Buy Now');
    $('.addToCart').attr('disabled', false);
    $('.addToCart').html('<i class="fa fa-shopping-basket"></i> Add To Cart');
    $('.add_to_cart').attr('disabled', false);
    $('.add_to_cart').html('<i class="fa fa-shopping-basket"></i> Add To Cart');
    $('.buy_now').attr('disabled', false);
    $('.buy_now').html('<i class="fa fa-shopping-cart"></i> Buy Now');
     
});


jQuery(document).on('click', '.suggest_cross', function() {
    $('.search_suggest_wrapper').hide();
     $('.searchContent').val('');
 });
 
 $(document).on('blur', '.search_suggest_wrapper, .searchbox', function() {
     setTimeout(function(){ 
         $('.search_suggest_wrapper').hide();
     },200);
 });



 
jQuery(document).on('click', '.mobile_suggest_cross', function() {
    $('.mobile_search_suggest_wrapper').hide();
     $('.searchContent').val('');
 });
 
 $(document).on('blur', '.mobile_search_suggest_wrapper, .searchbox', function() {
     setTimeout(function(){ 
         $('.mobile_search_suggest_wrapper').hide();
     },200);
 });


 


//Quantity
$(document).on('click', '.plus', function() {
    // $(this).closest('.quantity_calculate').find('.qty').attr('data-qty');
     $qty = parseInt($(this).closest('.quantity_calculate').find('.qty').attr('data-qty')) + 1;
     if ($qty > parseInt($(this).closest('.quantity_calculate').find('.qty').attr('data-total_qty'))) {
         $qty = parseInt($(this).closest('.quantity_calculate').find('.qty').attr('data-total_qty'));
     }
     $(this).closest('.quantity_calculate').find('.qty').attr('data-qty', parseInt($qty));
     $(this).closest('.quantity_calculate').find('.qty').text(parseInt($qty));
     $(this).closest('.quantity_calculate').find('.qtyInput').val(parseInt($qty));
     console.log('');
     return false;
 });
 $(document).on('click', '.minus', function() {
     $qty = parseInt($('.qty').attr('data-qty')) - 1;
     if ($qty < 1) {
         $qty = 1;
     }
     $('.qty').attr('data-qty', parseInt($qty));
     $('.qty').text(parseInt($qty));
     $('.qtyInput').val(parseInt($qty));
 });



 //Variable product QTY
 $(document).on('click', '.variable_plus', function() {
    let maxQty = [];
    $('.variant_input').each(function() {
        let currentQty = $(this).attr('data-additional-qty');
        if (currentQty == -1) {
            maxQty.push(currentQty);
        }
        if (currentQty != 'NaN') {
            maxQty.push(currentQty);
        }
    });

    $qty = parseInt($('.qty').attr('data-qty')) + 1;

    maxQty = Math.min(...maxQty);
    console.log('Minimum Qty:' + maxQty);

    if (maxQty == -1) {
        alert( 'Please select all product combination first!', "error");
        return false;
    }

    if ($qty > maxQty) {
        alert('You can not add more than ' + maxQty + ' quantity for this product combination!', "error");
        return false;
    }

    if ($qty > parseInt($('.qty').attr('data-total_qty'))) {
        $qty = parseInt($('.qty').attr('data-total_qty'));
    }
    $('.qty').attr('data-qty', parseInt($qty));
    $('.qty').text(parseInt($qty));
    $('.qtyInput').val(parseInt($qty));

});




$(document).on('click', '.left_cart_icon', function() {
    $('.left_cart').show(500);
});

$(document).on('click', '.cart_close', function() {
    $('.left_cart').hide(500);
});


$(document).on('click', '.show_checkout_section', function() {
    $('#cart-page').hide();
    $('#checkout_section').show();
});


$(document).on('click', '.back_to_cart', function() {
    $('#cart-page').show();
    $('#checkout_section').hide();
});


$(document).on('click','.add_to_cart',function(){
    setTimeout(function(){ $('.left_cart_icon').addClass('left_cart_ico');}, 1000);
    setTimeout(function(){ $('.left_cart_icon').removeClass('left_cart_ico');}, 3000);
})

$(document).on('click','.nav-link',function(){
    $('.all_item').hide();
    $('.dynamic_nav_link').removeClass('active');
});


$(document).on('click','.dynamic_nav_link',function(){
    $('.nav-link').removeClass('active');
    $('.dynamic_nav_link').removeClass('active');
    $(this).addClass('active');
    $('.tab-pane').hide();
    var id = $(this).attr('data-id');
    $('.all_item').hide();
    $('#description').hide();
    $('#specification').hide();
    $('#description').removeClass('show active');
    $('.dynamic_section_'+id).show();
});

$(document).on('click','.partial_want',function(){
    //alert($(this).val());
    // if($('.partial_want:checked')){
    //     $('.partial_request_amount').show();
    // }else{
    //     $('.partial_request_amount').hide();
    // }
});


$(document).on('click', '#address_list', function() {
    $('#list').show();
    $('#add').hide();
});

$(document).on('click', '#address_add', function() {
    $('#list').hide();
    $('#add').show();
});


$(document).ready(function () {
    $('input[name="intervaltype"]').click(function () {
        $(this).tab('show');
        $(this).removeClass('active');
    });
})



jQuery(document).on('click', '.has_child', function() {
    $(this).closest('.common_parent').find('.child:first').toggle();
    if($(this).find('.fa').hasClass('fa-chevron-right')){
        $(this).find('.fa').removeClass('fa-chevron-right').addClass('fa-chevron-down');
    }else{
        $(this).find('.fa').removeClass('fa-chevron-down').addClass('fa-chevron-right');
    }
});



jQuery(document).on('click', '.whatlogin', function() {
    var val = $(this).attr('data-radiovalue');
    if(val == 500){
        $('.otp_login').show();
        $('.password_login').hide();
    }else{
        $('.otp_login').hide();
        $('.password_login').show();
    }
});

jQuery(document).on('click', '.whatloginPopup', function() {
    var val = $(this).attr('data-radiovalue');
    if(val == 500){
        $('.otp_login_popup').show();
        $('.password_login_popup').hide();
    }else{
        $('.otp_login_popup').hide();
        $('.password_login_popup').show();
    }
});


jQuery(document).on('mouseover', '.has_layer_2', function() {
    var id = $(this).attr('data-layertwoparent');
    if(id){
        $('.layers_2').show();
        $('.layer_2_sinlge').hide();
        $('.layertwochild'+id).show();
    }else{
        $('.layers_2').hide();
    }
    $('.layers_3').hide();
});

jQuery(document).on('mouseover', '.has_layer_3', function() {
    var id = $(this).attr('data-layerthreeparent');
    if(id){
        $('.layers_3').show();
        $('.layer_3_sinlge').hide();
        $('.layerthreechild'+id).show();
    }else{
        $('.layers_3').hide();
    }
});


jQuery(document).on('mouseover', '.banner_slider_wrap', function() {
    $('.layers_2').hide();
    $('.layers_3').hide();
    $("#sticky_category").slideUp();
});







$(document).ready(function(e) {
    setInterval(function(){
       $('.rightlst').trigger('click');
    },5000);
});



jQuery(document).on('click', '.quickview_description', function() {
    $('.quickview_specification_tab').removeClass('active');
    $('.quickview_description_tab').addClass('active');
});

jQuery(document).on('click', '.quickview_specification', function() {
    $('.quickview_description_tab').removeClass('active');
    $('.quickview_specification_tab').addClass('active');
});



$('#client_section .carousel .carousel-item').each(function(){
    var minPerSlide = 3;
    var next = $(this).next();
    if (!next.length) {
    next = $(this).siblings(':first');
    }
    next.children(':first-child').clone().appendTo($(this));
    
    for (var i=0;i<minPerSlide;i++) {
        next=next.next();
        if (!next.length) {
            next = $(this).siblings(':first');
          }
        
        next.children(':first-child').clone().appendTo($(this));
      }
});



jQuery(document).on('click', '.link_level', function() {
    if($(this).hasClass('old')){
        $(this).removeClass('old');
    }else{
        $(this).addClass('old');
    }
});



jQuery(document).on('click', '.search_filter', function() {
    $('.search_sidebar').slideToggle();
    if($('.changeable_fa').hasClass('fa-chevron-right')){
        $('.changeable_fa').removeClass('fa-chevron-right');
        $('.changeable_fa').addClass('fa-chevron-down');
    }else{
        $('.changeable_fa').addClass('fa-chevron-right');
        $('.changeable_fa').removeClass('fa-chevron-down');
    }
});


jQuery(document).on('change','#quotation_action',function(){
    jQuery('.accept_div').hide();
    if(jQuery(this).find('option:selected').val() == '2'){
        jQuery('.accept_div').show();
    }
});

jQuery(document).on('click','.hover_icon_group .fa-eye',function(){
    let modal = jQuery(this).attr('data-modal');
    let selectedModal = '#quickViewModal'+modal;
    jQuery(selectedModal).find('.quick_view img').each(function(key,val){
        jQuery(this).attr('src',jQuery(this).attr('data-src'));
    })
});

jQuery(document).on('click','.cash_on_delivery',function(){
    jQuery('.partial_payment').hide();
});


jQuery(document).on('click','.online_payment',function(){
    jQuery('.partial_payment').show();
});

// jQuery(document).on('click','#brand_profile',function(){
//     jQuery('.page_shop .filter_responsive_row, .page_shop .search_page_orderBy').hide();
// });

// // jQuery(document).on('click','#brand_all_product',function(){
// //     jQuery('.page_shop .filter_responsive_row, .page_shop .search_page_orderBy').show();
// // });



jQuery(document).on('click','.additionalOption',function(){
    jQuery('.corporate_division').removeClass('additionalOption');
});

// jQuery(document).on('click','.addagain',function(){
//     jQuery(this).closest('.cart_product_group').hide();
// });

jQuery(document).on('click','.orsignup',function(){
    jQuery('.close').trigger('click');
    jQuery('.cart_close').trigger('click');
});


jQuery(document).on('mouseleave', '.mouse_leave_div', function() {
    jQuery("#sticky_category").slideUp();
    jQuery("#layer2_main").slideUp();
    jQuery("#layer3_main").slideUp();
});
jQuery(document).on('mouseover', '#searchbar_section', function() {
    jQuery("#sticky_category").slideUp();
    jQuery("#layer2_main").slideUp();
    jQuery("#layer3_main").slideUp();
});


jQuery(document).on('mouseleave','#sticky_category',function(){
    jQuery("#sticky_category").slideUp();
    jQuery("#layer2_main").slideUp();
    jQuery("#layer3_main").slideUp();
 });
 
 jQuery(document).on('click','.desktop_sticky_menu',function(){
    $("#sticky_category").slideToggle();
 });

 jQuery(document).on('mouseover','.secondlayer',function(){
    $('.thirdLayerparent').hide();
    jQuery(this).closest('.list-1').find('.thirdLayerparent').css({'display':'block'});
    //console.log('sucesss');
});


 var $post = $(".addClass");
$post.toggleClass("animateElement");
setInterval(function(){   
     $post.removeClass("animateElement");
  setTimeout(function(){
    $post.addClass("animateElement");    
}, 1000);
}, 4000);

