"use strict";
(function($){
window.popMapRuntimeMemory = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initBlockRuntimeMemoryIndependent : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, mempage = args.runtimeMempage;

		// Initialize with this library key
		mempage.mapRuntime = {};

		// Reset values
		that.resetBlockRuntimeMemory(pageSection, block);
	},
		
	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	getRuntimeMemoryPage : function(pageSection, targetOrId) {

		var that = this;
		return popManager.getRuntimeMemoryPage(pageSection, targetOrId).mapRuntime;
	},

	resetBlockRuntimeMemory : function(pageSection, targetOrId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, targetOrId);
		var empty = {

			content: '', 
			title: '', 
			marker_ids: [],
		};

		$.extend(mempage, empty);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popMapRuntimeMemory, ['initBlockRuntimeMemoryIndependent']);
