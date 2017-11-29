"use strict";
(function($){
window.popWaypointsHistoryState = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	waypointsHistoryStateNew : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;
		
		jQuery(document).ready( function($) {

			// newstate: trigger when entering the item from scrolling down when the top is in view, or scrolling up when the bottom is in view
			targets.each(function() {

				var target = $(this);
				var opts = popWaypoints.getOptions(pageSection, target);

				var waypointUp = new Waypoint({
					element: target,
					handler: function(direction) {

						// var waypoint = $('#'+this.element.id);
						var waypoint = this;
						var target = this.element;
						if (popManager.isHidden(target)) {
							return;
						}
						if (direction == 'up') {
							
							popManager.historyReplaceState($(this));
						}
					}, 
					context: opts.context,
					offset: 'bottom-in-view'
				});
				var waypointDown = new Waypoint({
					element: target,
					handler: function(direction) {

						// var waypoint = $('#'+this.element.id);
						var waypoint = this;
						var target = this.element;
						if (popManager.isHidden(target)) {
							return;
						}
						if (direction == 'down') {
							
							// Also track with Google Analytics
							popManager.historyReplaceState($(this));
						}
					}, 
					context: opts.context
				});

				popWaypoints.destroy(pageSection, block, waypointUp);
				popWaypoints.destroy(pageSection, block, waypointDown);
			});
		});
	},

	waypointsHistoryStateOriginal : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;
		
		jQuery(document).ready( function($) {

			// original state: trigger when scrolling up when the top is in view, to re-set the url to the original one
			targets.each(function() {

				var target = $(this);
				var opts = popWaypoints.getOptions(pageSection, target);

				var waypoint = new Waypoint({
					element: target,
					handler: function(direction) {

						// var waypoint = $('#'+this.element.id);
						var waypoint = this;
						var target = this.element;
						if (popManager.isHidden(target)) {
							return;
						}
						if (direction == 'up') {
							
							popManager.historyReplaceState(target);
						}
					}, 
					context: opts.context
				});				
				popWaypoints.destroy(pageSection, block, waypoint);
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popWaypointsHistoryState, ['waypointsHistoryStateOriginal', 'waypointsHistoryStateNew']);
