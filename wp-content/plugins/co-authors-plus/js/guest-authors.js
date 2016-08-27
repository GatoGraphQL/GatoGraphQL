jQuery(document).ready(function($){
	$('.reassign-option').on('click',function(){
		$('#wpbody-content input#submit').addClass('button-primary').removeAttr('disabled');
	});
	$('#leave-assigned-to').select2({
		minimumInputLength: 2,
		width: 'copy',
		multiple: false,
		ajax: {
			url: ajaxurl,
			dataType: 'json',
			data: function( term, page ) {
				return {
					q: term,
					action: 'search_coauthors_to_assign',
					guest_author: $('#id').val()
				};
			},
			results: function( data, page ) {
				return { results: data };
			}
		},
		formatResult: function( object, container, query ) {
			return object.display_name;
		},
		formatSelection: function( object, container ) {
			return object.display_name;
		}
	}).on('change', function() {
		$('#reassign-another').trigger('click');
	});
});