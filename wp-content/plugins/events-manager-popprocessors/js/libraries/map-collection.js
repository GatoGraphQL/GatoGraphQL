(function($){
popMapCollection = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	formMapLocationGeocode : function(args) {
	
		var t = this;
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
			popMap.geocode(pageSection, block, map, address.join(', '), latInput, lngInput);
		});
	},

	modalMap : function(args) {

		var t = this;
		var domain = args.domain, targets = args.targets;

		targets.on('show.bs.modal', function(e) {

			var modal = $(this);
			var link = $(e.relatedTarget);
			t.execModalMap(domain, modal, link);
		});
	},

	modalMapBlock : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets, link = args.relatedTarget;
		var modal = targets.closest('.modal');

		// Need to refresh the map when the modal shows again, otherwise when opening the 2nd modal map,
		// the 1st one will look screwed
		// var map = block.find('.pop-map');
		// modal.on('shown.bs.modal', function(e) {

		// 	popMap.refresh(pageSection, block, map);
		// });

		// Do already execute it, the first time, since this will be executed when creating the modal inside the MODALS pageSection
		t.execModalMap(domain, modal, link);
	},

	//-------------------------------------------------
	// PROTECTED functions
	//-------------------------------------------------

	execModalMap : function(domain, modals, link) {

		var t = this;

		// Make sure the link we got is the original one, and not the intercepted one
		// This is important to later on retrieve the corresponding block and pageSection,
		// under which are stored the markerIds
		link = popManager.getOriginalLink(link);

		var block = popManager.getBlock(link);
		var pageSection = popManager.getPageSection(block);
		var mapDiv = modals.find('.pop-map');

		// Add the data-marker-ids and the header to the map div
		mapDiv.data('markers-pagesection', pageSection);
		mapDiv.data('markers-block', block);
		mapDiv.data('markers-removecurrent', true);
		var markerIds = popMap.getMarkerIdsFromLinkURLs(link);
		// mapDiv.data('marker-ids', markerIds);
		mapDiv.data('marker-ids-'+removeScheme(domain), markerIds);
	},

	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popMapCollection, ['formMapLocationGeocode', 'modalMap', 'modalMapBlock']);