var em_setup_attendee_forms = function( spaces, fieldset, fieldset_container, fields_template){
	fieldset_container.find('input.em-date-input-loc').datepicker('destroy').attr('id', ''); //clear all datepickers
	fieldset_container.find('.em-time-range input.em-time-end, .em-time-range input.em-time-start').unbind(['click','focus','change']); //clear all timepickers - consequently, also other click/blur/change events, recreate the further down
	//get the attendee form template and clone it
	var form = fields_template.clone().removeClass('em-attendee-fields-template').addClass('em-attendee-fields');
	//add or subtract fields according to spaces
	var current_forms = fieldset_container.find('.em-attendee-fields');
	if( current_forms.length < spaces ){
		for( var i= current_forms.length ; i < spaces; i++ ){
			new_form = form.clone().appendTo(fieldset).show();
			new_form.find("*:contains('#NUM#')").text( function(it,text){ this.innerText = text.replace('#NUM#', i+1); } );
		}
	}else if( current_forms.length > spaces ){
		var current_forms_length = current_forms.length;
		for( var i= spaces; i < current_forms_length; i++ ){
			current_forms.last().remove();
			current_forms.splice(current_forms.length-1,1);
		}
	}
	//clean up
	em_setup_datepicker(fieldset_container);
	em_setup_timepicker(fieldset_container);
	form.remove();
};
$('.em-booking-form p.em-tickets-spaces select.em-ticket-select').change( function(e){
	var el = $(this);
	var spaces = el.children('option:selected').val();
	var fieldset = el.closest('.em-booking-form').find('.em-attendee-fieldset');
	var fieldset_container = fieldset.parent();
	var fields_template = el.closest('.em-booking-form').find('.em-attendee-fields-template');
	em_setup_attendee_forms(spaces, fieldset, fieldset_container, fields_template);
});
$('.em-booking-form table.em-tickets select.em-ticket-select').change( function(e){
	var el = $(this);
	var spaces = el.children('option:selected').val();
	var row = el.closest('tr').next();
	var fieldset = row.find('.em-attendee-fieldset');
	var fieldset_container = row.find('.em-attendee-fieldset').parent();
	var fields_template = row.find('.em-attendee-fields-template');
	em_setup_attendee_forms(spaces, fieldset, fieldset_container, fields_template);
	if( spaces > 0 ){
		row.show();
	}else{
		row.hide();
	}
});