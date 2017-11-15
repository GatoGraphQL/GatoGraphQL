"use strict";
(function($){
window.popWaypoints = {

	//-------------------------------------------------
	// INTERNAL VARIABLES
	//-------------------------------------------------

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// Comment Leo 22/02/2017: Waypoints seems to already process the windowResize already, so no need to add this
	// documentInitialized : function() {
	
	// 	var that = this;

	// 	$(window).on('resized', function() {

	// 		Waypoint.refreshAll();
	// 	});
	// },

	waypointsHistoryStateNew : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;
		
		jQuery(document).ready( function($) {

			// newstate: trigger when entering the item from scrolling down when the top is in view, or scrolling up when the bottom is in view
			targets.each(function() {

				var target = $(this);
				var opts = that.getOptions(pageSection, target);

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
							// that.executeHistoryState(pageSection, $(this));
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
							// that.executeHistoryState(pageSection, $(this), {analytics: true});
							// that.executeHistoryState(pageSection, $(this));
							popManager.historyReplaceState($(this));
						}
					}, 
					context: opts.context
				});

				that.destroy(pageSection, block, waypointUp);
				that.destroy(pageSection, block, waypointDown);
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
				var opts = that.getOptions(pageSection, target);

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
							// that.executeHistoryState(pageSection, $(this));
							popManager.historyReplaceState(target);
						}
					}, 
					context: opts.context
				});				
				that.destroy(pageSection, block, waypoint);
			});
		});
	},

	waypointsToggleClass : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		jQuery(document).ready( function($) {	

			targets.each(function() {

				var target = $(this);
				var recipient = $(target.data('target'));
				var classs = target.data('class');
				if (recipient.length && classs) {

					var opts = that.getOptions(pageSection, target);
					var waypoint = new Waypoint({
						element: target,
						handler: function(direction) {

							var waypoint = this;
							var target = this.element;
							if (popManager.isHidden(target)) {
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
							return that.getOffset(this.element);
						}
					});		
					that.destroy(pageSection, block, waypoint);
				}
			});			
		});
	},

	waypointsToggleCollapse : function(args) {
	
		var that = this;
		var targets = args.targets;

		jQuery(document).ready( function($) {	

			// Targets: the collapse elements. Each one of them will have attr "data-collapse-target" indicating what other element is the waypoint
			targets.each(function() {

				var collapse = $(this);
				var target = $(collapse.data('collapse-target'));
				if (target.length) {
					var block = popManager.getBlock(target);
					var pageSection = popManager.getBlock(block);
					that.execWaypointsToggleCollapse(pageSection, block, target, collapse);
				}
			});			
		});
	},

	waypointsTheater : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;

		// var waypoint = block.find('.waypoint[data-toggle="theater"]').addBack('.waypoint[data-toggle="theater"]');
		jQuery(document).ready( function($) {	

			targets.each(function() {

				var target = $(this);
				var opts = that.getOptions(pageSection, target);

				var waypoint = new Waypoint({
					element: target,
					handler: function(direction) {

						var waypoint = this;
						var target = this.element;
						if (popManager.isHidden(target)) {
							return;
						}
						if (direction == 'down') {
							popPageSectionManager.theater(true);
						}
						else if (direction == 'up') {
							popPageSectionManager.theater(false);
						}
					}, 
					context: opts.context,
					offset: opts.context ? 0 : function() {

						// Comment 30/01/2017: since removing perfectScrollbar from the mainPageSection, and switching to body, we can't use the context 'pop-waypoints-context' anymore
						// Then, we gotta add an offset for the main pageSection: it has an offset with regards to the body (eg: top => 45px, top+pagetabs => 80px),
						// Needed for the Projects Map waypointsTeater
						return that.getOffset(this.element);
					}
				});		
				that.destroy(pageSection, block, waypoint);
			});			
		});
	},

	waypointsFetchMore : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;

		// Fetch more
		jQuery(document).ready( function($) {	

			var waypoints = [];
			var contentLoaded = popManager.isContentLoaded(pageSection, block);

			// Important: do this only after the page finished loading, so that the waypoint doesn't trigger
			// before the JS scripts finished running (it creates issue with overriding topLevelParams)
			targets.each(function() {

				var target = $(this);
				var opts = that.getOptions(pageSection, target);

				// enabled attr: fetch data only if the Content has been initialized
				// Eg when not: Lazy Blocks / Aggregators (for these, only trigger waypoint after data is first initialized)
				var waypoint = new Waypoint({
					element: target,
					handler: function(direction) {
						var waypoint = this;
						var target = this.element;
						if (direction == 'down') {

							// Somehow it also triggers waypoint from other tabPanes, so make sure that the waypoint target is really visible
							if (popManager.isHidden(target)) {
								return;
							}

							// Comment Leo 02/10/2015: It's important to trigger the click of the button instead of executing fetchBlock directly,
							// because there are actions associated to the button that need to be triggered. Eg: 'saveLastClicked'
							target.trigger('click');
							// popManager.fetchBlock(pageSection, block, {operation: M.URLPARAM_OPERATION_APPEND});
						}
					}, 
					context: opts.context,
					enabled: contentLoaded,
					offset: 'bottom-in-view'
				});				
				that.destroy(pageSection, block, waypoint);

				waypoints.push(waypoint);
			});

			// Refresh the waypoints when it comes back from fetching content
			// Keep it at the top, so that it executes also when popManager.isContentLoaded(block) is false
			// and inits lazy
			block.on('fetchCompleted', function(e) {
				
				var block = $(this);
				// var blockQueryState = popManager.getBlockQueryState(pageSection, block);

				// if (blockQueryState[M.URLPARAM_STOPFETCHING]) {	
				// Update waypoint
				if (popManager.stopFetchingBlock(pageSection, block)) {

					// If stop fetching no need to use the waypoint anymore. Re-enable it only when filtering,
					// since that's the only way to re-fill the content
					$.each(waypoints, function(index, waypoint) {

						if (waypoint.enabled) {
							waypoint.disable();
							that.reEnable(pageSection, block, waypoint);
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

	execWaypointsToggleCollapse : function(pageSection, block, target, collapse) {
	
		var that = this;
		var opts = that.getOptions(pageSection, target);

		var waypoint = new Waypoint({
			element: target,
			handler: function(direction) {

				var waypoint = this;
				var target = this.element;
				if (popManager.isHidden(target)) {
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
		that.destroy(pageSection, block, waypoint);
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
	// getOptions : function(pageSection, waypoint) {
	
	// 	var that = this;

	// 	var opts = {};

	// 	// Add a context if there is one
	// 	// var context = waypoint.closest('.pop-viewport');
	// 	var context = popManager.getViewport(pageSection, waypoint);
	// 	if (context.length) {
	// 		opts['context'] = context;
	// 	}

	// 	return opts;
	// },

	destroy : function(pageSection, block, waypoint) {

		var that = this;
		
		var pageSectionPage = popManager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {

			waypoint.destroy();
		});
	},

	// executeHistoryState : function(pageSection, waypoint, options) {
	
	// 	var that = this;
		
	// 	// popManager.historyReplaceState(pageSection, waypoint, options);
	// 	popManager.historyReplaceState(waypoint, options);
	// }
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popWaypoints, [/*'documentInitialized', */'waypointsFetchMore', 'waypointsToggleClass', 'waypointsToggleCollapse', 'waypointsTheater', 'waypointsHistoryStateOriginal', 'waypointsHistoryStateNew']);