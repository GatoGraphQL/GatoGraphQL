"use strict";
(function($){
window.pop.MapInitMarker = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// Function used within map-script-markers.tmpl, that's why it uses pssId/bsId as arguments, instead of the objects
	// (objects not created/inserted into the DOM yet)
	initMarker : function(pageSection, block, markerId, markerData) {
	
		var that = this;

		var mempage = pop.MapMemory.getRuntimeMemoryPage(pageSection, block);

		// Initialize internal vars
		// that.initMarkersVars(pssId, bsId);

		// Check if this marker already exists, if so add new information to this one (pile up all in one marker instead of having many sitting on top of each other so that we can't access the below ones):
		// 1. Same title: we already have it, don't add it
		// 2. Different title: it's a different post/user referencing the same Location => append content
		// var loadedMarkerData = that.markers[pssId][bsId][markerId];
		var loadedMarkerData = mempage.markers[markerId];
		if (loadedMarkerData) {

			// Title already added => marker already added
			if (loadedMarkerData.title.split(' | ').indexOf(markerData.title) > -1) return;

			// Different title => append content to the marker
			markerData.title = loadedMarkerData.title + ' | ' + markerData.title;
			// markerData.infoWindow.content = loadedMarkerData.infoWindow.content + '<hr/>' + markerData.infoWindow.content;
			markerData.infoWindow.content = markerData.infoWindow.content + '<hr/>' + loadedMarkerData.infoWindow.content;

			// Reset the marker to the default one
			markerData.icon = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
		}
		// that.markers[pssId][bsId][markerId] = markerData;
		mempage.markers[markerId] = markerData;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.MapInitMarker, []);
