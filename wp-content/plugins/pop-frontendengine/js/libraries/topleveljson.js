"use strict";
(function($){
window.popTopLevelJSON = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	
	strings: {},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	getProperties : function() {

		var that = this;

		// Merge the json with the properties from object popTopLevelJSON
		var properties = {};
		if (!$.isEmptyObject(that.strings)) {
			
			for (var property in that.strings) {
				if (that.strings.hasOwnProperty(property)) {
			
					// We have a string, convert it to JSON
					properties[property] = JSON.parse(that.strings[property]);
				}
			}
		}

		return properties;
	},

	init : function() {

		var that = this;
		if (M.RUNTIMEJS) {
			
			$(document).on('initTopLevelJson', function(e, json) {

				$.extend(json, that.getProperties());	
			});
		}
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popTopLevelJSON.init();
