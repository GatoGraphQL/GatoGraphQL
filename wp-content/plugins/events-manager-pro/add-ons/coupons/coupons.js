jQuery(document).ready( function($){
	$('.em-coupon-code').change(function(){
		var coupon_el = $(this); 
		var formdata = coupon_el.parents('.em-booking-form').serialize().replace('action=booking_add','action=em_coupon_check'); //simple way to change action of form
		$.ajax({
			url: EM.ajaxurl,
			data: formdata,
			dataType: 'jsonp',
			type:'post',
			beforeSend: function(formData, jqForm, options) {
				$('.em-coupon-message').remove();
				if(coupon_el.val() == ''){ return false; }
				coupon_el.after('<span id="em-coupon-loading"></span>');
			},
			success : function(response, statusText, xhr, $form) {
				if(response.result){
					coupon_el.after('<span class="em-coupon-message em-coupon-success">'+response.message+'</span>');
				}else{
					coupon_el.after('<span class="em-coupon-message em-coupon-error">'+response.message+'</span>');
				}
			},
			complete : function() {
				$('#em-coupon-loading').remove();
			}
		});
	});
});