"use strict";
(function($){
window.pop.MapRuntime = {
		
	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	// This function is invoked from wp-content/plugins/events-manager-popprocessors/js/templates/maps/em-map-script-drawmarkers.tmpl
	drawMarkers : function(domain, pageSection, block, mapDiv) {
	
		var that = this;

		if (pop.Manager.jsInitialized(block)) {
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
		var mempage = pop.MapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);

		//Add the data-marker-ids to the pop-map div
		// If there were already other marker ids, then add them
		var key = 'marker-ids-'+removeScheme(domain);
		// var existing = mapDiv.data(key) || [];
		// var markerIds = existing.concat(mempage.marker_ids[domain]);
		// mapDiv.data(key, markerIds);
		mapDiv.data(key, mempage.marker_ids[domain]);

		var domains = mapDiv.data('domains') || [];
		domains.push(domain);
		mapDiv.data('domains', domains);

		// // No need for the marker_ids in the mempage anymore
		// mempage.marker_ids[domain] = [];
	},

	resetMarkerIds : function(domain, pageSection, block) {
	
		var that = this;		
		if (pop.Manager.jsInitialized(block)) {
			that.execResetMarkerIds(domain, pageSection, block);
		}
		else {
			block.one('initialize', function() {
				that.execResetMarkerIds(domain, pageSection, block);
			});
		}
	},
	execResetMarkerIds : function(domain, pageSection, block) {
	
		var that = this;		
		var mempage = pop.MapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);
		mempage.marker_ids[domain] = [];
	},

	setMarkerData : function(pageSection, block, title, content) {
	
		var that = this;

		if (pop.Manager.jsInitialized(block)) {
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
		var mempage = pop.MapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);

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

	initMarker : function(domain, pageSection, block, locationId, lat, lng, defaultTitle, defaultContent) {
	
		var that = this;
		
		if (pop.Manager.jsInitialized(block)) {
			that.execInitMarker(domain, pageSection, block, locationId, lat, lng, defaultTitle, defaultContent);
		}
		else {
			block.one('initialize', function() {
				that.execInitMarker(domain, pageSection, block, locationId, lat, lng, defaultTitle, defaultContent);
			});
		}
	},
	execInitMarker : function(domain, pageSection, block, locationId, lat, lng, defaultTitle, defaultContent) {
	
		var that = this;
		var mempage = pop.MapRuntimeMemory.getRuntimeMemoryPage(pageSection, block);

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

		// Comment Leo 09/12/2017: place the marker Id under the domain (mempage.marker_ids[domain]) to avoid 2 requests from 2 different domains to arrive simultaneously,
		// and the 2nd one modifiying the marker_ids for the 1st one. Placing under the domain, that won't happen
		var markerId = that.getMarkerId(domain, locationId);
		mempage.marker_ids[domain] = mempage.marker_ids[domain] || [];
		mempage.marker_ids[domain].push(markerId);
		pop.MapInitMarker.initMarker(pageSection, block, markerId, marker_data);
		
		// Once initialized, clear the marker atts
		mempage.content = '';
		mempage.title = '';
	},

	getMarkerId : function(domain, locationId) {
	
		var that = this;
		return domain+'-'+locationId;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.MapRuntime, []);
