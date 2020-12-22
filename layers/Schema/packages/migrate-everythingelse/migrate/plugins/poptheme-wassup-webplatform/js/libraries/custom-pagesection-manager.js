"use strict";
// These functions are called from the event handler, so they lose the context of who 't' is
// This is needed so that we can call jQuery.off on them
// So then place them outside the pop.PageSectionManager structure
function showTopNav() {
	pop.PageSectionManager.open(pop.CustomPageSectionManager.getTopNav());
}
function hideTopNav() {
	pop.PageSectionManager.close(pop.CustomPageSectionManager.getTopNav());
}
(function($){
window.pop.CustomPageSectionManager = {

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	enableShowHideTopNav : function() {

		var that = this;
		$(window).on('scroll:down', hideTopNav);
		$(window).on('scroll:up', showTopNav);
	},
	disableShowHideTopNav : function() {

		var that = this;
		// The context for t is lost, so gotta call the function using the full structure
		$(window).off('scroll:down', hideTopNav);
		$(window).off('scroll:up', showTopNav);
	},
	getTopNav : function() {

		var that = this;

		var offCanvasGroup = pop.PageSectionManager.getGroup();
		return offCanvasGroup.find('.offcanvas-topnav');
	},
	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.CustomPageSectionManager, []);
