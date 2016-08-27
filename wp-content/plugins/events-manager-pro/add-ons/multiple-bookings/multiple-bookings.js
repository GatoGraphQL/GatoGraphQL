//checkout cart
jQuery(document).ready(function($){
	$(document).on( 'click', '.em-cart-table a.em-cart-table-details-show', function(e){
		e.preventDefault();
		var event_id = $(this).hide().attr('rel');
		$('#em-cart-table-details-hide-'+event_id).show();
		$('.em-cart-table-event-details-'+event_id).show();
		$('#em-cart-table-event-summary-'+event_id+' .em-cart-table-spaces span').hide();
		$('#em-cart-table-event-summary-'+event_id+' .em-cart-table-price span').hide();
	});
	$(document).on('em_booking_success', function( e, response ) {
		if( EM.mb_redirect && !response.checkout ){
			window.location.href = EM.mb_redirect;
		}
	});
	$(document).on( 'click', '.em-cart-table a.em-cart-table-details-hide', function(e){
		e.preventDefault();
		var event_id = $(this).hide().attr('rel');
		$('#em-cart-table-details-show-'+event_id).show();
		$('.em-cart-table-event-details-'+event_id).hide();
		$('#em-cart-table-event-summary-'+event_id+' .em-cart-table-spaces span').show();
		$('#em-cart-table-event-summary-'+event_id+' .em-cart-table-price span').show();
	});
	$(document).on( 'click', '.em-cart-table a.em-cart-table-actions-remove', function(e){
		e.preventDefault();
		var event_id = $(this).attr('rel');
		container = $(this).parents('.em-cart-table').first().parent();
		$.ajax({
			url: EM.bookingajaxurl,
			data: { 'action':'emp_checkout_remove_item', 'event_id':event_id },
			dataType: 'jsonp',
			type:'post',
			beforeSend: function(formData, jqForm, options) {
				$('.em-booking-message').remove();
				container.append('<div id="em-loading"></div>');
			},
			success : function(response, statusText, xhr, $form) {
				$('#em-loading').remove();
				//show error or success message
				if(response.result){
					$(document).trigger('em_cart_refresh');
				}else{
					$('<div class="em-booking-message-error em-booking-message">'+response.message+'</div>').insertBefore(em_booking_form);
				    $('html, body').animate({ scrollTop: em_booking_form.parent().offset().top - 30 }); //sends user back to top of form
				}
			}
		});
		return false;
	});
	$(document).on( 'click', '.em-cart-actions-empty', function(e){
		if( !confirm(EM.mb_empty_cart) ) return false;
		e.preventDefault();
		container = $(this).parent();
		$.ajax({
			url: EM.bookingajaxurl,
			data: { 'action':'emp_empty_cart'},
			dataType: 'jsonp',
			type:'post',
			beforeSend: function(formData, jqForm, options) {
				$('.em-booking-message').remove();
				container.append('<div id="em-loading"></div>');
			},
			success : function(response, statusText, xhr, $form) {
				$('#em-loading').remove();
				//show error or success message
				window.location.reload();
			}
		});
		return false;
	});
	$(document).on('em_checkout_page_refresh em_cart_refresh',function(){
		$('.em-checkout-page-contents').append('<div id="em-loading"></div>').load( EM.ajaxurl, { 'action':'em_checkout_page_contents' } );
	});
	$(document).on('em_cart_page_refresh em_cart_refresh',function(){
		$('.em-cart-page-contents').append('<div id="em-loading"></div>').load( EM.ajaxurl, { 'action':'em_cart_page_contents' } );
	});
	if( EM.cache ){
		$(document).trigger('em_checkout_page_refresh');
		$(document).trigger('em_cart_page_refresh');
	}
});