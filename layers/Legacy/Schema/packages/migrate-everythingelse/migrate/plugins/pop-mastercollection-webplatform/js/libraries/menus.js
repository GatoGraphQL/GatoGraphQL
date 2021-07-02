"use strict";
(function($){
window.pop.Menus = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	activeLinks : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.each(function() {

			var target = $(this);
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, target);
			var activeLinkMenuItemIds = jsSettings['active-link-menu-item-ids'] || [];
			$.each(activeLinkMenuItemIds, function(index, activeLinkMenuItemId) {

				target.find('.menu-item-object-id-'+activeLinkMenuItemId)
					.addClass('active')
					.parents('.menu-item')
						.addClass('active');
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Menus, ['activeLinks']);
