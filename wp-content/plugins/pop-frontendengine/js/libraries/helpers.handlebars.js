// Handlebars.registerHelper('breaklines', function(text) {
//     text = Handlebars.Utils.escapeExpression(text);
//     text = text.toString();
//     text = text.replace(/(\r\n|\n|\r)/gm, '<br>');
//     return new Handlebars.SafeString(text);
// });

Handlebars.registerHelper('showmore', function(str, options) {

    // len == 0 => No need for showmore
    var len = options.hash.len || 0;

    // Only if at least 100 chars more, so that it doesn't shorten just a tiny bit of text
    if (len > 0 && str.length > len + 100) {

		// If we find "</p>", then we must also hide the bit until that </p>
		var delim = "</p>";
		var total_len = len;
		var morelink = '<a href="#" class="pop-showmore-more">'+M.STRING_MORE+'</a>';
		var lesslink = '<a href="#" class="pop-showmore-less hidden">'+M.STRING_LESS+'</a>';
		var moreless = false, add_morelink = true;
		if ((str.length > total_len) && (str.substr(len).indexOf(delim) > -1)) {

			// Add the moreless links at the end, if only to show the hidden text inside the <p></p>
			moreless = true;
			add_morelink = false;
			
			// Wrap excess characters inside the <p></p> inside a hidden span
			// Add the morelink inside the <p></p> so it shows inline
			str = 
				str.substr(0, len)+
				'<span class="pop-showmore-more-full hidden">'+
				str.substr(len, str.substr(len).indexOf(delim))+
				'</span> '+
				morelink+
				str.substr(len+str.substr(len).indexOf(delim));

			total_len = len + str.substr(len).indexOf(delim) + delim.length;
		}

		if (moreless || (str.length > total_len)) {
			
			// Make sure there still some string left after the operation. If not, then nothing to hide
			var str_end = str.substr(total_len);
			var has_endstr = str_end.trim().length;
			if (moreless || has_endstr) {
				var str_beg = str.substr(0, total_len);
				var str_new = 	
					'<span class="pop-showmore-more-teaser">'+str_beg+'</span>'+ 
					(has_endstr ? '<span class="pop-showmore-more-full hidden">'+str_end+'</span> ' : ' ')+
					(add_morelink ? morelink : '')+
					lesslink;
		        return new Handlebars.SafeString(str_new);     
		    }
	    }
    }
    
    return str;
});

Handlebars.registerHelper('ondate', function(date, options) {

    return new Handlebars.SafeString(M.ONDATE.format(date));     
});

// Handlebars.registerHelper('img', function(imageData, options) {

//     var url = options.hash.url,
//     	title = options.hash.title || "",
//     	alt = options.hash.alt || "",
//     	classs = options.hash.class || "",
//     	ret = "";
        
// 	if (url) { ret += '<a href="'+url+'" title="'+title+'">'; }
// 	ret += '<img src="'+imageData.src+'" width="'+imageData.width+'" height="'+imageData.height+'" alt="'+alt+'" class="'+classs+'">';
// 	if (url) { ret += '</a>'; }

//     return new Handlebars.SafeString ( ret );
// });

Handlebars.registerHelper('addParam', function(url, param, value, options) {

	// Allow for inputs with multiple values
	if (!jQuery.isArray(value)) {
		value = [value];
	}
	jQuery.each(value, function(index, val) {

		url = add_query_arg(param, val, url);
	});

	return url;
});

Handlebars.registerHelper('destroyUrl', function(url, options) {

	return new Handlebars.SafeString(popManager.getDestroyUrl(url));
});

Handlebars.registerHelper('statusLabel', function(status, options) {

	var ret = '<span class="label '+M.STATUS.class[status]+' label-'+status+'">'+M.STATUS.text[status]+'</span>';

	return new Handlebars.SafeString ( ret );
});

