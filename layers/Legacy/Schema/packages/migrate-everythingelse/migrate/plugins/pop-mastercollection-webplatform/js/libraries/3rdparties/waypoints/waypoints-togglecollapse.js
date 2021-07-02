"use strict";
(function($){
window.pop.WaypointsToggleCollapse = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	waypointsToggleCollapse : function(args) {
	
		var that = this;
		var targets = args.targets;

		jQuery(document).ready( function($) {	

			// Targets: the collapse elements. Each one of them will have attr "data-collapse-target" indicating what other element is the waypoint
			targets.each(function() {

				var collapse = $(this);
				var target = $(collapse.data('collapse-target'));
				if (target.length) {
					
					var block = pop.Manager.getBlock(target);
					var pageSection = pop.Manager.getBlock(block);
					that.execWaypointsToggleCollapse(pageSection, block, target, collapse);
				}
			});			
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	execWaypointsToggleCollapse : function(pageSection, block, target, collapse) {
	
		var that = this;
		var opts = pop.Waypoints.getOptions(pageSection, target);

		var waypoint = new Waypoint({
			element: target,
			handler: function(direction) {

				var waypoint = this;
				var target = this.element;
				if (pop.Manager.isHidden(target)) {
					return;
				}
				if (direction == 'down') {
					collapse.collapse('show');
				}
				else if (direction == 'up') {
					collapse.collapse('hide');
				}
			}, 
			context: opts.context
		});		
		pop.Waypoints.destroy(pageSection, block, waypoint);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.WaypointsToggleCollapse, ['waypointsToggleCollapse']);
