"use strict";

Handlebars.registerHelper('destroyUrl', function(url, options) {

	return new Handlebars.SafeString(pop.Manager.getDestroyUrl(url));
});

Handlebars.registerHelper('iffirstload', function(options) {

	var context = options.hash.context || this;
	var pssId = options.hash.pssId || context.pss.pssId;
	var condition = pop.Manager.isFirstLoad(pssId);
	if (condition) {
        return options.fn(this);
    } 
    else {
        return options.inverse(this);
    }
});

Handlebars.registerHelper('interceptAttr', function(options) {

	var context = options.hash.context || this;
	var intercept = context[pop.c.JS_INTERCEPT] || {};

	return new Handlebars.SafeString((intercept[pop.c.JS_TYPE] ? ' data-intercept="'+intercept[pop.c.JS_TYPE]+'"' : '') + (intercept.settings ? ' data-intercept-settings="'+intercept[pop.c.JS_SETTINGS]+'"' : '') + (intercept[pop.c.JS_TARGET] ? ' target="'+intercept[pop.c.JS_TARGET]+'"' : '')  + (intercept[pop.c.JS_SKIPSTATEUPDATE] ? ' data-intercept-skipstateupdate="true"' : '') + ' style="display: none;"');
});

Handlebars.registerHelper('generateId', function(options) {

	var context = options.hash.context || this;
	var pssId = options.hash.pssId || context.pss.pssId;
	var targetId = options.hash.targetId || context.bs.bsId;
	var componentName = options.hash.component || context[pop.c.JS_COMPONENT];
	var fixed = options.hash.fixed || context[pop.c.JS_FIXEDID];
	var isIdUnique = options.hash.idUnique || context[pop.c.JS_ISIDUNIQUE];
	var group = options.hash.group;
	var id = options.fn(this);
	var ignorePSRuntimeId = context.ignorePSRuntimeId;
	var domain = context.tls.domain;
	
	// Print also the block URL. Needed to know under what URL to save the session-ids.
	// Set the URL before calling addModule, where it will be needed
	var url = options.hash.addURL ? context.tls.feedback[pop.c.URLPARAM_URL] : '';
	if (url) {
		pop.JSRuntimeManager.setBlockURL(domain, url);
	}

	var generatedId = pop.JSRuntimeManager.addModule(domain, pssId, targetId, componentName, id, group, fixed, isIdUnique, ignorePSRuntimeId);
	var items = [];
	items.push('id="'+generatedId+'"'); 
	items.push('data-componentname="'+componentName+'"');
	
	// For the block, also add the URL on which it was first generated (not initialized... it can be initialized later on)
	if (url) {
		items.push('data-'+pop.c.PARAMS_TOPLEVEL_URL+'="'+url+'"');
		items.push('data-'+pop.c.PARAMS_TOPLEVEL_DOMAIN+'="'+getDomain(url)+'"');
	}
	return new Handlebars.SafeString(items.join(' '));
});

Handlebars.registerHelper('lastGeneratedId', function(options) {

	var context = options.hash.context || this;
	var pssId = options.hash.pssId || context.pss.pssId;
	var targetId = options.hash.targetId || context.bs.bsId;
	var componentName = options.hash.component || context[pop.c.JS_COMPONENT];
	// Allow to set the domain explicitly. Eg: in the decentralized map, the "mapdiv-component" gets drawn on the block-toplevel-domain, not on the data domain
	var domain = options.hash.domain || context.tls.domain;
	var group = options.hash.group;
	return pop.JSRuntimeManager.getLastGeneratedId(domain, pssId, targetId, componentName, group);
});

Handlebars.registerHelper('enterTemplate', function(template, options){
	var context = options.hash.context || this;
	var domain = context.tls.domain;
	var response = pop.Manager.getHtml(domain, template, context);
	return new Handlebars.SafeString(response);
});

