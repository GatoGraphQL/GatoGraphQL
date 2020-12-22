"use strict";
(function($){
window.pop.Bootstrap = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	modal : function(args) {

		var that = this;
		var targets = args.targets;

		// Hooks
		targets.on('show.bs.modal', function(e) {

			var modal = $(this);
			var link = $(e.relatedTarget);
			
			// Make sure the link we got is the original one, and not the intercepted one
			// header is stored under the original link, not the interceptor
			link = pop.Manager.getOriginalLink(link);

			// Set the header if any on the link
			var modalHeader = link.data('header') || '';
			modal.find('.modal-header .pop-box').html(modalHeader);
		});
	},

	closeModals : function() {

		var that = this;
		$('.modal.in').modal('hide');
	},

	showingModal : function(modal) {

		var that = this;
		return modal.hasClass('in');
	},

	popover : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		that.execPopover(pageSection, block, targets);
	},

	contentPopover : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Execute the popover on any link with attr data-popover-target defined
		that.execPopover(pageSection, block, targets.find('a[data-popover-target]'));
	},

	tooltip : function(args) {
		
		var that = this;
		var pageSection = args.pageSection, /*block = args.block,*/ targets = args.targets;

		jQuery(document).ready( function($) {
			
			// Comment Leo 11/04/2015: allow the tooltip to be used from embed-frame-top (not within a block),
			// so removed using block here
			var pageSectionPage = pop.Manager.getPageSectionPage(targets);
			pageSectionPage.one('destroy', function() {
				
				targets.tooltip('destroy');
			});

			targets.each(function() {

				var tooltip = $(this);
				var container = pop.Manager.getDOMContainer(pageSection, tooltip);
				var containerTarget = '#'+container.attr('id');
				
				// Comment Leo 10/04/2014: do not use tooltip-options anymore to set the container/viewport, instead
				// use pop.Manager.getViewport(), this way we can set the viewport on the pageSectionPage. This allows the tooltip/popover
				// to not be visible on the iPad/iPhone when clicking on a link (otherwise it stays there forever, since the container is the pageSection)
				var options = {
					placement : tooltip.data('tooltip-placement') || "top",
					title: tooltip.attr('title'),
					container: containerTarget,
				};

				tooltip.tooltip(options);
			});		
		});		
	},

	openParentCollapse : function(args) {

		var that = this;
		var targets = args.targets;

		that.execOpenParentCollapse(targets);
	},

	alertCloseOnTimeout : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		// get the time from the block
		var closeTime = targets.data('closetime') || 3000;
		targets.data('close', true);
		targets.mouseenter(function() {
			
			targets.data('close', null);

			// Also allow to change the style
			targets.addClass('hover');
		})
		targets.mouseleave(function() {
			
			targets.data('close', true);
			that.execAlertCloseOnTimeout(targets, closeTime);

			// Also allow to change the style
			targets.removeClass('hover');
		})

		// set the timeout
		that.execAlertCloseOnTimeout(targets, closeTime);
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	execOpenParentCollapse : function(targets) {

		var that = this;

		// Open the Parent Collapse, needed for the Simple View Feed when adding a comment and the comments is not showing
		targets.closest('.collapse').not('.in').collapse('show');
	},

	execPopover : function(pageSection, block, targets) {

		var that = this;

		jQuery(document).ready( function($) {
			var pageSectionPage = pop.Manager.getPageSectionPage(block);
			pageSectionPage.one('destroy', function() {
				targets.popover('destroy');
			});
				
			var container = pop.Manager.getDOMContainer(pageSection, block);
			var containerTarget = '#'+container.attr('id');
			// Default Options
			var options = {
				animation : false,
				html: true,
				trigger: 'manual focus', // manual: keep it open while onmouseover the popover. focus: for iphone/ipad, dismiss when clicking away
				placement: 'auto',
				delay: {
					hide: 100
				},
				container: containerTarget,
			};	  
			targets.each(function() {

				var popover = $(this);
				var popoverTarget = $(popover.data('popover-target')).first();
				if (popoverTarget.length) {

					// Comment Leo 24/03/2015: do not encode the html anymore, because then it doesn't execute the javascript, eg:
					// Events calendar popover with Locations Map links, so it doesn't add the corresponding markers and then JS error when clicking there
					// The content is first encoded, so that ids will not be duplicated
					// var content = unescapeHtml(popover.next('.make-popover-content').html());
					// var content = popover.next('.make-popover-content').html();
					var content = popoverTarget.html();
					
					// Comment Leo 01/04/2015: the approach above fails because then the Calendar in the Navigator doesn't look good.
					// Instead, set the block id to the popover, and add another way to find the block when the object is not inside the block (in function pop.Manager.getBlock)
					var blockTarget = '#'+pop.Manager.getBlock(popover).attr('id');
					content = '<span data-block="'+blockTarget+'">'+content+'</span>';

					var popoverOptions = $.extend({}, options, {content: content});

					// Code taken from http://stackoverflow.com/questions/15989591/how-can-i-keep-bootstrap-popover-alive-while-the-popover-is-being-hovered
					popover
						.popover(popoverOptions)
						.on("mouseenter", function () {
							var _this = this;
							$(this).popover("show");
							$(".popover").on("mouseleave", function () {
								$(_this).popover('hide');
							});
						}).on("mouseleave", function () {
							var _this = this;
							setTimeout(function () {
								if (!$(".popover:hover").length) {
									$(_this).popover("hide")
								}
							}, popoverOptions.delay.hide);
						});
					}
			});	
		});	
	},

	execAlertCloseOnTimeout : function(targets, closeTime) {

		var that = this;
		setTimeout(function () {
			
			if (targets.data('close')) {
	
				targets.alert('close');
			}
		}, closeTime);
	},

	scrollTop : function(args) {

		var that = this;
		var elem = args.elem, top = args.top, animate = args.animate;

		// Try modal if available
		var modal = elem.closest('.modal').addBack('.modal');
		if (modal.length) {
			
			modal.animate({ scrollTop: 0 }, 'fast');
			return true;
		}

		return false;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Bootstrap, ['modal', 'closeModals', 'popover', 'contentPopover', 'tooltip', 'openParentCollapse', 'alertCloseOnTimeout', 'scrollTop']);