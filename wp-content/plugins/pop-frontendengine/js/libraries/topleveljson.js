(function($){
popTopLevelJSON = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	
	strings: {},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	getProperties : function() {

		var t = this;

		// Merge the json with the properties from object popTopLevelJSON
		var properties = {};
		if (!$.isEmptyObject(t.strings)) {
			
			for (var property in t.strings) {
				if (t.strings.hasOwnProperty(property)) {
			
					// We have a string, convert it to JSON
					properties[property] = JSON.parse(t.strings[property]);
				}
			}
		}

		return properties;
	},

	init : function() {

		var t = this;
		$(document).on('initTopLevelJson', function(e, json) {

			$.extend(json, t.getProperties());	
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popTopLevelJSON.init();
