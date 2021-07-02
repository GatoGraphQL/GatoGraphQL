"use strict";

Handlebars.registerHelper('statusLabel', function(status, options) {

	var ret = '<span class="label '+pop.c.STATUS.class[status]+' label-'+status+'">'+pop.c.STATUS.text[status]+'</span>';

	return new Handlebars.SafeString ( ret );
});