Handlebars.registerHelper('labelize', function(strings, label, /*clear,*/ options) {

	var ret = '', extra_class = '';
	if (strings) {
		for (var i = 0; i < strings.length; i++) {
			extra_class = M.LABELIZE_CLASSES[strings[i]] ? M.LABELIZE_CLASSES[strings[i]] : '';
			ret += '<span class="label '+label+' '+extra_class+'">'+strings[i]+'</span> ';
		}
	}

	return new Handlebars.SafeString ( ret );
});

// Handlebars.registerHelper('infoButton', function(id, itemObjectId, options) {

// 	var classs = options.hash.classs || '';
// 	var ret = '<a class="'+classs+'" data-toggle="collapse" href="#'+id+'-'+itemObjectId+'" role="button"><span class="glyphicon glyphicon-info-sign"></span></a>';
// 	return new Handlebars.SafeString ( ret );
// });
// Handlebars.registerHelper('infoCollapse', function(id, itemObjectId, options) {

// 	var content = options.fn(this);
// 	var ret = '<div class="collapse" id="'+id+'-'+itemObjectId+'">'+content+'</div>';
// 	return new Handlebars.SafeString ( ret );
// });

Handlebars.registerHelper('mod', function(lvalue, rvalue, options) {
    if (arguments.length < 3)
        throw new Error("Handlebars Helper equal needs 2 parameters");
        
    offset = options.hash.offset || 0;
            
    if( (lvalue + offset) % rvalue === 0 ) {
        return options.fn(this);
    } else {
        return options.inverse(this);
    }
});

Handlebars.registerHelper('compare', function(lvalue, rvalue, options) {

    if (arguments.length < 3)
        throw new Error("Handlerbars Helper 'compare' needs 2 parameters");

    var operator = options.hash.operator || "==";
    // var context = options.hash.context || this;

    var operators = {
        // function eq: allows to compare a string against a bool, then "true" == true (eg: for the Yes/No Select)
        'eq':       function(l,r) { return l == r || l.toString() == r.toString(); },
        '==':       function(l,r) { return l == r; },
        '===':      function(l,r) { return l === r; },
        '!=':       function(l,r) { return l != r; },
        '<':        function(l,r) { return l < r; },
        '>':        function(l,r) { return l > r; },
        '<=':       function(l,r) { return l <= r; },
        '>=':       function(l,r) { return l >= r; },
        'typeof':   function(l,r) { return typeof l == r; },
        'in':   	function(l,r) { return r.indexOf(l) >= 0; },
        'notin':   	function(l,r) { return r.indexOf(l) == -1; }
    }

    if (!operators[operator])
        throw new Error("Handlerbars Helper 'compare' doesn't know the operator "+operator);

    var result = operators[operator](lvalue,rvalue);

    if( result ) {
        return options.fn(this/*context*/);
    } else {
        return options.inverse(this/*context*/);
    }

});

Handlebars.registerHelper('get', function(db, index, options) {
	if (typeof db == 'undefined' || typeof db[index] == 'undefined') {
		return '';
	}
	return db[index];
});

Handlebars.registerHelper('ifget', function(db, index, options) {

	// if (typeof db == 'undefined' || typeof db[index] == 'undefined') {
	if (typeof db == 'undefined') {
		return '';
	}

	var context = options.hash.context || this;
	var condition = false;
	// Allow to execute a method to check for the condition (eg: is user-id from the object equal to website logged in user?)
	if (options.hash.method) {
		var executed = popJSLibraryManager.execute(options.hash.method, {domain: context.tls.domain, input: db[index]});
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
		popManager.expandJSKeys(context);
	}

	return options.fn(context);
});

Handlebars.registerHelper('iffirstload', function(options) {

	var context = options.hash.context || this;
	var pssId = options.hash.pssId || context.pss.pssId;
	var condition = popManager.isFirstLoad(pssId);
	if (condition) {
        return options.fn(this);
    } 
    else {
        return options.inverse(this);
    }
});

