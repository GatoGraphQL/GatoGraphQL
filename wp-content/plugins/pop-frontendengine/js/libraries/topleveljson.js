"use strict";
(function($){
window.popTopLevelJSON = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	
	// Objects can be assigned directly
	objects: {},

	// Strings will perform a replacement before being converted to objects
	strings: {},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	getProperties : function() {

		var that = this;

		// Merge the json with the properties from object popTopLevelJSON
		var properties = {};
		that.addProperties(properties, that.strings, true);
		that.addProperties(properties, that.objects, false);

		return properties;
	},

	addProperties : function(properties, from, parse) {

		var that = this;

		// Merge the json with the properties from object popTopLevelJSON
		if (!$.isEmptyObject(from)) {
			
			for (var property in from) {
				if (from.hasOwnProperty(property)) {
			
					// We have a string, convert it to JSON
					properties[property] = parse ? JSON.parse(from[property]) : from[property];
				}
			}
		}
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
