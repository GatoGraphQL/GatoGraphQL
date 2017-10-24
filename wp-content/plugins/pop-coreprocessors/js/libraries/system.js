(function($){
popSystem = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	destroyPage : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		
		targets.click(function(e) {
			
			e.preventDefault();
			var link = $(this);
			var pageSectionPage = $(link.data('target'));

			// Call the destroy to the pageSection, it will know which page to destroy
			popManager.destroyPageSectionPage(domain, pageSection, pageSectionPage);
		});
	},

	closePageSection : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			popPageSectionManager.close(pageSection);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popSystem, ['closePageSection', 'destroyPage']);