Handlebars.registerHelper('interceptAttr', function(options) {

	var context = options.hash.context || this;
	var intercept = context[M.JS_INTERCEPT] || {};//context.intercept || {};

	// return new Handlebars.SafeString((intercept.type ? ' data-intercept="'+intercept.type+'"' : '') + (intercept.settings ? ' data-intercept-settings="'+intercept.settings+'"' : '') + (intercept.target ? ' target="'+intercept.target+'"' : '')  + (intercept.skipstateupdate ? ' data-intercept-skipstateupdate="true"' : '') + ' style="display: none;"');
	return new Handlebars.SafeString((intercept[M.JS_TYPE] ? ' data-intercept="'+intercept[M.JS_TYPE]+'"' : '') + (intercept.settings ? ' data-intercept-settings="'+intercept[M.JS_SETTINGS]+'"' : '') + (intercept[M.JS_TARGET] ? ' target="'+intercept[M.JS_TARGET]+'"' : '')  + (intercept[M.JS_SKIPSTATEUPDATE] ? ' data-intercept-skipstateupdate="true"' : '') + ' style="display: none;"');
});

Handlebars.registerHelper('generateId', function(options) {

	var context = options.hash.context || this;
	var pssId = options.hash.pssId || context.pss.pssId;
	var targetId = options.hash.targetId || context.bs.bsId;
	var template = options.hash.template || context[M.JS_TEMPLATE];//context.template;
	var fixed = options.hash.fixed || context[M.JS_FIXEDID];//context['fixed-id'];
	var isIdUnique = options.hash.idUnique || context[M.JS_ISIDUNIQUE];//context['is-id-unique'];
	var group = options.hash.group;
	var id = options.fn(this);
	var ignorePSRuntimeId = context.ignorePSRuntimeId;
	var domain = context.tls.domain;
	
	// Print also the block URL. Needed to know under what URL to save the session-ids.
	// Set the URL before calling addTemplateId, where it will be needed
	var url = options.hash.addURL ? context.tls.feedback[M.URLPARAM_URL] : '';
	if (url) {
		popJSRuntimeManager.setBlockURL(url);
	}

	var generatedId = popJSRuntimeManager.addTemplateId(domain, pssId, targetId, template, id, group, fixed, isIdUnique, ignorePSRuntimeId);
	var items = [];
	items.push('id="'+generatedId+'"'); 
	items.push('data-templateid="'+template+'"');
	
	// For the block, also add the URL on which it was first generated (not initialized... it can be initialized later on)
	if (url) {
		items.push('data-'+M.PARAMS_TOPLEVEL_URL+'="'+url+'"');
		items.push('data-'+M.PARAMS_TOPLEVEL_DOMAIN+'="'+getDomain(url)+'"');
	}
	return new Handlebars.SafeString(items.join(' '));
});
Handlebars.registerHelper('lastGeneratedId', function(options) {

	var context = options.hash.context || this;
	var pssId = options.hash.pssId || context.pss.pssId;
	var targetId = options.hash.targetId || context.bs.bsId;
	var template = options.hash.template || context[M.JS_TEMPLATE];//context.template;
	// Allow to set the domain explicitly. Eg: in the decentralized map, the "mapdiv-template" gets drawn on the block-toplevel-domain, not on the data domain
	var domain = options.hash.domain || context.tls.domain;
	var group = options.hash.group;
	return popJSRuntimeManager.getLastGeneratedId(domain, pssId, targetId, template, group);
});

Handlebars.registerHelper('applyLightTemplate', function(template, options){
	var context = options.hash.context || this;
	// var tls = context.tls;
	// var domain = tls.domain;
	var response = popManager.getHtml(/*domain, */template, context);
	return new Handlebars.SafeString(response);
});

// Taken from http://stackoverflow.com/questions/10232574/handlebars-js-parse-object-instead-of-object-object
Handlebars.registerHelper('json', function(context) {
    return JSON.stringify(context);
});

