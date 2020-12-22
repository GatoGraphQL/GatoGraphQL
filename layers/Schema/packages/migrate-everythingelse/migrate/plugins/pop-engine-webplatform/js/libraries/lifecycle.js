"use strict";
(function($){
window.pop.Lifecycle = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	destroyPage : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		
		targets.click(function(e) {
			
			e.preventDefault();
			var link = $(this);
			var pageSectionPage = $(link.data('target'));

			// Call the destroy to the pageSection, it will know which page to destroy
			pop.Manager.destroyPageSectionPage(domain, pageSection, pageSectionPage);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Lifecycle, ['destroyPage']);
