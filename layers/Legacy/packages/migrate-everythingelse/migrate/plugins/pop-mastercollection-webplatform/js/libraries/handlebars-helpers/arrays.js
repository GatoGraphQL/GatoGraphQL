"use strict";

Handlebars.registerHelper('maybe_make_array', function(element, options) {

    // If null, return null
    if (typeof element === 'undefined') {
		
		return element;
	}

	// If empty, return an empty array
	if (!element) {
		
		return [];
	}

	// If not an array, make it so
	if (jQuery.type(element) !== "array") {
		
		return [element];
	}

    return element;
});