/* Comment Leo: taken from http://jsfiddle.net/dain/NRjUb/ */
Handlebars.registerHelper('enterModule', function(prevContext, options){

	// The context can be passed as a param, or if null, use the current one
	var context = options.hash.context || this;
	var componentName = options.hash.component || context[pop.c.JS_COMPONENT];

	// From the prevContext we rescue the topLevel/pageSection/block Settings
	var tls = prevContext.tls;
	var pss = prevContext.pss;
	var bs = prevContext.bs;
	var resolvedObject = prevContext.resolvedObject;
	var resolvedObjectTypeOutputKey = prevContext.resolvedObjectTypeOutputKey;
	var ignorePSRuntimeId = prevContext.ignorePSRuntimeId;
	var feedbackObject = prevContext.feedbackObject;
	
	// The following values, if passed as a param, then these take priority. Otherwise, use them from the previous context
	var typeOutputKey = (typeof options.hash.typeOutputKey != 'undefined') ? options.hash.typeOutputKey : prevContext.typeOutputKey;
	var objectIDs = (typeof options.hash.objectIDs != 'undefined') ? options.hash.objectIDs : prevContext.objectIDs;
	if (jQuery.type(objectIDs) === "array" && !objectIDs.length) {
		objectIDs = null;
	}
	
	// Add all these vars to the context for this component
	var extend = {
		resolvedObject: resolvedObject, 
		resolvedObjectTypeOutputKey: resolvedObjectTypeOutputKey, 
		typeOutputKey: typeOutputKey, 
		objectIDs: objectIDs, 
		tls: tls, 
		pss: pss, 
		bs: bs, 
		ignorePSRuntimeId: ignorePSRuntimeId,
		feedbackObject: feedbackObject, 
	};
	
	var domain = tls.domain;
	var pssId = pss.pssId;
	var psId = pss.psId;
	var bsId = bs.bsId;
	var bId = bs.bId;

	// jQuery.extend(context, pop.Manager.getRuntimeConfiguration(domain, pssId, bsId, componentName));

	// Expand the JS Keys
	// Needed in addition to withModule because it's not always used. Eg: controlbuttongroup.tmpl it iterates directly on components and do enterModule on each, no #with involved
	// Do it after extending with getRuntimeConfiguration, so that these keys are also expanded
	pop.Manager.expandJSKeys(context);

	// DBObjectId could be passed as an array ('objectIDs' is an array), so if it's the case, and it's empty, then nullify it
	var objectID = options.hash.objectID;
	if (jQuery.type(objectID) === "array") {
		
		if (objectID.length) {

			objectID = objectID[0];
		}
		else {

			objectID = null;
			resolvedObject = null;
			extend.resolvedObject = resolvedObject;
		}
	}

	if (options.hash.typeOutputKey && objectID) {

		typeOutputKey = options.hash.typeOutputKey;
		resolvedObject = pop.Manager.getDBObject(domain, typeOutputKey, objectID);
		extend.resolvedObject = resolvedObject;
		extend.resolvedObjectTypeOutputKey = typeOutputKey;
		extend.typeOutputKey = typeOutputKey;
		extend.objectIDs = [objectID];
	}
	else if (options.hash.typeOutputKey && objectIDs) {

		extend.typeOutputKey = options.hash.typeOutputKey;
		extend.objectIDs = objectIDs;
	}
	else if (options.hash.subcomponent && objectID) {

		typeOutputKey = bs.outputKeys[options.hash.subcomponent];
		resolvedObject = pop.Manager.getDBObject(domain, typeOutputKey, objectID);
		extend.resolvedObject = resolvedObject;
		extend.resolvedObjectTypeOutputKey = typeOutputKey;
		extend.typeOutputKey = typeOutputKey;
		extend.objectIDs = [objectID];
	}
	else if (options.hash.subcomponent && objectIDs) {

		typeOutputKey = bs.outputKeys[options.hash.subcomponent];
		extend.typeOutputKey = typeOutputKey;
		extend.objectIDs = objectIDs;
	}
	else if (objectIDs) {

		extend.objectIDs = objectIDs;
	}
	else if (options.hash.typeOutputKey) {

		// If only the typeOutputKey has value, it means the other value passes (objectID or objectIDs) is null
		// So then put everything to null
		extend.typeOutputKey = options.hash.typeOutputKey;
		extend.resolvedObject = null;
		extend.resolvedObjectTypeOutputKey = null;
		extend.objectIDs = null;
	}

	// Make sure the objectIDs are an array
	if (extend.objectIDs) {
		if (jQuery.type(extend.objectIDs) !== "array") {
			extend.objectIDs = [extend.objectIDs];
		}
	}

	if (options.hash.feedbackObject) {

		// Allow to get data from an object from the feedback (eg: feedbackmessage)
		extend.feedbackObject = options.hash.feedbackObject;
	}
	
	jQuery.extend(context, extend);

	var response = pop.Manager.getHtml(domain, componentName, context);

	// Allow PoP Resource Loader to modify the response, to add embedded scripts
	var args = {
		response: response,
		context: context,
		component: componentName,
		domain: domain,
		pssId: pssId,
		psId: psId,
		bsId: bsId,
		bId: bId,
	};
	pop.JSLibraryManager.execute('handlebarsHelperEnterModuleResponse', args);
	response = args.response;

	if (options.hash.unsafe) {
		return response;
	}
	
	return new Handlebars.SafeString(response);
});

