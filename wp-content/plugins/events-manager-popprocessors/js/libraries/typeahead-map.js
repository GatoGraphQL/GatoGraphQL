(function($){
popTypeaheadMap = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	typeaheadMap : function(args) {
	
		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		// var typeaheadMap = elem.find('.pop-typeaheadmap');
		targets.each(function() {

			var typeaheadMap = $(this);
			var typeahead = typeaheadMap.find('.pop-typeahead');
			var map = typeaheadMap.find('.pop-map');
			var template = typeaheadMap.data('addmarker-template');
			// var block = popManager.getBlock(typeaheadMap);

			typeahead.on('selected', function(e, datum) {

				if (!popMap.hasMarker(pageSection, block, datum.id)) {

					// Extend the targetContext using datum as the itemObject
					var targetConfiguration = popManager.getTargetConfiguration(domain, pageSection, block, template);
					$.extend(targetConfiguration, {itemObject: datum});

					// Add it to DOM => Execute it => the markerData will be added to popMap.markers
					popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
					// var domain = block.data('domain') || getDomain(block.data('toplevel-url'));
					var html = popManager.getTemplateHtml(domain, pageSection, block, template);					
					popManager.mergeHtml(html, typeaheadMap);
					popManager.runJSMethods(domain, pageSection, /*pageSectionPage, */block, template, 'last');
					popJSRuntimeManager.deleteBlockLastSessionIds(domain, pageSection, block, template);
				}

				// Add the newly added marker to the map
				// map.data('marker-ids', [datum.id]);
				map.data('marker-ids-'+removeScheme(domain), [datum.id]);
				popMap.addMarkers(domain, pageSection, block, map);
			});

			typeahead.on('close', function(e, alert, markerId) {

				// Remove the marker from the map
				popMap.removeMarker(pageSection, block, map, markerId);
			});
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popTypeaheadMap, ['typeaheadMap']);