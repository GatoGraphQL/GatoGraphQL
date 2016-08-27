(function($){
popDynamicMaxHeight = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	dynamicMaxHeight : function(args) {
	
		var t = this;
		var targets = args.targets;

		targets.dynamicMaxHeight();
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popDynamicMaxHeight, ['dynamicMaxHeight']);