/* Comment Leo: taken from http://jsfiddle.net/dain/NRjUb/ */
Handlebars.registerHelper('enterModule', function(prevContext, options){

	// The context can be passed as a param, or if null, use the current one
	var context = options.hash.context || this;
	var templateName = options.hash.template || context[M.JS_TEMPLATE];//context.template;

	// From the prevContext we rescue the topLevel/pageSection/block Settings
	var tls = prevContext.tls;
	var pss = prevContext.pss;
	var bs = prevContext.bs;
	var itemObject = prevContext.itemObject;
	var itemObjectDBKey = prevContext.itemObjectDBKey;
	var ignorePSRuntimeId = prevContext.ignorePSRuntimeId;
	
	// The following values, if passed as a param, then these take priority. Otherwise, use them from the previous context
	var itemDBKey = (typeof options.hash.itemDBKey != 'undefined') ? options.hash.itemDBKey : prevContext.itemDBKey;
	var items = (typeof options.hash.items != 'undefined') ? options.hash.items : prevContext.items;

	// Add all these vars to the context for this template
	var extend = {itemObject: itemObject, itemObjectDBKey: itemObjectDBKey, itemDBKey: itemDBKey, items: items, tls: tls, pss: pss, bs: bs, ignorePSRuntimeId: ignorePSRuntimeId};
	
	var domain = tls.domain;
	var pssId = pss.pssId;
	var psId = pss.psId;
	var bsId = bs.bsId;
	var bId = bs.bId;

	jQuery.extend(context, popManager.getRuntimeConfiguration(domain, pssId, bsId, templateName));

	// Expand the JS Keys
	// Needed in addition to withBlock/withModule because it's not always used. Eg: controlbuttongroup.tmpl it iterates directly on modules and do enterModule on each, no #with involved
	// Do it after extending with getRuntimeConfiguration, so that these keys are also expanded
	popManager.expandJSKeys(context);

	// ItemObjectId could be passed as an array ('dataset' is an array), so if it's the case, and it's empty, then nullify it
	var itemObjectId = options.hash.itemObjectId;
	if (jQuery.type(itemObjectId) === "array") {
		
		if (itemObjectId.length) {

			itemObjectId = itemObjectId[0];
		}
		else {

			itemObjectId = null;
			itemObject = null;
			extend.itemObject = itemObject;
		}
	}

	if (options.hash.itemDBKey && itemObjectId) {

		itemDBKey = options.hash.itemDBKey;
		itemObject = popManager.getItemObject(domain, itemDBKey, itemObjectId);
		extend.itemObject = itemObject;
		extend.itemObjectDBKey = itemDBKey;
		extend.itemDBKey = itemDBKey;
		extend.items = [itemObjectId];
	}
	else if (options.hash.itemDBKey && options.hash.items) {

		extend.itemDBKey = options.hash.itemDBKey;
		extend.items = options.hash.items;
	}
	else if (options.hash.subcomponent && itemObjectId) {

		itemDBKey = bs['db-keys'][M.JS_SUBCOMPONENTS][options.hash.subcomponent]['db-key'];//bs['db-keys'].subcomponents[options.hash.subcomponent]['db-key'];
		itemObject = popManager.getItemObject(domain, itemDBKey, itemObjectId);
		extend.itemObject = itemObject;
		extend.itemObjectDBKey = itemDBKey;
		extend.itemDBKey = itemDBKey;
		extend.items = [itemObjectId];
	}
	else if (options.hash.subcomponent && options.hash.items) {

		itemDBKey = bs['db-keys'][M.JS_SUBCOMPONENTS][options.hash.subcomponent]['db-key'];//bs['db-keys'].subcomponents[options.hash.subcomponent]['db-key'];
		extend.itemDBKey = itemDBKey;
		extend.items = options.hash.items;
	}
	else if (options.hash.items) {

		extend.items = options.hash.items;
	}
	else if (options.hash.itemDBKey) {

		// If only the itemDBKey has value, it means the other value passes (itemObjectId or items) is null
		// So then put everything to null
		extend.itemDBKey = options.hash.itemDBKey;
		extend.itemObject = null;
		extend.itemObjectDBKey = null;
		extend.items = null;
	}

	// Make sure the items are an array
	if (extend.items) {
		if (jQuery.type(extend.items) !== "array") {
			extend.items = [extend.items];
		}
	}

	if (options.hash['feedback-msg']) {
		extend['feedback-msg'] = options.hash['feedback-msg'];
	}

	// Override the Configuration with runtime values? (Eg: value inside formcomponents using DB elements)
	var overrideFields = [], staticStrReplace = [], runtimeStrReplace = [];
	if (context[M.JS_MODULEOPTIONS]/*context.moduleoptions*/) {

		overrideFields = context[M.JS_MODULEOPTIONS][M.JS_OVERRIDEFROMITEMOBJECT] || [];//context.moduleoptions['override-from-itemobject'] || [];
		staticStrReplace = context[M.JS_MODULEOPTIONS][M.JS_REPLACESTRFROMITEMOBJECT] || [];//context.moduleoptions['replacestr-from-itemobject'] || [];
	}
	if (context[M.JS_RUNTIMEMODULEOPTIONS]/*context['runtime-moduleoptions']*/) {

		runtimeStrReplace = context[M.JS_RUNTIMEMODULEOPTIONS][M.JS_REPLACESTRFROMITEMOBJECT] || [];//context['runtime-moduleoptions']['replacestr-from-itemobject'] || [];
	}
	if (overrideFields.length) {

		popManager.overrideFromItemObject(itemObject, extend, overrideFields);
	}
	var strReplace = staticStrReplace.concat(runtimeStrReplace);
	if (strReplace.length) {

		popManager.replaceFromItemObject(domain, pssId, bsId, templateName, itemObject, extend, strReplace);
	}


	// Needed for the BlockGroups
	var parentContext = options.hash.parentContext || {};
	if (parentContext) {
		extend['parent-context'] = parentContext;

		var rootContext = parentContext;
		while (rootContext['parent-context']) {
			rootContext = rootContext['parent-context'];
		}
		extend['root-context'] = rootContext;
	}
	
	jQuery.extend(context, extend);

	var response = popManager.getHtml(/*domain, */templateName, context);

	if (options.hash.unsafe) {
		return response;
	}
	
	return new Handlebars.SafeString(response);
});

