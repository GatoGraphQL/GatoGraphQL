"use strict";
(function($){
window.popMapMemory = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	initBlockRuntimeMemoryIndependent : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, mempage = args.runtimeMempage;

		// Initialize with this library key
		mempage.map = {};

		// Reset values
		that.resetBlockRuntimeMemory(pageSection, block);
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	getRuntimeMemoryPage : function(pageSection, targetOrId) {

		var that = this;
		return popManager.getRuntimeMemoryPage(pageSection, targetOrId).map;
	},

	resetBlockRuntimeMemory : function(pageSection, targetOrId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, targetOrId);
		var empty = that.getEmtpyBlockRuntimeMemory();

		$.extend(mempage, empty);
	},

	getEmtpyBlockRuntimeMemory : function() {

		return {
			markers: {},
			markersPos: {},
			markersOpen: {},
			maps: {},
		};
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popMapMemory, ['initBlockRuntimeMemoryIndependent']);
