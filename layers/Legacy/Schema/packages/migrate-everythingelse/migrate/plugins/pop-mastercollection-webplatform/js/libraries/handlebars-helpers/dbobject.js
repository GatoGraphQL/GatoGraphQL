"use strict";

Handlebars.registerHelper('withDBObject', function(dbKey, objectID, options) {

	var context = options.hash.context || this;
	var tls = context.tls;
	var domain = tls.domain;

	// Replace the context with only the dbObject
	var context = pop.Manager.getDBObject(domain, dbKey, objectID);
	
	return options.fn(context);
});

