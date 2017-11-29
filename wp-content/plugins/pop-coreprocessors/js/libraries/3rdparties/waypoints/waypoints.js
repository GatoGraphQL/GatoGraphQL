"use strict";
(function($){
window.popWaypoints = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// Comment Leo: Uncomment here: Needed for MainRelated Info
	// waypointsCollapse : function(args) {
	
	// 	var that = this;
	// 	var pageSection = args.pageSection, block = args.block, targets = args.targets;

	// 	// var waypoint = block.find('.waypoint[data-toggle="offcanvas-collapse"]').addBack('.waypoint[data-toggle="offcanvas-collapse"]');
	// 	jQuery(document).ready( function($) {	

	// 		targets.each(function() {

	// 			var waypoint = $(this);
	// 			var target = waypoint.data('target');
	// 			var parent = waypoint.data('parent');
	// 			var opts = that.getOptions(waypoint);

	// 			// Data toggle: offcanvas collapse
	// 			waypoint.waypoint(function(direction) {

	// 				//var waypoint = $('#'+this.element.id);
	// 				var waypoint = this.element;
	// 				if (popManager.isHidden(waypoint)) {
	// 					return;
	// 				}
	// 				if (direction == 'up') {
						
	// 					// Close offcanvas collapse
	// 					$(target).collapse('hide');
	// 				}
	// 				else if (direction == 'down') {

	// 					// Reset the bubbling
	// 					gdBootstrap.resetBubbling();
						
	// 					// Open offcanvas collapse
	// 					$(parent).find('.collapse.in').collapse('hide');
	// 					$(target).collapse('show');
	// 				}
	// 			}, opts);
	// 		});
	// 	});
	// },

	// waypointsShowHideTopNav : function(args) {
	
	// 	var that = this;
	// 	var pageSection = args.pageSection, block = args.block, targets = args.targets;

	// 	// Show/hide topnav
	// 	// waypoint = block.find('.waypoint.template-showhidetopnav').addBack('.waypoint.template-showhidetopnav');
	// 	jQuery(document).ready( function($) {	

	// 		targets.each(function() {

	// 			var waypoint = $(this);
	// 			var opts = that.getOptions(waypoint);

	// 			waypoint.waypoint(function(direction) {
			
	//				// var waypoint = $('#'+this.element.id);
	// 				var waypoint = this.element;
	// 				if (popManager.isHidden(waypoint)) {
	// 					return;
	// 				}
	// 				if (direction == 'up') {
						
	// 					// Disable the show/hide topnav, keep it always open
	// 					popCustomPageSectionManager.disableShowHideTopNav();
	// 				}
	// 				else if (direction == 'down') {

	// 					// When entering the FullView content feed, enable the show/hide topnav
	// 					popCustomPageSectionManager.enableShowHideTopNav();
	// 				}
	// 			}, opts);
	// 		});
	// 	});
	// },

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	reEnable : function(pageSection, block, waypoint) {
	
		var that = this;
		
		block.one('beforeReload', function() {
			block.one('fetchCompleted', function() {
				
				// After filtering, re-enable waypoints
				// var blockQueryState = popManager.getBlockQueryState(pageSection, block);
				// if (!blockQueryState[M.URLPARAM_STOPFETCHING]) {
				if (!popManager.stopFetchingBlock(pageSection, block)) {
					waypoint.enable();
				}
				else {
					that.reEnable(pageSection, block, waypoint);
				}
			});
		});
	},

	getOptions : function(pageSection, waypoint) {
	
		var that = this;
		var options = {};
		
		// Comment Leo 10/04/2014: pop-viewport for perfect-scrollbar and for waypoints are not the same!
		// For waypoints, it's actually the pageSection, so then assign it directly
		// But only if it has class "pop-waypoints-context", otherwise don't (eg: no need for print)
		var context = pageSection.filter('.pop-waypoints-context');
		if (context.length) {
			options.context = context;
		}
		else {
			// Check if any ancestor of the waypoint is the context
			context = waypoint.closest('.pop-waypoints-context');
			if (context.length) {
				options.context = context;
			}
		}

		return options;
	},

	getOffset : function(target) {

		// Comment 30/01/2017: since removing perfectScrollbar from the mainPageSection, and switching to body, we can't use the context 'pop-waypoints-context' anymore
		// Then, we gotta add an offset for the main pageSection: it has an offset with regards to the body (eg: top => 45px, top+pagetabs => 80px),
		// Needed for the Projects Map waypointsTeater
		var pageSection = popManager.getPageSection(target);
		return pageSection.offset().top;
	},

	destroy : function(pageSection, block, waypoint) {

		var that = this;
		
		var pageSectionPage = popManager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {

			waypoint.destroy();
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popWaypoints, []);
