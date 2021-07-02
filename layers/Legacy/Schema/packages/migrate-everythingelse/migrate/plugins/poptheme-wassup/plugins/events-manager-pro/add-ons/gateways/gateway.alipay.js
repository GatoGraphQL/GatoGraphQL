//add alipay redirection
$(document).bind('em_booking_gateway_add_alipay', function(event, response){ 
	// called by EM if return JSON contains gateway key, notifications messages are shown by now.
	if(response.result){
		var ppForm = $('<form action="'+response.gateway_url+'" method="post" id="em-alipay-redirect-form"></form>');
		$.each( response.gateway_vars, function(index,value){
			ppForm.append('<input type="hidden" name="'+index+'" value="'+value+'" />');
		});
		ppForm.append('<input id="em-alipay-submit" type="submit" style="display:none" />');
		ppForm.appendTo('body').trigger('submit');
	}
});