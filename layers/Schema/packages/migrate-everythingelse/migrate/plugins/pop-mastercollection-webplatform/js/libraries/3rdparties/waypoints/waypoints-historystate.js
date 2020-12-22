"use strict";
(function($){
window.pop.WaypointsHistoryState = {

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
				var opts = pop.Waypoints.getOptions(pageSection, target);

				var waypointUp = new Waypoint({
					element: target,
					handler: function(direction) {

						// var waypoint = $('#'+this.element.id);
						var waypoint = this;
						var target = this.element;
						if (pop.Manager.isHidden(target)) {
							return;
						}
						if (direction == 'up') {
							
							pop.Manager.historyReplaceState($(this));
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
						if (pop.Manager.isHidden(target)) {
							return;
						}
						if (direction == 'down') {
							
							// Also track with Google Analytics
							pop.Manager.historyReplaceState($(this));
						}
					}, 
					context: opts.context
				});

				pop.Waypoints.destroy(pageSection, block, waypointUp);
				pop.Waypoints.destroy(pageSection, block, waypointDown);
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
				var opts = pop.Waypoints.getOptions(pageSection, target);

				var waypoint = new Waypoint({
					element: target,
					handler: function(direction) {

						// var waypoint = $('#'+this.element.id);
						var waypoint = this;
						var target = this.element;
						if (pop.Manager.isHidden(target)) {
							return;
						}
						if (direction == 'up') {
							
							pop.Manager.historyReplaceState(target);
						}
					}, 
					context: opts.context
				});				
				pop.Waypoints.destroy(pageSection, block, waypoint);
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.WaypointsHistoryState, ['waypointsHistoryStateOriginal', 'waypointsHistoryStateNew']);
