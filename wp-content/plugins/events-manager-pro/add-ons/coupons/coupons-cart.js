jQuery(document).ready( function($){
	$('input.em-coupon-code').on('keypress', function( event ){ 
		if( event.which == 13 ){
			$('button.em-coupon-code').trigger('click');
			event.preventDefault();
			return false;
		}
	});
	$('button.em-coupon-code').on('click', function(){
		var coupon_b = $(this);
		var coupon_el = coupon_b.prevAll('input'); 
		var coupon_val = coupon_el.val();
		$.ajax({
			url: EM.ajaxurl,
			data: {'coupon_code': coupon_val, 'action':'em_coupon_apply'},
			dataType: 'jsonp',
			type:'post',
			beforeSend: function(formData, jqForm, options) {
				$('.em-coupon-message').remove();
				if(coupon_val == ''){ return false; }
				coupon_el.before('<span id="em-coupon-loading"></span>');
			},
			success : function(response, statusText, xhr, $form) {
				if(response.result){
					window.location.reload();
				}else{
					coupon_b.parent().after('<span class="em-coupon-message em-coupon-error">'+response.message+'</span>');
				}
			},
			complete : function() {
				$('#em-coupon-loading').remove();
			}
		});
	});
});