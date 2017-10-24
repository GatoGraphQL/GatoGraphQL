(function($){
popCDNCoreHooks = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	mentionsSource : function(args) {
	
		var t = this;
		args.url = popCDN.convertURL(args.url);
	},

	typeaheadPrefetchURL : function(args) {
	
		var t = this;
		args.url = popCDN.convertURL(args.url);
	},

	typeaheadRemoteURL : function(args) {
	
		var t = this;
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