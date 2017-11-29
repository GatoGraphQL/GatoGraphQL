"use strict";
(function($){
window.popMapRuntime = {
		
	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	// This function is invoked from wp-content/plugins/events-manager-popprocessors/js/templates/maps/em-map-script-drawmarkers.tmpl
	drawMarkers : function(domain, pageSection, block, mapDiv) {
	
		var that = this;

		if (popManager.jsInitialized(block)) {
			that.execDrawMarkers(domain, pageSection, block, mapDiv);
		}
		else {
			block.one('initialize', function() {
				that.execDrawMarkers(domain, pageSection, block, mapDiv);
			});
		}
	},
	execDrawMarkers : function(domain, pageSection, block, mapDiv) {
	
		var that = this;
		var mempage = popMapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);

		//Add the data-marker-ids to the pop-map div
		// mapDiv.data('marker-ids', mempage.marker_ids);
		mapDiv.data('marker-ids-'+removeScheme(domain), mempage.marker_ids);
	},

	resetMarkerIds : function(pageSection, block) {
	
		var that = this;		
		if (popManager.jsInitialized(block)) {
			that.execResetMarkerIds(pageSection, block);
		}
		else {
			block.one('initialize', function() {
				that.execResetMarkerIds(pageSection, block);
			});
		}
	},
	execResetMarkerIds : function(pageSection, block) {
	
		var that = this;		
		var mempage = popMapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);
		mempage.marker_ids = [];
	},

	setMarkerData : function(pageSection, block, title, content) {
	
		var that = this;

		if (popManager.jsInitialized(block)) {
			that.execSetMarkerData(pageSection, block, title, content);
		}
		else {
			block.one('initialize', function() {
				that.execSetMarkerData(pageSection, block, title, content);
			});
		}
	},
	execSetMarkerData : function(pageSection, block, title, content) {
	
		var that = this;
		// that.initMarkerDataVars(pssId, bsId);
		var mempage = popMapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);

		// If already set, then do nothing. This is so that we can customize the infoWindow content:
		// If possible we set customized content, eg: a Project or Event name/pic. If no customization
		// was set, then the default case will be the location title/address
		if (!mempage.title) {
			mempage.title = title;
		}
		if (!mempage.content) {
			mempage.content = content;
		}
	},

	initMarker : function(pageSection, block, locationId, lat, lng, defaultTitle, defaultContent) {
	
		var that = this;
		
		if (popManager.jsInitialized(block)) {
			that.execInitMarker(pageSection, block, locationId, lat, lng, defaultTitle, defaultContent);
		}
		else {
			block.one('initialize', function() {
				that.execInitMarker(pageSection, block, locationId, lat, lng, defaultTitle, defaultContent);
			});
		}
	},
	execInitMarker : function(pageSection, block, locationId, lat, lng, defaultTitle, defaultContent) {
	
		var that = this;
		var mempage = popMapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);

		// defaultTitle/Content: because these are the location attributes, but before setting them
		// we had the change to set customized attributes, eg: Project or Event or User
		that.setMarkerData(pageSection, block, defaultTitle, defaultContent);

		var title = mempage.title;
		var content = mempage.content;
		var marker_data = {
			coordinates : {
				lat : lat,
				lng : lng
			},
			title : title,
			infoWindow : {
				header: '<strong>'+title+'</strong>',
				content: content
			},
			// icon : that.icon
			icon : 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
		};

		mempage.marker_ids.push(locationId);
		popMapInitMarker.initMarker(pageSection, block, locationId, marker_data);
		
		// Once initialized, clear the marker atts
		mempage.content = '';
		mempage.title = '';
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popMapRuntime, []);
