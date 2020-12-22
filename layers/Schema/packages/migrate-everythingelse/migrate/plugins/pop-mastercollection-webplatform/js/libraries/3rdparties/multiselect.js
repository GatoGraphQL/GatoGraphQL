"use strict";
(function($){
window.pop.Multiselect = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// destroyPageSection : function(args) {

	// 	var that = this;
	// 	var pageSection = args.pageSection;

	// 	pageSection.find('.multiselect').remove();
	// },

	multiselect : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;

		var pageSectionPage = pop.Manager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {

			targets.multiselect('destroy');
		});
		
		var options = {
			buttonClass: 'btn btn-default',
			buttonContainer: '<div class="btn-group multiselect-btn-group" />',
			enableHTML: true, // Needed for showing fontawesome icons inside the options, as in the Sections filter
			nonSelectedText: pop.c.MULTISELECT_NONSELECTEDTEXT,
			nSelectedText: pop.c.MULTISELECT_NSELECTEDTEXT,
			allSelectedText: pop.c.MULTISELECT_ALLSELECTEDTEXT
		};			
		targets.each(function() {

			var multiselect = $(this);
			var customOptions = $.extend({}, options);
			if (multiselect.attr('title')) {
			
				customOptions['nonSelectedText'] = multiselect.attr('title');
			}
			if (multiselect.hasClass('input-sm')) {
			
				customOptions['buttonClass'] += ' btn-sm';
			}
			if (multiselect.hasClass('input-lg')) {
			
				customOptions['buttonClass'] += ' btn-lg';
			}
			multiselect.multiselect(customOptions);
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Multiselect, ['multiselect']);