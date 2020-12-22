"use strict";

Handlebars.registerHelper('formatValue', function(value, format, options) {

	// If the value is null, don't do anything, since it must be coming from failing both value and dbObject[dbObjectField] in formcomponentValue
	if (value === null) {
		return value;
	}

	switch (format) {

		// Convert from boolean to string
		case pop.c.VALUEFORMAT_BOOLTOSTRING:

			// Handle arrays
			if (jQuery.type(value) == 'array') {

				var ret = [];
				jQuery.each(value, function(index, val) {

					ret.push(val ? pop.c.BOOLSTRING_TRUE : pop.c.BOOLSTRING_FALSE);
				});
				return ret;
			}
		
			if (value) {
				return pop.c.BOOLSTRING_TRUE;
			}
			return pop.c.BOOLSTRING_FALSE;
	}

	return value;
});

