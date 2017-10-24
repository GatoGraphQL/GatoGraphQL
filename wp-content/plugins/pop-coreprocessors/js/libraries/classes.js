(function($){
popClasses = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	addDomainClass : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.each(function() {

			var target = $(this);

			// Add a prefix to the domain, such as 'visible-loggedin-', etc
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, target);
			var prefix = jsSettings['prefix'] || '';
			targets.addClass(prefix+getDomainId(domain));
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popClasses, ['addDomainClass']);
