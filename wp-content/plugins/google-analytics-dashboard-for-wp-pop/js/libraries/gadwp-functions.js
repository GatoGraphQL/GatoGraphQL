(function($){
popGADWP = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// pageSectionFetchSuccess : function(args) {

	// 	var t = this;

	// 	// Only register the Google Analytics call if it is not a silent page
	// 	var options = args.options;
	// 	if (!options.silentDocument) {

	// 		ga('send', 'pageview');
	// 	}
	// },
	stateURLPushed : function(args) {

		var t = this;
		ga('send', 'pageview');
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popGADWP, ['pageSectionFetchSuccess']);
popJSLibraryManager.register(popGADWP, ['stateURLPushed']);
