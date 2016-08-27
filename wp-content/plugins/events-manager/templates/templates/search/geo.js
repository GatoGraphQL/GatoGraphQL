function em_geo_search_init(){
	jQuery('input.em-search-geo').each(function(i){
		var input = /** @type {HTMLInputElement} */ jQuery(this);
		var wrapper = input.closest('div.em-search-geo'); 
		var autocomplete = new google.maps.places.Autocomplete(input[0]);
		var geo_coords = wrapper.find("input.em-search-geo-coords");

		var geo_field_status = function( status ){
			wrapper.data('status',status);
			var em_search = wrapper.closest('.em-search');
			if( status == 'on' ){
				wrapper.css('background-image', wrapper.css('background-image').replace('search-geo.png', 'search-geo-on.png').replace('search-geo-off.png', 'search-geo-on.png'));
				em_search.find('select.em-search-country option:first-child').prop('selected','selected').trigger('change');
				em_search.find('.em-search-location').slideUp();
				em_search.find('.em-search-geo-units').slideDown();
			}else{
				if( status == 'off' ){
					wrapper.css('background-image', wrapper.css('background-image').replace('search-geo.png', 'search-geo-off.png').replace('search-geo-on.png', 'search-geo-off.png'));
				}else{
					wrapper.css('background-image', wrapper.css('background-image').replace('search-geo-off.png', 'search-geo.png').replace('search-geo-on.png', 'search-geo.png'));
				}
				geo_coords.val('');
				em_search.find('.em-search-location').slideDown();
				em_search.find('.em-search-geo-units').slideUp();
			}
		};

		var ac_listener = function( place ) {
			var place = autocomplete.getPlace();
			if( !place || !place.geometry ){ //place not found
				if( input.val() == '' || input.val() == EM.geo_placeholder ){ 
					geo_field_status(false);
				}else{
					if( wrapper.data('last-search') == input.val() ){ 
						geo_field_status('on');
						geo_coords.val( wrapper.data('last-coords') );
						return; 
					}
	              	//do a nearest match suggestion as last resort
					if( input.val().length >= 2 ){
						geo_field_status(false);
						autocompleteService = new google.maps.places.AutocompleteService();
				        autocompleteService.getPlacePredictions( {'input': input.val(), 'offset': input.val().length }, function listentoresult(list, status) {
		                    if(list != null && list.length != 0) {
		                        placesService = new google.maps.places.PlacesService(document.getElementById('em-search-geo-attr'));
		                        placesService.getDetails( {'reference': list[0].reference}, function detailsresult(detailsResult, placesServiceStatus) {
		                            //we have a match, ask the user
                    				wrapper.data('last-search', detailsResult.formatted_address );
                    				wrapper.data('last-coords', detailsResult.geometry.location.lat() + ',' + detailsResult.geometry.location.lng());
	                                if( input.val() == detailsResult.formatted_address || confirm(EM.geo_alert_guess.replace('%s', '"'+detailsResult.formatted_address+'"')) ){
		                                geo_coords.val( detailsResult.geometry.location.lat() + ',' + detailsResult.geometry.location.lng() );
		        						geo_field_status('on');
		                                input.val(detailsResult.formatted_address);
	                                }else{
	                                	input.data('last-key',false);
	                					geo_field_status('off');
	                                }
		                        });
		                    }else{ geo_field_status('off'); }
						});
					}else{ geo_field_status('off'); }
				}
				wrapper.data('last-search', input.val() );
				wrapper.data('last-coords', geo_coords.val());
				return;
			}
			geo_coords.val( place.geometry.location.lat() + ',' + place.geometry.location.lng() );
			geo_field_status('on');
			wrapper.data('last-search', input.val() );
			wrapper.data('last-coords', geo_coords.val());
		};
		google.maps.event.addListener(autocomplete, 'place_changed', ac_listener);
				
		if( geo_coords.val() != '' ){
			geo_field_status('on');
			wrapper.data('last-search', input.val() );
			wrapper.data('last-coords', geo_coords.val() );
		}
		input.keypress( function(e) {
			//if enter is pressed once during 'near' input, don't do anything so Google can select location, otherwise let behavior (form submittal) proceed 
			if( e.which == 13 ) {
				if( input.data('last-key') != 13 || wrapper.data('status') != 'on' ){
					e.preventDefault();
				}
			}else{
				geo_field_status(false);
			}
		}).keyup( function(e) {
			if( this.value == '' ){ geo_field_status(false); }
			else if( wrapper.data('last-search') != this.value ){ geo_field_status('off'); }
			input.data('last-key', e.which);
		}).blur( function(e){ //create HTML 5 placeholder effect if not HTML 5
			if( this.value == '' && !('placeholder' in document.createElement('input')) ) this.value = EM.geo_placeholder;
		}).focus( function(e){
			if( this.value == EM.geo_placeholder ) this.value='';
			input.data('last-key', 13);
		}).attr('placeholder', EM.geo_placeholder);
	});
}
jQuery(document).bind('em_maps_loaded', function(){ em_geo_search_init(); });