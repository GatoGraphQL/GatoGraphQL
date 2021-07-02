"use strict";
(function($){
window.pop.MapCollection = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	formMapLocationGeocode : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.find('input.address-input').change(function() {

			var input = $(this);
			var locationMapGeocode = input.closest('div.pop-map-locationgeocode');

			var address = [];
			locationMapGeocode.find('input.address-input').each(function() {

				var addressInput = $(this);
				if (addressInput.val()) {
					address.push(addressInput.val());
				}
			});

			var map = locationMapGeocode.find('div.pop-map');
			var latInput = locationMapGeocode.find('input.address-lat');
			var lngInput = locationMapGeocode.find('input.address-lng');
			pop.Map.geocode(pageSection, block, map, address.join(', '), latInput, lngInput);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.MapCollection, ['formMapLocationGeocode']);