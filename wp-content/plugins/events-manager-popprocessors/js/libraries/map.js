"use strict";
(function($){
window.popMap = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	initBlockRuntimeMemory : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, mempage = args.runtimeMempage;

		// Initialize with this library key
		mempage.map = {};

		// Reset values
		that.resetBlockRuntimeMemory(pageSection, block);
	},

	map : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		block.one('rerender', function(action) {
			
			that.destroy(pageSection, block, targets);
		});

		// Do it after the document is fully loaded, so that Google Maps doesn't make the site load more slowly
		$(document).ready(function($) {
			targets.each(function() {
				
				var map = $(this);
				that.triggerShowMap(domain, pageSection, block, map);
			});
		});
	},

	mapModal : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		targets.on('shown.bs.modal', function() {

			var modal = $(this);
			$.each(modal.find('.pop-map'), function() {

				var map = $(this);
				var block = popManager.getBlock(map);
				if (popManager.jsInitialized(block)) {
					that.triggerShowMap(domain, pageSection, block, map);
				}
			});
		});
	},

	mapStandalone : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Refresh when website goes into theater mode (needed at least for the standaloneMap)
		popPageSectionManager.getGroup(pageSection).on('on.bs.pagesection:theater off.bs.pagesection:theater', function(e) {

			targets.each(function() {
				
				var map = $(this);
				if (!popManager.isHidden(map)) {

					that.refresh(pageSection, block, map);
				}
			});
		});

		// Hook the standalone map to the block activity
		block.on('fetchDomainCompleted', function(e, status, domain) {
			
			// Set the Block URL for popJSRuntimeManager.addTemplateId to know under what URL to place the session-ids
			popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);

			targets.each(function() {

				var map = $(this);
				that.triggerAddMarkers(domain, pageSection, block, map, status);
			});
		});

		targets.each(function() {

			var map = $(this);
			that.hoverBlockElems(pageSection, block, map);
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	getRuntimeMemoryPage : function(pageSection, targetOrId) {

		var that = this;
		return popManager.getRuntimeMemoryPage(pageSection, targetOrId).map;
	},

	resetBlockRuntimeMemory : function(pageSection, targetOrId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, targetOrId);
		var empty = that.getEmtpyBlockRuntimeMemory();

		$.extend(mempage, empty);
	},

	getEmtpyBlockRuntimeMemory : function() {

		return {
			markers: {},
			markersPos: {},
			markersOpen: {},
			maps: {},
		};
	},

	destroy : function(pageSection, block, targets) {
	
		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		targets.each(function() {
			var map = $(this);
			var mapId = map.attr('id');
			delete mempage.maps[mapId];
		});
	},

	triggerShowMap : function(domain, pageSection, block, map) {

		var that = this;

		// Make sure the block is not hidden, otherwise GoogleMaps fails loading
		if (!popManager.isHidden(map)) {
		
			that.execTriggerShowMap(domain, pageSection, block, map);
		}
		else {

			that.pendingTriggerMap('showmap', domain, pageSection, block, map);
		}
	},

	triggerAddMarkers : function(domain, pageSection, block, map, status) {

		var that = this;

		// Make sure the block is not hidden, otherwise GoogleMaps fails loading
		if (!popManager.isHidden(map)) {
		
			// Comment Leo 26/07/2017: since adding domains, we can't just ask for status.reload to know if to remove the current markers,
			// since that flag will be true for each domain that we are fetching data from, after doing a reload,
			// so they will keep removing the previous' domains' markers.
			// var removeCurrent = status.reload;
			var removeCurrent = status.reload && status.isFirst;
			that.addMarkers(domain, pageSection, block, map, removeCurrent);
		}
		else {

			that.pendingTriggerMap('addmarkers', domain, pageSection, block, map, status);
		}
	},

	triggerFunction : function(functionName, domain, pageSection, block, map, status) {

		var that = this;
					
		// Call again this same function, to make sure that the map is still not hidden by 1 of the other conditions (eg: pageSectionTab not active AND map inside a collapse)
		// that.execTriggerShowMap(pageSection, block, map);
		if (functionName == 'showmap') {
			that.triggerShowMap(domain, pageSection, block, map);
		}
		else if (functionName == 'addmarkers') {
			that.triggerAddMarkers(domain, pageSection, block, map, status);
		}
	},

	pendingTriggerMap : function(functionName, domain, pageSection, block, map, status) {

		var that = this;

		var pageSectionPage = popManager.getPageSectionPage(block);

		// If in particular it is hidden because the pageSection is not visible from the pageSectionGroup, 
		// then initialize it when the pageSection opens
		if (popPageSectionManager.execIsHidden(pageSection)) {

			popPageSectionManager.getGroup(pageSection).one('on.bs.pagesection-group:pagesection-'+pageSection.attr('id')+':opened', function() {

				// Make sure the block still exists! (It could've been destroyed by the time of the trigger)
				block = $('#'+block.attr('id'));
				if (block.length) {
					
					that.triggerFunction(functionName, domain, pageSection, block, map, status);
				}
			});
		}

		// If not visible because in a collapsed bootstrap element, wait until it opens
		// This happens with the Locations Map in Create Individual Profile (initially minimized)
		else if (map.parents('.collapse').not('.in').length) {
			
			var collapse = map.parents('.collapse').not('.in');
			collapse.one('shown.bs.collapse', function() {

				that.triggerFunction(functionName, domain, pageSection, block, map, status);
			});
		}

		// If when opening the page, the pageSectionPage is not active (eg: alt+click on the link), then the map will not show up
		else if (pageSectionPage.hasClass('tab-pane') && !popManager.isActive(pageSectionPage)) {

			// if (pageSectionPage.hasClass('tab-pane')) {

			pageSectionPage.one('shown.bs.tabpane', function() {
				
				that.triggerFunction(functionName, domain, pageSection, block, map, status);
			});
			// }
		}
	},

	execTriggerShowMap : function(domain, pageSection, block, map) {

		var that = this;

		// targets.each(function() {
			
		// 	var map = $(this);
		// 	that.addMarkers(pageSection, block, map, map.data('markers-removecurrent'));
		// });

		that.addMarkers(domain, pageSection, block, map, map.data('markers-removecurrent'));

		// Dispatch a window resize so that the Calendar / Google map gets updated
		windowResize();
	},

	refresh : function(pageSection, block, map) {

		var that = this;
		var gMap = that.getGMap(pageSection, block, map);
		if (!gMap) return;
		gMap.refresh();
	},

	hoverBlockElems : function(pageSection, block, map) {

		var that = this;
		
		// Close all (other open) infowindows
		block.on('mouseenter', '.pop-openmapmarkers', function() {

			var elem = $(this), links;

			// If the element creates a popover, then the links will be inside the popover, which is 
			// not placed under the element but under .pop-viewport, so then retrieve the links from the popover instead
			if (elem.hasClass('make-popover')) {
				
				var popover_id = elem.attr('aria-describedby');
				if (popover_id) {
					links = $('#'+popover_id).find('a.pop-modalmap-link');
				}
			}
			else {
				
				// By default, the links are found under the element
				links = elem.find('a.pop-modalmap-link');
			}

			that.openMapMarkers(pageSection, block, map, links);
		});
	},
	
	openMapMarkers : function(pageSection, block, map, links) {

		var that = this;

		// Make sure the map is visible. If it is not, do nothing or it will screw it
		if (popManager.isHidden(map)) return;
		
		that.closeMarkers(pageSection, block, map);

		// Open all the markers for the link, if any
		if (links.length) {

			that.openMarkers(pageSection, block, map, links);
		}
	},

	closeMarkers : function(pageSection, block, map) {

		var that = this;

		var gMap = that.getGMap(pageSection, block, map);
		if (!gMap) return;
		var markersInfo = that.getMarkersInfo(pageSection, block, map);
		
		// Close all other open infoWindows
		gMap.hideInfoWindows();

		// Restore the icon for all open markers
		$.each(markersInfo.markersOpen, function(index, markerId) {

			var markerPos = markersInfo.markersPos[markerId];
			if (gMap.markers[markerPos]) {
				gMap.markers[markerPos].setIcon('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
			}
		});
	},

	getMarkerIdsFromLinkURLs : function(links) {

		var that = this;

		var markerIds = [];
		links.each(function() {
			var link = $(this);
			// Make sure we got the original link, and not an interceptor
			link = popManager.getOriginalLink(link);

			var url = link.attr('href');
			var paramIds = getParam(M.LOCATIONSID_FIELDNAME, url);
			if (paramIds) {

				$.each(paramIds, function(index, value) {
					markerIds.push(value);
				})
			}
		});

		return markerIds;
	},

	openMarkers : function(pageSection, block, map, links) {

		var that = this;

		var markerIds = that.getMarkerIdsFromLinkURLs(links);
		if (!markerIds.length) return;
		
		// Remove potential duplicates
		markerIds = markerIds.filter(function (item, pos) {return markerIds.indexOf(item) == pos});

		var gMap = that.getGMap(pageSection, block, map);
		if (!gMap) return;

		// Allow to inject markers from other blocks, useful for loading just one map: LocationsMap Modal Block
		var markersInfo = that.getMarkersInfo(pageSection, block, map);

		// Change the icon to a different one
		$.each(markerIds, function(index, markerId) {

			var markerPos = markersInfo.markersPos[markerId];

			// Comment Leo 10/09/2015: sometimes this produces null on gMap.markers[markerPos] (I don't know why it happens), so just in case check for this condition
			// so it doesn't produce a js error (eg where it has happened: https://www.mesym.com/u/mesym/?tab=members&format=map)
			if (gMap.markers[markerPos]) {
				gMap.markers[markerPos].setIcon('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
			}
		});

		// If only 1 location, then "click" on it
		if (markerIds.length == 1) {

			var markerPos = markersInfo.markersPos[markerIds[0]];
			// Comment Leo 10/09/2015: related to the comment above, ask for this condition just in case, however there should be no need, this should always be true
			if (gMap.markers[markerPos]) {
				// If there is no connection to Internet, object 'google' will not be there
				if (typeof google != 'undefined') {
					google.maps.event.trigger( gMap.markers[markerPos], 'click' );
				}
			}
		}
		else {
			// Focus on the markers
			gMap.fitZoom();
		}

		// Assign the new openMarkers
		// var markersOpen = $.extend({}, markerIds);
		that.setMarkersOpen(pageSection, block, map, markerIds, true);
	},

	// Function used within map-script-markers.tmpl, that's why it uses pssId/bsId as arguments, instead of the objects
	// (objects not created/inserted into the DOM yet)
	initMarker : function(pageSection, block, markerId, markerData) {
	
		var that = this;

		var mempage = that.getRuntimeMemoryPage(pageSection, block);

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

	hasMarker : function(pageSection, block, markerId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		return mempage.markers[markerId];
	},

	getGMap : function(pageSection, block, map) {

		var that = this;

		if (typeof google == 'undefined' || typeof GMaps == 'undefined') return;

		// var pssId = popManager.getSettingsId(pageSection);
		// var bsId = popManager.getSettingsId(block);

		// that.initMapsVars(pssId, bsId);

		var mempage = that.getRuntimeMemoryPage(pageSection, block);
		var mapId = map.attr('id');
		// var gMap = that.maps[pssId][bsId][mapId];
		var gMap = mempage.maps[mapId];

		// If it doesn't exist, create it
		if (!gMap) {
			gMap = mempage.maps[mapId] = new GMaps({
				div: '#'+mapId,
				lat: M.LOCATIONSMAP_LAT,
				lng: M.LOCATIONSMAP_LNG,
				zoom: parseInt(M.LOCATIONSMAP_ZOOM),
			});
		}

		return gMap;
	},

	getMarkersInfo : function(pageSection, block, map) {

		var that = this;

		var markersPageSection = map.data('markers-pagesection') || pageSection;
		var markersBlock = map.data('markers-block') || block;
		
		// Comment Leo 16/10/2017: there is a bug:
		// clicking on location link inside the Event preview gives JS error, because the link from where we are clicking (the original link), which we need in map-collection.js, execModalMap : function(domain, modals, link), line `link = popManager.getOriginalLink(link);`, doesn't exist anymore, since it's also in a modal window which is closed when opening the new location modal window...
		// Then getRuntimeMemoryPage will throw an Exception. If this happens, then just do nothing, but don't let it explode
		var mempage = that.getEmtpyBlockRuntimeMemory();
		try {
			mempage = that.getRuntimeMemoryPage(markersPageSection, markersBlock);
		}
		catch(err) {
			// Do nothing
			console.log('Error: '+err.message);
		}
		var mapId = map.attr('id');
		// Markers Info
		return {
			markers: mempage.markers || {},
			markersPos: mempage.markersPos[mapId] || {},
			markersOpen: mempage.markersOpen[mapId] || []
		};
	},
	setMarkersOpen : function(pageSection, block, map, markersOpen, removeCurrent) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);
		var mapId = map.attr('id');

		if (removeCurrent || !mempage.markersOpen[mapId]) {
			mempage.markersOpen[mapId] = [];
		}
		if (markersOpen) {
			mempage.markersOpen[mapId] = mempage.markersOpen[mapId].concat(markersOpen);
		}
	},
	setMarkersPos : function(pageSection, block, map, markersPos, removeCurrent) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);
		var mapId = map.attr('id');

		if (removeCurrent || !mempage.markersPos[mapId]) {
			mempage.markersPos[mapId] = {};
		}
		if (markersPos) {
			$.extend(mempage.markersPos[mapId], markersPos);
		}
	},

	removeMarker : function(pageSection, block, map, markerId) {

		var that = this;

		var gMap = that.getGMap(pageSection, block, map);
		if (!gMap) return;
		var markersInfo = that.getMarkersInfo(pageSection, block, map);

		// No need to remove the marker from the map, or update markersPos, because .setMap(null) simply hides the marker, not delete it
		var markerPos = markersInfo.markersPos[markerId];

		if (gMap.markers[markerPos]) {
			gMap.markers[markerPos].setMap(null);
		}

		// Zoom
		that.zoom(pageSection, block, map);
	},

	zoom : function(pageSection, block, map) {

		var that = this;
		var gMap = that.getGMap(pageSection, block, map);
		if (!gMap) return;

		// Zoom
		if (gMap.markers.length > 1) {

			gMap.fitZoom();
		}
		else if (gMap.markers.length == 1) {

			// var marker = map.markers[0];
			var pos = gMap.markers[0].getPosition();

			gMap.setCenter(pos.lat(), pos.lng());
			gMap.setZoom(parseInt(M.LOCATIONSMAP_1MARKER_ZOOM));
		}
	},

	addMarkers : function(domain, pageSection, block, map, removeCurrent) {

		var that = this;
		var gMap = that.getGMap(pageSection, block, map);
		if (!gMap) return;

		// Remove already existing markers?
		if (removeCurrent) {

			// Remove any open marker and their position
			gMap.removeMarkers();
			that.setMarkersOpen(pageSection, block, map, null, true);
			that.setMarkersPos(pageSection, block, map, null, true);
		}

		// var markerIds = map.data('marker-ids');
		var markerIds = map.data('marker-ids-'+removeScheme(domain));
		if (!markerIds || !markerIds.length) return;

		// Set no marker ids on the map, so that when clicking on the tab or collapse again, it doesn't add these markers again
		// (more since it will override previously added markers using update=true not present currently in data-marker-ids)
		// map.data('marker-ids', null);
		map.data('marker-ids-'+removeScheme(domain), null);
		
		// Allow to inject markers from other blocks, useful for loading just one map: LocationsMap Modal Block
		var markersInfo = that.getMarkersInfo(pageSection, block, map);
		
		var markerData, marker, markerPos;
		var markersPos = {};
		// markerIds = markerIds.toString().split(',');
		$.each(markerIds, function(index, markerId) {

			markerData = markersInfo.markers[markerId];

			// Comment Leo 16/10/2017: there is a bug:
			// clicking on location link inside the Event preview gives JS error, because the link from where we are clicking (the original link), which we need in map-collection.js, execModalMap : function(domain, modals, link), line `link = popManager.getOriginalLink(link);`, doesn't exist anymore, since it's also in a modal window which is closed when opening the new location modal window...
			// Then we will not have object will markerData. In this case, do nothing (do not let the JS explode)
			if (markerData) {

				var infoWindow = {
					content : markerData.infoWindow.header + '<br/>' + markerData.infoWindow.content
				};

				var markerOptions = {
					lat : markerData.coordinates.lat,
					lng : markerData.coordinates.lng,
					title : markerData.title,
					infoWindow: infoWindow,
					icon: markerData.icon
				};
				// If there is no connection to Internet, object 'google' will not be there
				if (typeof google != 'undefined') {
					markerOptions['animation'] = google.maps.Animation.DROP;
				}
				marker = gMap.addMarker(markerOptions);

				// Save the position of the marker in the map
				markerPos = gMap.markers.length - 1;
				markersPos[markerId] = markerPos;
			}
		});

		that.setMarkersPos(pageSection, block, map, markersPos);

		// Open the infoWindow of the added marker (if only 1)
		var onemarkerClick = map.data('open-onemarker-infowindow');
		if (markerIds.length == 1 && onemarkerClick) {

			// Close all other open infoWindows
			gMap.hideInfoWindows();
			
			// If unique, open the infoWindow for that one marker
			// If there is no connection to Internet, object 'google' will not be there
			if (typeof google != 'undefined') {
				google.maps.event.trigger( marker, 'click' );
			}
		}

		that.zoom(pageSection, block, map);
	},

	// geocode : function(pageSection, block, createLocationMap, address) {
	geocode : function(pageSection, block, map, address, latInput, lngInput) {

		var that = this;
		var gMap = that.getGMap(pageSection, block, map);
		if (!gMap) return;

		GMaps.geocode({
			address: address,
			callback: function(results, status) {
				if (status == 'OK') {
					
					gMap.removeMarkers();
					var latlng = results[0].geometry.location;
					gMap.setCenter(latlng.lat(), latlng.lng());					
					gMap.addMarker({
						lat: latlng.lat(),
						lng: latlng.lng(),
						draggable: true
					});
					gMap.setZoom(parseInt(M.LOCATIONSMAP_1MARKER_ZOOM));

					// Set the Lat / Lng to create the Location
					latInput.val(latlng.lat());
					lngInput.val(latlng.lng());

					var marker = gMap.markers[0];
					// If there is no connection to Internet, object 'google' will not be there
					if (typeof google != 'undefined') {
						google.maps.event.addListener(marker, 'dragend', function() {
							
							var latlng = marker.getPosition();
							
							// Set the Lat / Lng to create the Location
							latInput.val(latlng.lat());
							lngInput.val(latlng.lng());
						});			
					}		
				}
			}
		});
	}
};
})(jQuery);


(function($){
window.popMapRuntime = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initBlockRuntimeMemory : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, mempage = args.runtimeMempage;

		// Initialize with this library key
		mempage.mapRuntime = {};

		// Reset values
		that.resetBlockRuntimeMemory(pageSection, block);
	},
		
	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	getRuntimeMemoryPage : function(pageSection, targetOrId) {

		var that = this;
		return popManager.getRuntimeMemoryPage(pageSection, targetOrId).mapRuntime;
	},

	resetBlockRuntimeMemory : function(pageSection, targetOrId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, targetOrId);
		var empty = {

			content: '', 
			title: '', 
			marker_ids: [],
		};

		$.extend(mempage, empty);
	},

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
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

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
		var mempage = that.getRuntimeMemoryPage(pageSection, block);
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
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

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
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

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
		popMap.initMarker(pageSection, block, locationId, marker_data);
		
		// Once initialized, clear the marker atts
		mempage.content = '';
		mempage.title = '';
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popMap, ['initBlockRuntimeMemory', 'map', 'mapModal', 'mapStandalone']);
popJSLibraryManager.register(popMapRuntime, ['initBlockRuntimeMemory']);