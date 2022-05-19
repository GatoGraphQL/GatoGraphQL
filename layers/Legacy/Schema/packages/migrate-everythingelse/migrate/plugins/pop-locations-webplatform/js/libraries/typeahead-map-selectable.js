"use strict";
(function($){
window.pop.TypeaheadMapSelectable = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	selectableTypeaheadMap : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		targets.each(function() {

			var typeaheadMap = $(this);
			var typeahead = typeaheadMap.find('.pop-typeahead');
			var map = typeaheadMap.find('.pop-map');
			var moduleName = typeaheadMap.data('addmarker-component');
			var trigger = pop.TypeaheadSelectable.getTypeaheadTrigger(typeahead);

			trigger.on('dbObjectLayoutRendered', function(e, targetDomain, targetPageSection, targetBlock, target, datum) {

				var markerId = pop.MapRuntime.getMarkerId(domain, datum.id);
				if (!pop.Map.hasMarker(pageSection, block, markerId)) {

					// Add it to DOM => Execute it => the markerData will be added to pop.Map.markers
					var context = {dbObject: datum};
					pop.DynamicRender.render(domain, pageSection, block, moduleName, typeaheadMap, context);
				}

				// Add the newly added marker to the map
				map.data('marker-ids-'+removeScheme(domain), [markerId]);
				map.data('domains', [domain]);
				pop.Map.addMarkers(domain, pageSection, block, map);
			});

			typeahead.on('close', function(e, alert, locationId) {

				// Remove the marker from the map
				var markerId = pop.MapRuntime.getMarkerId(domain, locationId);
				pop.Map.removeMarker(pageSection, block, map, markerId);
			});
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.TypeaheadMapSelectable, ['selectableTypeaheadMap']);