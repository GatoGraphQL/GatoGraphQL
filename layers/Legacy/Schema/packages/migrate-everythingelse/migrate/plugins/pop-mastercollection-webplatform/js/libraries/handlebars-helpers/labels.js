"use strict";

Handlebars.registerHelper('labelize', function(strings, label, options) {

	var ret = '', extra_class = '';
	if (strings) {
		for (var i = 0; i < strings.length; i++) {
			extra_class = pop.c.LABELIZE_CLASSES[strings[i]] ? pop.c.LABELIZE_CLASSES[strings[i]] : '';
			ret += '<span class="label '+label+' '+extra_class+'">'+strings[i]+'</span> ';
		}
	}

	return new Handlebars.SafeString ( ret );
});