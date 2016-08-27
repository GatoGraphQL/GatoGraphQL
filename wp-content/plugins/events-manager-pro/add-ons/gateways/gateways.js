//Select Submission
$('.em-booking-gateway select[name=gateway]').change(function(e){
	var gateway = $(this).find('option:selected').val();
	$('div.em-booking-gateway-form').hide();
	$('div#em-booking-gateway-'+gateway).show();
});
//Button Submission
$('input.em-gateway-button').click(function(e){
	//prevents submission in order to append a hidden field and bind to the booking form submission event
	e.preventDefault();
	//get gateway name
	var gateway = $(this).attr('id').replace('em-gateway-button-','');
	var parent = $(this).parents('.em-booking-form').first();
	parent.find('input[name=gateway]').remove();
	parent.append('<input type="hidden" name="gateway" value="'+gateway+'" />');
	parent.trigger('submit');
	return false;
});