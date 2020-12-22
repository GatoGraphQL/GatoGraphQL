"use strict";

Handlebars.registerHelper('withDBObject', function(dbKey, dbObjectID, options) {

	var context = options.hash.context || this;
	var tls = context.tls;
	var domain = tls.domain;

	// Replace the context with only the dbObject
	var context = pop.Manager.getDBObject(domain, dbKey, dbObjectID);
	
	return options.fn(context);
});

