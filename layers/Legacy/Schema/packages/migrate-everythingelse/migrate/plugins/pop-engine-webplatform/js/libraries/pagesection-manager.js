"use strict";
(function($){
window.pop.PageSectionManager = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	offcanvasToggle : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			var control = $(this);
			var mode = control.data('mode');
			var target = $(control.data('target'));
			var action = control.data('toggle');
			if (action == 'offcanvas-open') {
				that.open(target, mode);
			}
			else if (action == 'offcanvas-close') {
				that.close(target, mode);
			}
			else if (action == 'offcanvas-toggle') {
				that.toggle(target, mode);
			}
		});
	},
	isHidden : function(args) {
	
		var that = this;
		var targets = args.targets;

		// Comment Leo: this makes the waypoint on the frame-side fail, because the class needed is active-side
		// So fix here!
		// return false;

		// If the pageSectionPage does not have class 'active' then it is hidden
		// var pageSectionPage = pop.Manager.getPageSectionPage(targets);
		// if (!pageSectionPage.hasClass('active')) {
		// 	return true;
		// }

		// Check that the pageSection is not hidden

		var offcanvas = targets.closest('.offcanvas');
		return that.execIsHidden(offcanvas);
		// if (offcanvas.length) {

		// 	var classs = that.getOffcanvasActiveClass(offcanvas);
		// 	return !that.getGroup(offcanvas).hasClass(classs);
		// }

		// return false;		
	},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	execIsHidden : function(offcanvas) {
	
		var that = this;
		if (offcanvas.length) {

			var classs = that.getOffcanvasActiveClass(offcanvas);
			return !that.getGroup(offcanvas).hasClass(classs);
		}

		return false;		
	},
	
	getOffcanvasActiveClass : function(offcanvas, mode) {
	
		var that = this;
		var section = offcanvas.data('offcanvas');
		var ret = 'active-'+section;

		if (mode == 'xs') {
			ret += '-xs';
		}

		return ret;
	},

	open : function(offcanvas, mode) {
	
		var that = this;
		var group = that.getGroup(offcanvas);
		var classs = that.getOffcanvasActiveClass(offcanvas, mode);
		group.addClass(classs);
		that.triggerStatus(offcanvas, mode, 'opened');
	},
	close : function(offcanvas, mode) {
	
		var that = this;
		var group = that.getGroup(offcanvas);
		var classs = that.getOffcanvasActiveClass(offcanvas, mode);
		group.removeClass(classs);
		that.triggerStatus(offcanvas, mode, 'closed');
	},
	toggle : function(offcanvas, mode) {
	
		var that = this;
		var group = that.getGroup(offcanvas);
		var classs = that.getOffcanvasActiveClass(offcanvas, mode);
		// group.toggleClass(classs);
		if (group.hasClass(classs)) {
			that.close(offcanvas, mode);
		}
		else {
			that.open(offcanvas, mode);
		}
	},
	triggerStatus : function(offcanvas, mode, status) {
	
		var that = this;
		var group = that.getGroup(offcanvas);
		group.triggerHandler('on.bs.pagesection-group:pagesection-'+offcanvas.attr('id')+':'+status, [offcanvas, mode]);
	},
	

	// getTopLevelSettingsId : function() {
		
	// 	var that = this;
	// 	return pop.c.ID_JSON;
	// },
	getPageSectionStatus : function(pageSection) {
	
		var that = this;

		return $('#'+pageSection.attr('id')+'-status');
	},
	getGroup : function(offcanvas) {

		var that = this;

		// The offcanvas can be empty. If so, then return the main pageSectionGroup
		// Otherwise, it could also be the quickView
		if (offcanvas) {
			return offcanvas.closest('.pop-pagesection-group');
		}
		return $('#'+pop.c.COMPONENTID_PAGESECTIONGROUP_ID);
	},
	theater : function(theater) {

		var that = this;
		var group = that.getGroup();
		that.transitionEndResize();
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

		var that = this;
		var group = that.getGroup();
		return group.hasClass('theater');
	},
	transitionEndResize : function() {

		var that = this;

		// Dispatch a window resize so that the Calendar / Google map gets updated
		var offCanvasGroup = that.getGroup();
		offCanvasGroup.one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function() {
			windowResize();
		});
	},

	getOpenMode : function(pageSection) {

		return pageSection.data('pagesection-openmode') || 'automatic';
	}
	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.PageSectionManager, ['isHidden', 'offcanvasToggle'], true); // Make all base JS classes high priority so that they execute first
