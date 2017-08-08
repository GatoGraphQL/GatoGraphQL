(function($){
popBlockFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	hideIfEmptyBlock : function(args) {

		var t = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block;

		if (popManager.hideIfEmpty(domain, pageSection, block)) {

			block.addClass('hidden');
		}
		else {

			block.removeClass('hidden');
		}
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBlockFunctions, ['hideIfEmptyBlock']);