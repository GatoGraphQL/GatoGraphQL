"use strict";
(function($){
window.pop.InitializeData = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	
	// Extensions will perform a replacement before being converted to objects
	extensions: {},

	init : function() {

		var that = this;
		$(document).on('PoP:initData', function(e, jsonData) {

			$.extend(true, jsonData, that.extensions);	
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
if (pop.c.RUNTIMEJS) {

	pop.InitializeData.init();
}
