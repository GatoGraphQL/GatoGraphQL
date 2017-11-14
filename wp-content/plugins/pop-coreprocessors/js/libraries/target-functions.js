(function($){
window.popTargetFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	copyHeader : function(args) {

		var that = this;
		var targets = args.targets, link = args.relatedTarget;

		// Whenever replicating an Addon, we might want to pick extra information from the opening link (relatedTarget), eg: Header from att data-header for the Contact Profile Addon
		if (link) {

			// Make sure the link we got is the original one, and not the intercepted one
			// header is stored under the original link, not the interceptor
			link = popManager.getOriginalLink(link);
			
			// Set the header if any on the link
			var header = link.data('header');
			if (header) {
				targets.find('.pop-header').removeClass('hidden').find('.pop-box').html(header);
			}
		}
	},

	highlight : function(args) {

		var that = this;
		var targets = args.targets;
		
		targets.each(function() {
	
			// Must obtain the pageSection again and not use the one passed in the args, since the pageSection might be a different one
			// (eg: adding a comment from frame-addons and content added in main)
			var target = $(this);
			var pageSection = popManager.getPageSection(target);
			popManager.scrollToElem(pageSection, target, true);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popTargetFunctions, ['copyHeader', 'highlight']);
