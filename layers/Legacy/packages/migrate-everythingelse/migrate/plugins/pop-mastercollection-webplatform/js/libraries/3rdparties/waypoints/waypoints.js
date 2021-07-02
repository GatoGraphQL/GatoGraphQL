"use strict";
(function($){
window.pop.Waypoints = {

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	reEnable : function(pageSection, block, waypoint) {
	
		var that = this;
		
		block.one('beforeReload', function() {
			block.one('fetchCompleted', function() {
				
				// After filtering, re-enable waypoints
				if (!pop.Manager.stopFetchingBlock(pageSection, block)) {
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
		var pageSection = pop.Manager.getPageSection(target);
		return pageSection.offset().top;
	},

	destroy : function(pageSection, block, waypoint) {

		var that = this;
		
		var pageSectionPage = pop.Manager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {

			waypoint.destroy();
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.Waypoints, []);