Handlebars.registerHelper('withModule', function(context, componentSetingsIdOrPointer, options) {

	if (typeof context == 'undefined' || typeof context[pop.c.JS_SUBCOMPONENTS] == 'undefined') {

		return;
	}

	// Get the component settings id: it is either a pointer to its value in the configuration, or already the value
	var componentSettingsId;
	if (context[pop.c.JS_SUBCOMPONENTOUTPUTNAMES] && context[pop.c.JS_SUBCOMPONENTOUTPUTNAMES][componentSetingsIdOrPointer]) {
		componentSettingsId = context[pop.c.JS_SUBCOMPONENTOUTPUTNAMES][componentSetingsIdOrPointer];
	}
	else {
		componentSettingsId = componentSetingsIdOrPointer;
	}

	if (typeof context[pop.c.JS_SUBCOMPONENTS][componentSettingsId] == 'undefined') {

		return;
	}

	// Go down to the component
	context = context[pop.c.JS_SUBCOMPONENTS][componentSettingsId];

	// Expand the JS Keys
	pop.Manager.expandJSKeys(context);

	// Read all hash options, and add them to the Context
	jQuery.extend(context, options.hash);

	return options.fn(context);
});

Handlebars.registerHelper('withSublevel', function(sublevel, options) {

	var context = options.hash.context || this;

	// Expand the JS Keys. Do it only if it's an object, not an array
	if (jQuery.type(context) === 'object') {
		pop.Manager.expandJSKeys(context);
	}

	return options.fn(context[sublevel]);
});

Handlebars.registerHelper('get', function(db, index, options) {
	
	if (typeof db == 'undefined' || typeof db[index] == 'undefined') {
		return '';
	}
	return db[index];
});

Handlebars.registerHelper('ifget', function(db, index, options) {

	if (typeof db == 'undefined') {
		return '';
	}

	var context = options.hash.context || this;
	var condition = false;
	// Allow to execute a method to check for the condition (eg: is user-id from the object equal to website logged in user?)
	if (options.hash.method) {
		var executed = pop.JSLibraryManager.execute(options.hash.method, {domain: context.tls.domain, input: db[index]});
		jQuery.each(executed, function(index, value) {
			if (value) {
				condition = true;
				return -1;
			}
		});
	}
	else {
		condition = db[index];
	}
	if (condition) {
        return options.fn(this);
    } 
    else {
        return options.inverse(this);
    }
});

Handlebars.registerHelper('withget', function(db, index, options) {

	if (typeof db == 'undefined' || typeof db[index] == 'undefined') {
		return '';
	}

	var context = db[index];

	// Expand the JS Keys. Do it only if it's an object, not an array
	if (jQuery.type(context) === 'object') {
		pop.Manager.expandJSKeys(context);
	}

	return options.fn(context);
});