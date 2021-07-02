"use strict";
(function($){
window.pop.WaypointsFetchMore = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	waypointsFetchMore : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Fetch more
		jQuery(document).ready( function($) {	

			var waypoints = [];
			var contentLoaded = pop.Manager.isContentLoaded(pageSection, block);

			// Important: do this only after the page finished loading, so that the waypoint doesn't trigger
			// before the JS scripts finished running (it creates issue with overriding topLevelParams)
			targets.each(function() {

				var target = $(this);
				var opts = pop.Waypoints.getOptions(pageSection, target);

				// enabled attr: fetch data only if the Content has been initialized
				// Eg when not: Lazy Blocks / Aggregators (for these, only trigger waypoint after data is first initialized)
				var waypoint = new Waypoint({
					element: target,
					handler: function(direction) {
						var waypoint = this;
						var target = this.element;
						if (direction == 'down') {

							// Somehow it also triggers waypoint from other tabPanes, so make sure that the waypoint target is really visible
							if (pop.Manager.isHidden(target)) {
								return;
							}

							// Comment Leo 02/10/2015: It's important to trigger the click of the button instead of executing fetchBlock directly,
							// because there are actions associated to the button that need to be triggered. Eg: 'saveLastClicked'
							target.trigger('click');
						}
					}, 
					context: opts.context,
					enabled: contentLoaded,
					offset: 'bottom-in-view'
				});				
				pop.Waypoints.destroy(pageSection, block, waypoint);

				waypoints.push(waypoint);
			});

			// Refresh the waypoints when it comes back from fetching content
			// Keep it at the top, so that it executes also when pop.Manager.isContentLoaded(block) is false
			// and inits lazy
			block.on('fetchCompleted', function(e) {
				
				var block = $(this);

				// Update waypoint
				if (pop.Manager.stopFetchingBlock(pageSection, block)) {

					// If stop fetching no need to use the waypoint anymore. Re-enable it only when filtering,
					// since that's the only way to re-fill the content
					$.each(waypoints, function(index, waypoint) {

						if (waypoint.enabled) {
							
							waypoint.disable();
							pop.Waypoints.reEnable(pageSection, block, waypoint);
						}
					});
				}
				else {

					$.each(waypoints, function(index, waypoint) {

						// This is needed because when firstly !isContentLoaded, it needs to re-enable the waypoint when it first loads the content
						if (!waypoint.enabled) {
							waypoint.enable();
						}

						// Re-enable the waypoint to load again
						waypoint.context.refresh();
					});
				}
			});			
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.WaypointsFetchMore, ['waypointsFetchMore']);