function getMultiLayoutKey(itemDBKey, itemObject) {

	// Define in the jquery_constants what fields form the key, getting the field values from the itemObject,
	// to identify the layout
	var keyFields = M.MULTILAYOUT_KEYFIELDS[itemDBKey] || [];
	
	if (!keyFields.length) {
		return 'default';
	}

	var keyParts = [];
	jQuery.each(keyFields, function(index, field) {

		keyParts.push(itemObject[field]);
	});
	return keyParts.join('-');
}
Handlebars.registerHelper('withLayout', function(itemDBKey, itemObjectId, multipleLayouts, context, options) {

	var tls = context.tls;
	var domain = tls.domain;

	// Obtain the key composed as: 'post_type'-'cat'
	var itemObject = popManager.getItemObject(domain, itemDBKey, itemObjectId);
	
	// keys 'post-type' and 'cat' must always be there!
	var key = getMultiLayoutKey(itemDBKey, itemObject);
	
	// Fetch the layout for that particular configuration
	var layout = multipleLayouts[key];
	if (!layout) {
		layout = multipleLayouts['default'];
	}

	// If still no layout, then do nothing
	if (!layout) {
		return '';
	}

	// Render the content from this layout
	var layoutContext = context[M.JS_MODULES][layout];//context['modules'][layout];

	// Add itemDBKey and itemObjectId back into the context
	jQuery.extend(layoutContext, {itemDBKey: itemDBKey, itemObjectId: itemObjectId});

	// Expand the JS Keys
	popManager.expandJSKeys(layoutContext);

	return options.fn(layoutContext);
});
Handlebars.registerHelper('layoutLabel', function(itemDBKey, itemObject, options) {

	// Obtain the key composed as: 'post_type'-'cat'
	// keys 'post-type' and 'cat' must always be there!
	// var key = itemObject['post-type']+'-'+itemObject['cat'];

	var key = getMultiLayoutKey(itemDBKey, itemObject);
	
	return M.MULTILAYOUT_LABELS[key] || '';
});

