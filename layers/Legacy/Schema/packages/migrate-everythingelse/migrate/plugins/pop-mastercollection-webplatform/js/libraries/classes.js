"use strict";
(function($){
window.pop.Classes = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	addDomainClass : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.each(function() {

			var target = $(this);

			// Add a prefix to the domain, such as 'visible-loggedin-', etc
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, target);
			var prefix = jsSettings['prefix'] || '';
			target.addClass(prefix+getDomainId(domain));
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Classes, ['addDomainClass']);
