"use strict";
(function($){
window.pop.SSR = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	renderPageSectionDOMs : function(args) {
	
		var that = this;
		var options = args.options, domain = args.domain, pageSection = args.pageSection;

		if (options['init'] && pop.c.USESERVERSIDERENDERING) {

			// Trigger 'component:merged' for the Events Map to add the markers
			// It must come before the next line, which will execute the JS on all elements
			// (Eg: then layout-initjs-delay.tmpl works fine)
			pop.Manager.triggerHTMLMerged();

			// Set the newDOMs in the args, and we are done
			args.newDOMs = pop.Manager.getPageSectionDOMs(domain, pageSection);

			// In addition, Add the initial 'fetch-params' so we can also show the "page refreshed, please click here to refresh" when first loading the website
			// var tlFeedback = pop.Manager.getTopLevelFeedback(domain);
			// var url = tlFeedback[pop.c.URLPARAM_URL];
			var url = pop.Manager.getRequestMeta(domain)[pop.c.URLPARAM_URL];
			args.options['fetch-params'] = {
				url: url,
				target: pop.c.URLPARAM_TARGET_FULL,
				'fetch-url': url,
			};
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.SSR, ['renderPageSectionDOMs']);
