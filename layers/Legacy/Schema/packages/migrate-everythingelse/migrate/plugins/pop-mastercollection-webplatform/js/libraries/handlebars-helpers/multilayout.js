"use strict";

Handlebars.registerHelper('withConditionalOnDataFieldModule', function(dbKey, objectID, conditionDataFieldModules, $defaultModule, context, options) {

	var tls = context.tls;
	var domain = tls.domain;

	// Obtain the key composed as: 'post_type'-'mainCategory'
	var dbObject = pop.Manager.getDBObject(domain, dbKey, objectID);

	// Fetch the layout for that particular configuration
	var layout = '';
	jQuery.each(conditionDataFieldModules, function(conditionField, componentOutputName) {
		// Check if the property evals to `true`. If so, use the corresponding module
		if (dbObject[conditionField]) {
			layout = componentOutputName;
			return false;
		}
	});
	if (!layout) {
		layout = defaultModule;
	}

	// If still no layout, then do nothing
	if (!layout) {
		return '';
	}

	// Render the content from this layout
	var layoutContext = context[pop.c.JS_SUBCOMPONENTS][layout];

	// Add dbKey and objectID back into the context
	jQuery.extend(layoutContext, {dbKey: dbKey, objectID: objectID});

	// Expand the JS Keys
	pop.Manager.expandJSKeys(layoutContext);

	return options.fn(layoutContext);
});

Handlebars.registerHelper('layoutLabel', function(dbKey, dbObject, options) {

	var label = '';
	jQuery.each(dbObject['multilayoutKeys'], function(index, key) {

		label = pop.c.MULTILAYOUT_LABELS[dbObject[key]];
		if (label) {
			return -1;
		}
	});

	return label || '';
});
