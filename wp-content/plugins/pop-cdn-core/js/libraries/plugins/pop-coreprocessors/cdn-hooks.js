(function($){
window.popCDNCoreHooks = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	mentionsSource : function(args) {
	
		var that = this;
		args.url = popCDN.convertURL(args.url);
	},

	typeaheadPrefetchURL : function(args) {
	
		var that = this;
		args.url = popCDN.convertURL(args.url);
	},

	typeaheadRemoteURL : function(args) {
	
		var that = this;
		args.url = popCDN.convertURL(args.url);
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popCDNCoreHooks, ['mentionsSource', 'typeaheadPrefetchURL', 'typeaheadRemoteURL']);