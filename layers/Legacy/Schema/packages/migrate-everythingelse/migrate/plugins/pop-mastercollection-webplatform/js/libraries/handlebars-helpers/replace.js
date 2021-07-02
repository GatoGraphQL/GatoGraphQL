"use strict";

Handlebars.registerHelper('replace', function(search, replace, options) {

	if (search) {

		// Watch out! If $replace == null (as it happens coming from formcomponentValue) then it will replace by "null", in that case make it an empty string
		replace = replace || '';

		if (search != replace) {
			return options.fn(this).replace(new RegExp(search, 'g'), replace)
		}
	}

	return options.fn(this);
});