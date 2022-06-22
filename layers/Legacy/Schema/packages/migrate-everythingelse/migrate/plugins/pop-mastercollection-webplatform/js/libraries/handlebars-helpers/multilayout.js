"use strict";

Handlebars.registerHelper('withConditionalOnDataFieldModule', function(typeOutputKey, objectID, conditionDataFieldModules, $defaultModule, context, options) {

	var tls = context.tls;
	var domain = tls.domain;

	// Obtain the key composed as: 'post_type'-'mainCategory'
	var resolvedObject = pop.Manager.getDBObject(domain, typeOutputKey, objectID);

	// Fetch the layout for that particular configuration
	var layout = '';
	jQuery.each(conditionDataFieldModules, function(conditionField, componentOutputName) {
		// Check if the property evals to `true`. If so, use the corresponding module
		if (resolvedObject[conditionField]) {
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

	// Add typeOutputKey and objectID back into the context
	jQuery.extend(layoutContext, {typeOutputKey: typeOutputKey, objectID: objectID});

	// Expand the JS Keys
	pop.Manager.expandJSKeys(layoutContext);

	return options.fn(layoutContext);
});

Handlebars.registerHelper('layoutLabel', function(typeOutputKey, resolvedObject, options) {

	var label = '';
	jQuery.each(resolvedObject['multilayoutKeys'], function(index, key) {

		label = pop.c.MULTILAYOUT_LABELS[resolvedObject[key]];
		if (label) {
			return -1;
		}
	});

	return label || '';
});
