"use strict";
(function($){
window.pop.WaypointsToggleClass = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	waypointsToggleClass : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		jQuery(document).ready( function($) {	

			targets.each(function() {

				var target = $(this);
				var recipient = $(target.data('target'));
				var classs = target.data('class');
				if (recipient.length && classs) {

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
								recipient.addClass(classs);
							}
							else if (direction == 'up') {
								recipient.removeClass(classs);
							}
						}, 
						context: opts.context,
						offset: opts.context ? 0 : function() {

							// Comment 30/01/2017: since removing perfectScrollbar from the mainPageSection, and switching to body, we can't use the context 'pop-waypoints-context' anymore
							// Then, we gotta add an offset for the main pageSection: it has an offset with regards to the body (eg: top => 45px, top+pagetabs => 80px),
							// Needed for the Projects Map waypointsTeater
							return pop.Waypoints.getOffset(this.element);
						}
					});		
					pop.Waypoints.destroy(pageSection, block, waypoint);
				}
			});			
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.WaypointsToggleClass, ['waypointsToggleClass']);