Handlebars.registerHelper('withBlock', function(context, blockSettingsId, options) {

	// if (typeof context == 'undefined' || typeof context['modules'] == 'undefined' || typeof context['modules'][blockSettingsId] == 'undefined') {
	if (typeof context == 'undefined' || typeof context[M.JS_MODULES] == 'undefined' || typeof context[M.JS_MODULES][blockSettingsId] == 'undefined') {

		return;
	}

	// Go down to the module
	context = context[M.JS_MODULES][blockSettingsId];//context['modules'][blockSettingsId];

	// Expand the JS Keys
	popManager.expandJSKeys(context);

	return options.fn(context);
});

Handlebars.registerHelper('withModule', function(context, module, options) {

	// if (typeof context == 'undefined' || typeof context['settings-ids'] == 'undefined' || typeof context['settings-ids'][module] == 'undefined') {
	if (typeof context == 'undefined' || typeof context[M.JS_SETTINGSIDS] == 'undefined' || typeof context[M.JS_SETTINGSIDS][module] == 'undefined') {

		return;
	}

	// Get the module settings id from the configuration
	var moduleSettingsId = context[M.JS_SETTINGSIDS][module];//context['settings-ids'][module];

	// if (typeof context['modules'] == 'undefined' || typeof context['modules'][moduleSettingsId] == 'undefined') {
	if (typeof context[M.JS_MODULES] == 'undefined' || typeof context[M.JS_MODULES][moduleSettingsId] == 'undefined') {

		return;
	}

	// Go down to the module
	// context = context['modules'][moduleSettingsId];
	context = context[M.JS_MODULES][moduleSettingsId];

	// Expand the JS Keys
	popManager.expandJSKeys(context);

	// Read all hash options, and add them to the Context
	jQuery.extend(context, options.hash);

	return options.fn(context);
});

Handlebars.registerHelper('withSublevel', function(sublevel, options) {

	var context = options.hash.context || this;

	// Expand the JS Keys. Do it only if it's an object, not an array
	if (jQuery.type(context) === 'object') {
		popManager.expandJSKeys(context);
	}

	return options.fn(context[sublevel]);
});

Handlebars.registerHelper('withItemObject', function(itemDBKey, itemObjectId, options) {

	var context = options.hash.context || this;
	var tls = context.tls;
	var domain = tls.domain;

	// Replace the context with only the itemObject
	var context = popManager.getItemObject(domain, itemDBKey, itemObjectId);
	
	return options.fn(context);
});


// Taken from: https://github.com/raDiesle/Handlebars.js-helpers-collection#JOIN
Handlebars.registerHelper('join', function(items, block) {
    var delimiter = block.hash.delimiter || ",", 
        start = start = block.hash.start || 0, 
        len = items ? items.length : 0,
        end = block.hash.end || len,
        out = "";

        if(end > len) end = len;

    if ('function' === typeof block) {
        for (i = start; i < end; i++) {
            if (i > start) out += delimiter;
            if('string' === typeof items[i])
                out += items[i];
            else
                out += block(items[i]);
        }
        return out;
    } else { 
        return [].concat(items).slice(start, end).join(delimiter);
    }
});

// Handlebars.registerHelper('notificationActionIcon', function(action, options) {

//     if (M.NOTIFICATION_ACTION_ICONS[action]) {
//         return new Handlebars.SafeString(M.NOTIFICATION_ACTION_ICONS[action]);
//     }
    
//     return '';
// });