"use strict";
(function($){
window.popMenus = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	activeLinks : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets, domain = args.domain;

		// Only if the pageSection allows activeLinks (eg: side menu does not, or otherwise that link will remain always painted)
		var settings = popManager.getFetchPageSectionSettings(pageSection);
		if (settings.activeLinks) {

			// Source can be params or topLevelFeedback
			var feedback = popManager.getTopLevelFeedback(domain);

			var parentPageId = feedback[M.URLPARAM_PARENTPAGEID];
			if (parentPageId) {

				targets.find('.menu-item-object-id-'+parentPageId).each(function() {

					var menuItem = $(this);
					menuItem.addClass('active');
					menuItem.parents('.menu-item').addClass('active');

					// // If inside a collapse (with class 'pop-showactive'), open it
					// // Needed for the Side Sections Menu
					// jQuery(document).ready( function($) {
					// 	var collapse = menuItem.closest('.collapse.pop-showactive').not('.in');
					// 	collapse.collapse('show');
					// });
				});
			}
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popMenus, ['activeLinks']);
