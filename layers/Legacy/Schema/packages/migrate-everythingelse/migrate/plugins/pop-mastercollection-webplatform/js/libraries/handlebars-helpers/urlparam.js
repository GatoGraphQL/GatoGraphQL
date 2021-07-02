"use strict";

Handlebars.registerHelper('addQueryArg', function(param, value, url, options) {

	// Allow for inputs with multiple values
	if (!jQuery.isArray(value)) {
		value = [value];
	}
	jQuery.each(value, function(index, val) {

		url = add_query_arg(param, val, url);
	});

	return url;
});