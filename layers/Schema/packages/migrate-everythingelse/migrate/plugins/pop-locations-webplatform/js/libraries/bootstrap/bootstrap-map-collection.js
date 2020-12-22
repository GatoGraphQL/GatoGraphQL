"use strict";
(function($){
window.pop.BootstrapMapCollection = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	modalMap : function(args) {

		var that = this;
		var domain = args.domain, targets = args.targets;

		targets.on('show.bs.modal', function(e) {

			var modal = $(this);
			var link = $(e.relatedTarget);
			that.execModalMap(domain, modal, link);
		});
	},

	modalMapBlock : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets, link = args.relatedTarget;
		var modal = targets.closest('.modal');

		// Need to refresh the map when the modal shows again, otherwise when opening the 2nd modal map,
		// the 1st one will look screwed
		// var map = block.find('.pop-map');
		// modal.on('shown.bs.modal', function(e) {

		// 	pop.Map.refresh(pageSection, block, map);
		// });

		// Do already execute it, the first time, since this will be executed when creating the modal inside the MODALS pageSection
		that.execModalMap(domain, modal, link);
	},

	//-------------------------------------------------
	// PROTECTED functions
	//-------------------------------------------------

	execModalMap : function(domain, modals, link) {

		var that = this;

		// Make sure the link we got is the original one, and not the intercepted one
		// This is important to later on retrieve the corresponding block and pageSection,
		// under which are stored the markerIds
		link = pop.Manager.getOriginalLink(link);

		var block = pop.Manager.getBlock(link);
		var pageSection = pop.Manager.getPageSection(block);
		var mapDiv = modals.find('.pop-map');

		// Add the data-marker-ids and the header to the map div
		mapDiv.data('markers-pagesection', pageSection);
		mapDiv.data('markers-block', block);
		mapDiv.data('markers-removecurrent', true);
		var markerIds = pop.Map.getMarkerIdsFromLinkURLs(link);
		// mapDiv.data('marker-ids', markerIds);
		mapDiv.data('marker-ids-'+removeScheme(domain), markerIds);
		mapDiv.data('domains', [domain]);
	},

	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapMapCollection, ['modalMap', 'modalMapBlock']);