// These functions are called from the event handler, so they lose the context of who 't' is
// This is needed so that we can call jQuery.off on them
// So then place them outside the popPageSectionManager structure
function showTopNav() {
	popPageSectionManager.open(popCustomPageSectionManager.getTopNav());
}
function hideTopNav() {
	popPageSectionManager.close(popCustomPageSectionManager.getTopNav());
}
(function($){
popCustomPageSectionManager = {

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

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

		var offCanvasGroup = popPageSectionManager.getGroup();
		return offCanvasGroup.find('.offcanvas-topnav');
	},
	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popCustomPageSectionManager, []);
