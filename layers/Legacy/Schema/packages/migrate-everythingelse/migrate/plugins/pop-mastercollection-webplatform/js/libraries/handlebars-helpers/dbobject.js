"use strict";

Handlebars.registerHelper('withDBObject', function(typeOutputKey, objectID, options) {

	var context = options.hash.context || this;
	var tls = context.tls;
	var domain = tls.domain;

	// Replace the context with only the dbObject
	var context = pop.Manager.getDBObject(domain, typeOutputKey, objectID);
	
	return options.fn(context);
});

