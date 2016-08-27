// These functions are called from the event handler, so they lose the context of who 't' is
// This is needed so that we can call jQuery.off on them
// So then place them outside the popPageSectionManager structure
function showTopNav() {
	popPageSectionManager.open(popPageSectionManager.getTopNav());
}
function hideTopNav() {
	popPageSectionManager.close(popPageSectionManager.getTopNav());
}
(function($){
popPageSectionManager = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	offcanvasToggle : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			var control = $(this);
			var mode = control.data('mode');
			var target = $(control.data('target'));
			var action = control.data('toggle');
			if (action == 'offcanvas-open') {
				t.open(target, mode);
			}
			else if (action == 'offcanvas-close') {
				t.close(target, mode);
			}
			else if (action == 'offcanvas-toggle') {
				t.toggle(target, mode);
			}
		});
	},
	isHidden : function(args) {
	
		var t = this;
		var targets = args.targets;

		// Comment Leo: this makes the waypoint on the frame-side fail, because the class needed is active-side
		// So fix here!
		// return false;

		// If the pageSectionPage does not have class 'active' then it is hidden
		// var pageSectionPage = popManager.getPageSectionPage(targets);
		// if (!pageSectionPage.hasClass('active')) {
		// 	return true;
		// }

		// Check that the pageSection is not hidden

		var offcanvas = targets.closest('.offcanvas');
		return t.execIsHidden(offcanvas);
		// if (offcanvas.length) {

		// 	var classs = t.getOffcanvasActiveClass(offcanvas);
		// 	return !t.getGroup(offcanvas).hasClass(classs);
		// }

		// return false;		
	},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	execIsHidden : function(offcanvas) {
	
		var t = this;
		if (offcanvas.length) {

			var classs = t.getOffcanvasActiveClass(offcanvas);
			return !t.getGroup(offcanvas).hasClass(classs);
		}

		return false;		
	},
	
	getOffcanvasActiveClass : function(offcanvas, mode) {
	
		var t = this;
		var section = offcanvas.data('offcanvas');
		var ret = 'active-'+section;

		if (mode == 'xs') {
			ret += '-xs';
		}

		return ret;
	},

	open : function(offcanvas, mode) {
	
		var t = this;
		var group = t.getGroup(offcanvas);
		var classs = t.getOffcanvasActiveClass(offcanvas, mode);
		group.addClass(classs);
		t.triggerStatus(offcanvas, mode, 'opened');
	},
	close : function(offcanvas, mode) {
	
		var t = this;
		var group = t.getGroup(offcanvas);
		var classs = t.getOffcanvasActiveClass(offcanvas, mode);
		group.removeClass(classs);
		t.triggerStatus(offcanvas, mode, 'closed');
	},
	toggle : function(offcanvas, mode) {
	
		var t = this;
		var group = t.getGroup(offcanvas);
		var classs = t.getOffcanvasActiveClass(offcanvas, mode);
		// group.toggleClass(classs);
		if (group.hasClass(classs)) {
			t.close(offcanvas, mode);
		}
		else {
			t.open(offcanvas, mode);
		}
	},
	triggerStatus : function(offcanvas, mode, status) {
	
		var t = this;
		var group = t.getGroup(offcanvas);
		group.triggerHandler('on.bs.pagesection-group:pagesection-'+offcanvas.attr('id')+':'+status, [offcanvas, mode]);
	},
	

	getTopLevelSettingsId : function() {
		
		var t = this;
		return M.TEMPLATE_TOPLEVEL_SETTINGS_ID;
	},
	getPageSectionStatus : function(pageSection) {
	
		var t = this;

		return $('#'+pageSection.attr('id')+'-status');
	},
	getGroup : function(offcanvas) {

		var t = this;

		// The offcanvas can be empty. If so, then return the main pageSectionGroup
		// Otherwise, it could also be the quickView
		if (offcanvas) {
			return offcanvas.closest('.pop-pagesection-group');
		}
		return $('#'+M.TEMPLATE_PAGESECTIONGROUP_ID);
	},
	theater : function(theater) {

		var t = this;
		var group = t.getGroup();
		t.transitionEndResize();
		if (theater) {
			group.addClass('theater');
			group.triggerHandler('on.bs.pagesection:theater');
		}
		else {
			group.removeClass('theater');
			group.triggerHandler('off.bs.pagesection:theater');
		}
	},
	isTheater : function() {

		var t = this;
		var group = t.getGroup();
		return group.hasClass('theater');
	},
	transitionEndResize : function() {

		var t = this;

		// Dispatch a window resize so that the Calendar / Google map gets updated
		var offCanvasGroup = t.getGroup();
		offCanvasGroup.one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function() {
			windowResize();
		});
	},
	enableShowHideTopNav : function() {

		var t = this;
		$(window).on('scroll:down', hideTopNav);
		$(window).on('scroll:up', showTopNav);
	},
	disableShowHideTopNav : function() {

		var t = this;
		// The context for t is lost, so gotta call the function using the full structure
		$(window).off('scroll:down', hideTopNav);
		$(window).off('scroll:up', showTopNav);
	},
	getTopNav : function() {

		var t = this;

		var offCanvasGroup = t.getGroup();
		return offCanvasGroup.find('.offcanvas-topnav');
	},

	getOpenMode : function(pageSection) {

		return pageSection.data('pagesection-openmode') || 'automatic';
	}
	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popPageSectionManager, ['isHidden', 'offcanvasToggle'], true); // Make all base JS classes high priority so that they execute first
