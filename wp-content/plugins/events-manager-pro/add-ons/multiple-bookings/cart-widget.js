jQuery(document).ready( function($){ 
	$(document).on('em_booking_success em_cart_widget_refresh em_cart_refresh',function(){
		$('.em-cart-widget').each( function( i, el ){
			el = $(el);
			var form = el.find('form');
			var formData = form.serialize();
			el.find('.em-cart-widget-contents').text(form.find('input[name="loading_text"]').val()).load( EM.bookingajaxurl, formData );
		});
	});
	if( EM.cache ){
		$(document).trigger('em_cart_widget_refresh');
	}
});