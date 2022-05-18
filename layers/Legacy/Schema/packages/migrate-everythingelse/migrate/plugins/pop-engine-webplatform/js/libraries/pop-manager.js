"use strict";
(function($){
window.pop.Manager = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	
	mergingPromise : false,
	runtimeMemory : {
		general: {},
		url: {}
	},
	// // Used to override the dbobjectids/feedback/params when resetting the block
	// initialBlockMemory : {},
	firstLoad : {},
	documentTitle : null, // We keep a copy of the document title, so we can add the notification number in it
	domains : {}, // Keep a list of all domains, so we know when to initialize them
	loaded : false, // Flag indicating if the document has loaded, needed for operations that need to wait until then to execute

	//-------------------------------------------------
	// Whitelisted functions here
	//-------------------------------------------------

	getData : function(domain) {

		var that = this;
		return window.pop.Data[domain] || {};
	},

	getRequestMeta : function(domain) {

		var that = this;
		return that.getData(domain).requestmeta || {};
	},

	getSessionMeta : function(domain) {

		var that = this;
		return that.getData(domain).sessionmeta || {};
	},

	getSiteMeta : function(domain) {

		var that = this;
		return that.getData(domain).sitemeta || {};
	},



	//-------------------------------------------------
	// Previous shit
	//-------------------------------------------------

	getStoreData : function(domain) {

		var that = this;
		return pop.DataStore.store[domain] || {};
	},
	getStatelessData : function(domain) {

		var that = this;
		return that.getStoreData(domain).statelessdata || {};
	},
	getStatefulData : function(domain, url) {

		var that = this;
		url = "temporary-hack";
		return that.getStoreData(domain).statefuldata[url] || {};
	},
	getCombinedStateData : function(domain, url) {

		var that = this;
		url = "temporary-hack";
		return that.getStoreData(domain).combinedstatedata[url] || {};
	},
	getDatabases : function(domain) {

		var that = this;
		return pop.DataStore.store[domain].databases || {};
	},
	getDatabase : function(domain) {

		var that = this;
		return that.getDatabases(domain).database || {};
	},
	getUserDatabase : function(domain) {

		var that = this;
		return that.getDatabases(domain).userdatabase || {};
	},
	getTemplates : function(domain) {
	
		var that = this;
		return that.getStatelessData(domain).settings['templates'] || {};
	},
	initDomainVars : function(domain) {
	
		var that = this;
		pop.DataStore.store[domain] = {};
		// pop.DataStore.store[domain] = {
		// 	statelessdata : {
		// 		settings: {
		// 			configuration: {},
		// 			'js-settings': {},
		// 			jsmethods: {
		// 				pagesection: {},
		// 				block: {},
		// 			},
		// 			'modules-cbs': {},
		// 			'modules-paths': {},
		// 			'db-keys': {},
		// 		},
		// 	},
		// 	statefuldata : {
		// 		settings: {
		// 			configuration: {},
		// 			'js-settings': {},
		// 		},
		// 		dbobjectids: {},
		// 		feedback: {
		// 			block: {},
		// 			pagesection: {},
		// 			toplevel: {}
		// 		},
		// 		querystate: {
		// 			sharedbydomains: {},
		// 			uniquetodomain: {},
		// 		},
		// 	},
		// 	databases : {
		// 		database : {},
		// 		userdatabase : {},
		// 	},
		// };
	},
	clearUserDatabase : function(domain) {
	
		var that = this;

		// Executed when the logged-in user logs out
		// pop.DataStore.clearUserDatabase(domain);
		pop.DataStore.store[domain].databases.userdatabase = {};
	},
	// integrateDatabases : function(domain, response) {
	
	// 	var that = this;
	// 	if (response.databases) {
	// 		$.extend(true, pop.DataStore.store[domain].databases, response.databases);
	// 	}
	// 	// that.integrateDatabase(that.getDatabase(domain), response.databases.database);
	// 	// that.integrateDatabase(that.getUserDatabase(domain), response.databases.userdatabase);
	// },
	// initTopLevelJson : function(domain) {
	
	// 	var that = this;

	// 	// Initialize Settings
	// 	// var jsonHtml = $('#'+pop.PageSectionManager.getTopLevelSettingsId());
	// 	// var json = JSON.parse(jsonHtml.html());
	// 	var json = window.pop.Data[domain];

	// 	// // Comment Leo 30/10/2017: add a hook to fill the settings values from pop-runtimecontent .js files
	// 	// $(document).triggerHandler('initTopLevelJson', [json]);

	// 	// Databases and stateless data can be integrated straight
	// 	var storeData = that.getStoreData(domain);
	// 	storeData.databases = json.databases || {};
	// 	storeData.statelessdata = json.statelessdata || {};

	// 	// Stateful data is to be integrated under the corresponding URL
	// 	var url = json.componentdata.stateful.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pop.c.URLPARAM_URL];
	// 	url = "temporary-hack";
	// 	storeData.statefuldata = {};
	// 	storeData.statefuldata[url] = json.componentdata.stateful || {};
	// 	storeData.combinedstatedata = {};
	// 	storeData.combinedstatedata[url] = $.extend(true, {}, storeData.statelessdata, storeData.statefuldata[url]);

	// 	that.addInitialBlockMemory(json);
	// },
	// getInitialBlockMemory : function(url) {

	// 	var that = this;

	// 	// If the url is the first one loaded, then the initial memory is stored there.
	// 	// Otherwise, there is a bug when loading https://www.mesym.com/en/log-in/:
	// 	// The memory will be loaded under this url, but immediately it will fetch /loaders/initial-frames?target=main,
	// 	// and it will fetch /log-in again. However, the Notifications will not be there, since it's loaded only on loadingframe(),
	// 	// and it will produce a JS error when integrating the initialMemory into the memory (restoreinitial)
	// 	if (url == pop.c.INITIAL_URL) {
	// 		return that.initialBlockMemory[url];
	// 	}

	// 	return that.initialBlockMemory[url] || {};
	// },
	// addInitialBlockMemory : function(response) {

	// 	var that = this;
	// 	var url = response.statefuldata.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pop.c.URLPARAM_URL];
	// 	if (!that.initialBlockMemory[url]) {
	// 		that.initialBlockMemory[url] = {
	// 			dbobjectids: {},
	// 			feedback: {
	// 				block: {},
	// 			},
	// 			querystate: {
	// 				sharedbydomains: {},
	// 				uniquetodomain: {},
	// 			},
	// 			// runtimesettings: {
	// 			// 	configuration: {}
	// 			// }
	// 		};
	// 	}
	// 	var initialMemory = that.initialBlockMemory[url];
	// 	$.each(response.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT], function(pssId, rpsConfiguration) {

	// 		if (!initialMemory.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT]) {
	// 			initialMemory.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT] = {};
	// 			initialMemory.querystate.uniquetodomain[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT] = {};
	// 			initialMemory.dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT] = {};
	// 			initialMemory.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT] = {};
	// 		}
	// 		if (!initialMemory.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]) {
	// 			initialMemory.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = {};
	// 			initialMemory.querystate.uniquetodomain[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = {};
	// 			// initialMemory.runtimesettings.configuration[pssId] = {};
	// 			initialMemory.dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = {};
	// 			initialMemory.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = {};
	// 		}
	// 		$.extend(initialMemory.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId], response.statefuldata.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]);
	// 		$.extend(initialMemory.querystate.uniquetodomain[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId], response.statefuldata.querystate.uniquetodomain[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]);
	// 		$.extend(initialMemory.dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId], response.statefuldata.dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]);
	// 		$.extend(initialMemory.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId], response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]);
	// 	});
	// },
	// getRuntimeMemory : function(domain, pageSection, target, options) {

	// 	var that = this;

	// 	options = options || {};

	// 	// To tell if it's general or url, check for data-paramscope in the pageSection
	// 	var pageSectionPage = that.getPageSectionPage(target);
	// 	var scope = pageSectionPage.data('paramsscope');
	// 	if (scope == pop.c.SETTINGS_PARAMSSCOPE_URL) {
			
	// 		var url = options.url || that.getTopLevelFeedback(domain)[pop.c.URLPARAM_URL];
	// 		if (!that.runtimeMemory.url[url]) {
			
	// 			// Create a new instance for this URL
	// 			that.runtimeMemory.url[url] = {};
	// 		}

	// 		// Save which url the params for this target is under
	// 		target.data(pop.c.PARAMS_PARAMSSCOPE_URL, url);

	// 		return that.runtimeMemory.url[url];
	// 	}

	// 	// The entry can be created either under 'general' for all pageSections who are static, ie: they don't bring any new content with the url (eg: top-frame)
	// 	// or under 'url' for the ones who depend on a given url, eg: main
	// 	return that.runtimeMemory.general;
	// },
	// newRuntimeMemoryPage : function(domain, pageSection, target, options) {

	// 	var that = this;
		
	// 	// Take the URL from the topLevelFeedback and not from window.location.href when creating a new one.
	// 	// this is so that we don't need to update the browser url, which sometimes we don't want, eg: when preloading Add Comment in the addon pageSection
	// 	options = options || {};
	// 	if (!options.url) {
	// 		var tlFeedback = that.getTopLevelFeedback(domain);
	// 		options.url = tlFeedback[pop.c.URLPARAM_URL];
	// 	}
	// 	var mempage = that.getRuntimeMemory(domain, pageSection, target, options);

	// 	var pssId = that.getSettingsId(pageSection);
	// 	var targetId = that.getSettingsId(target);

	// 	if (!mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT]) {
	// 		mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT] = {};
	// 	}
	// 	if (!mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]) {
	// 		mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = {};
	// 	}
	// 	mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId] = {
	// 		querystate: {
	// 			sharedbydomains: {},
	// 			uniquetodomain: {},
	// 		},
	// 		id: null
	// 	};

	// 	return mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId];
	// },
	// deleteRuntimeMemoryPage : function(domain, pageSection, target, options) {

	// 	var that = this;
	// 	var mempage = that.getRuntimeMemory(domain, pageSection, target, options);

	// 	var pssId = that.getSettingsId(pageSection);
	// 	var targetId = that.getSettingsId(target);

	// 	if (mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]) {
	// 		delete mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId];

	// 		if ($.isEmptyObject(mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId])) {
	// 			delete mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId];
	// 		}
	// 	}
	// },
	getRuntimeMemoryPage : function(pageSection, targetOrId, options) {

		var that = this;
		return {};

		// // In function executeFetchBlock will get a response with the settingsId of the block, not the block. 
		// // In that case, we can't do block.data('paramsscope-url'), so instead we pass the url in the options
		// options = options || {};

		// // if the target has paramsscope-url set then look for its params under that url key
		// var mempage, url;
		// if (options.url) {
		// 	url = options.url;
		// }
		// else if ($.type(targetOrId) == 'object') {
		// 	var target = targetOrId;
		// 	url = that.getTargetParamsScopeURL(target);//target.data(pop.c.PARAMS_PARAMSSCOPE_URL);
		// }
		// if (url) {
			
		// 	mempage = that.runtimeMemory.url[url];
		// }
		// else {

		// 	// Otherwise, it's general
		// 	mempage = that.runtimeMemory.general;
		// }

		// var pssId = that.getSettingsId(pageSection);
		// var targetId = that.getSettingsId(targetOrId);

		// // If this doesn't exist, it's because the tab was closed and we are still retrieving the mempage later on (eg: a fetch-more had been invoked and the response came after tab was closed)
		// // Since this behavior is not right, then just thrown an exception
		// if (!mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT] || !mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || !mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId]) {

		// 	var error = "Mempage not available";
		// 	if (url) {
		// 		error += " for url " + url;
		// 	}
		// 	throw new Error(error);
		// }

		// return mempage[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId];
	},

	isFirstLoad : function(pageSection) {
		
		var that = this;
		
		return that.firstLoad[that.getSettingsId(pageSection)];
	},

	init : function() {
	
		var that = this;

		pop.JSLibraryManager.execute('init', {});

		// Step 0: The HTML has been loaded, now execute JS
		$(document.body).removeClass('pop-loadinghtml');
		
		// Comment Leo 22/08/2016: when is_search_engine(), there is no #toplevel, so do nothing
		// if ($('#'+pop.PageSectionManager.getTopLevelSettingsId()).length) {

		var domain = pop.c.HOME_DOMAIN;

		// Make sure the localStorage has no stale entries
		that.initLocalStorage();

		// Initialize the domain: it must be done before initializing the topLevelJSON, since this latter one must save info under the domain
		that.initDomain(domain);

		// Initialize Settings, Feedback and Data
		// that.initTopLevelJson(domain);

		// Initialize the document
		that.initDocument(domain);

		// Keep the pageSection DOM elems
		// var pageSections = [];

		var data = window.pop.Data[domain];
		var requestMeta = data.requestmeta; 
		var entryModule = requestMeta.entrymodule;
		// var topLevelFeedback = that.getTopLevelFeedback(domain);
		var url = requestMeta.url;

		// Comment Leo 08/11/2017: these variables will be used later on, inside `window.addEventListener('load', function() ...)`
		// however they must be assigned right now! Because before we reach there, we will initialize blocks, and these may bring their own
		// backgroundLoad, and modifying the topLevelFeedback, so by the time it reaches down, it will execute once again all these extra backgroundLoads
		// These happens in sukipop.com when initializing the main feed, that block calls initDomain which calls /initialize-domain/ for all the external domains,
		// and these bring their own /initial-frames/ URL to load. And it happens before reaching down because it's all cached in the localStorage
		// As a consequence, clicking on Add Comment will open 2 or 3 tabs
		var silent_document = requestMeta.silentdocument;
		var background_load_urls = requestMeta.backgroundloadurls;

		// Set the URL for the 'session-ids'
		// pop.JSRuntimeManager.setPageSectionURL(url);

		// Comment Leo 01/12/2016: Split the logic below into 2: first render all the pageSections (that.renderPageSection(pageSection)),
		// and then execute all JS on them (that.pageSectionInitialized(pageSection)), and in between remove the "loading" screen
		// this way, the first paint is much faster, for a better user experience
		// Step 0: initialize the pageSection
		// $.each(that.getCombinedStateData(domain).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT], function(pssId, psConfiguration) {
		// // $.each(that.getStatelessData(domain).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT], function(pssId, psConfiguration) {

		// 	// var psId = psConfiguration[pop.c.JS_FRONTENDID];
		// 	var psId = psConfiguration['frontend-id'];
		// 	var pageSection = $('#'+psId);

		// 	// Before anything: add the moduleoutputname to the div (so we can access below getPageSectionId)
		// 	pageSection.attr('data-moduleoutputname', pssId);
		// 	pageSection.addClass('pop-pagesection');

		// 	that.initPageSectionSettings(domain, pageSection, psConfiguration);

		// 	// Insert into the Runtime to generate the ID
		// 	that.addPageSectionIds(domain, pageSection, psConfiguration[pop.c.JS_COMPONENT]);

		// 	// Allow plugins to catch events for the rendering
		// 	that.initPageSection(domain, pageSection);

		// 	pageSections.push(pageSection);
		// });

		// Step 1: render the pageSection
		var options = {
			'init': true,
			'session-ids-url': url
		}

		// Progressive Booting: if enabled, split the process of initializing JS into 2: 'critical' immediately, and 'noncritical' after the page loads
		var priority = null;
		if (pop.c.USE_PROGRESSIVEBOOTING) {
			priority = pop.c.PROGRESSIVEBOOTING.CRITICAL;
		}
		
		// // Comment Leo 12/09/2017: Gotta use the Deferred here already, because the rendered pageSection
		// // may trigger a backgroundLoad of a page which is already cached (localStorage/Service Workers),
		// // so the response will come back immediately and try to render itself, for which
		// // it will modify the context, making the following pageSections (pagetabs, sideinfo) to be rendered unproperly
		// var dfd = $.Deferred();
		// that.mergingPromise = dfd.promise();
		// $.each(pageSections, function(index, pageSection) {

		// 	// This will also initialize the 'critical' JS
		// 	// Here it will not delete the session-ids yet, so they can be accessed by 'noncritical'
		// 	that.renderPageSection(domain, pageSection, priority, options);
		// });
		// dfd.resolve();

		// Implement:
		console.log('that.render(domain, priority, options);');

		// Step 2: remove the "loading" screen
		$(document.body).removeClass('pop-loadingframe');

		// Step 3: execute JS
		// $.each(pageSections, function(index, pageSection) {

		// 	that.pageSectionInitialized(domain, pageSection);
		// });
		// Implement:
		console.log('that.initialized(domain);');

		if (!silent_document) {
			
			// Update URL: it will remove the unwanted items, eg: mode=embed (so that if the user clicks on the newWindow btn, it opens properly)
			pop.BrowserHistory.replaceState(url);
			that.updateDocument(domain);
		}

		// Only critical assets will be executed here, because 'documentInitialized' is part of the resourceloader-mapping starting from pop.Manager.init(),
		// so it is not possible to have "defer" scripts with function documentInitialized
		that.documentInitialized(domain);

		// Step 4: remove the "loading" screen
		$(document.body).removeClass('pop-loadingjs');

		// If the server requested to extra load more URLs
		// Comment Leo 28/12/2017: backgroundLoad will execute later, after the page has loaded
		// This way, we bring forward the "Time to interactive", for all those functionalities that the user can see/is waiting for
		// The backgroundLoad can be prioritized lower (the /loggedinuser-data/ page, brought thru backgroundLoad, would be nice to bring immediately, but is a tradeoff; application views can certainly wait)
		// Comment Leo 15/04/2018: the backgroundLoad pages are loaded through GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN
		// that.backgroundLoad(pop.c.BACKGROUND_LOAD); // Initialization of modules (eg: Modals, Addons)
		that.backgroundLoad(background_load_urls); // Data to be loaded from server (eg: forceserverload_fields)

		// Progressive booting: Execute the non-critical JS functions
		window.addEventListener('load', function() {

			that.documentLoaded(domain);

			// If Progressive Booting is enabled...
			if (pop.c.USE_PROGRESSIVEBOOTING) {

				// Initialize the 'noncritical' JS
				// $.each(pageSections, function(index, pageSection) {

				// 	// Here it will delete the session-ids
				// 	that.initPageSectionBranches(domain, pageSection, pop.c.PROGRESSIVEBOOTING.NONCRITICAL, options);
				// });

				// Implement:
				console.log('that.initJS(domain, pop.c.PROGRESSIVEBOOTING.NONCRITICAL, options);');
			}
		});
		// }
	},

	maybeModifyURL : function(url) {
	
		var that = this;

		var args = {
			url: url
		};
		pop.JSLibraryManager.execute('maybeModifyURL', args);

		return args.url;
	},

	expandJSKeys : function(context) {
	
		var that = this;

		// In order to save file size, context keys can be compressed, eg: 'modules' => 'ms', 'module' => 'm'. However they might be referenced with their full name
		// in .tmpl files, so reconstruct the full name in the context duplicating these entries
		if (context && pop.c.COMPACT_RESPONSE_JSON_KEYS) {

			// Hardcoding always 'modules' allows us to reference this key, with certainty of its name, in the .tmpl files
			if (context[pop.c.JS_SUBCOMPONENTS]) {
				context.modules = context[pop.c.JS_SUBCOMPONENTS];
			}
			if (context[pop.c.JS_COMPONENT]) {
				context['module'] = context[pop.c.JS_COMPONENT];
			}
			if (context[pop.c.JS_TEMPLATE]) {
				context['template'] = context[pop.c.JS_TEMPLATE];
			}
			if (context[pop.c.JS_COMPONENTOUTPUTNAME]) {
				context['moduleoutputname'] = context[pop.c.JS_COMPONENTOUTPUTNAME];
			}
			if (context[pop.c.JS_SUBCOMPONENTOUTPUTNAMES]) {
				context['submoduleoutputnames'] = context[pop.c.JS_SUBCOMPONENTOUTPUTNAMES];
			}
			if (context[pop.c.JS_TEMPLATES]) {
				context['templates'] = context[pop.c.JS_TEMPLATES];
			}
			if (context[pop.c.JS_INTERCEPT]) {
				context.intercept = context[pop.c.JS_INTERCEPT];
			}
			if (context[pop.c.JS_BLOCKCOMPONENTOUTPUTNAMES]) {
				context['block-submoduleoutputnames'] = context[pop.c.JS_BLOCKCOMPONENTOUTPUTNAMES];
			}

			// Params
			if (context[pop.c.JS_PARAMS]) {
				context.params = context[pop.c.JS_PARAMS];
			}
			if (context[pop.c.JS_DBOBJECTPARAMS]) {
				context['dbobject-params'] = context[pop.c.JS_DBOBJECTPARAMS];
			}
			if (context[pop.c.JS_PREVIOUSCOMPONENTSIDS]) {
				context['previousmodules-ids'] = context[pop.c.JS_PREVIOUSCOMPONENTSIDS];
			}

			// Appendable
			if (context[pop.c.JS_APPENDABLE]) {
				context.appendable = context[pop.c.JS_APPENDABLE];
			}

			// Frequently used keys in many different templates
			if (context[pop.c.JS_CLASS]) {
				context.class = context[pop.c.JS_CLASS];
			}
			if (context[pop.c.JS_CLASSES]) {
				context.classes = context[pop.c.JS_CLASSES];

				if (context[pop.c.JS_CLASSES][pop.c.JS_APPENDABLE]) {
					context.classes.appendable = context[pop.c.JS_CLASSES][pop.c.JS_APPENDABLE];
				}
			}
			if (context[pop.c.JS_STYLE]) {
				context.style = context[pop.c.JS_STYLE];
			}
			if (context[pop.c.JS_STYLES]) {
				context.styles = context[pop.c.JS_STYLES];
			}
			if (context[pop.c.JS_TITLES]) {
				context.titles = context[pop.c.JS_TITLES];
			}
			if (context[pop.c.JS_DESCRIPTION]) {
				context.description = context[pop.c.JS_DESCRIPTION];
			}
			if (context[pop.c.JS_INTERCEPTURLS]) {
				context['intercept-urls'] = context[pop.c.JS_INTERCEPTURLS];
				
				if (context[pop.c.JS_EXTRAINTERCEPTURLS]) {
					context['extra-intercept-urls'] = context[pop.c.JS_EXTRAINTERCEPTURLS];
				}
			}

			// Allow Custom .js to add their own JS Keys (eg: Fontawesome)
			pop.JSLibraryManager.execute('expandJSKeys', {context: context});
		}
	},

	// addPageSectionIds : function(domain, pageSection, moduleName) {
	
	// 	var that = this;

	// 	var pssId = that.getSettingsId(pageSection);
	// 	var psId = pageSection.attr('id');

	// 	// Insert into the Runtime to generate the ID
	// 	pop.JSRuntimeManager.addPageSectionId(domain, pssId, moduleName, psId);

	// 	var args = {
	// 		domain: domain,
	// 		pageSection: pageSection,
	// 		module: moduleName
	// 	}

	// 	pop.JSLibraryManager.execute('addPageSectionIds', args);
	// },

	initDocument : function(domain) {
	
		var that = this;

		$(document).on('user:loggedout', function(e, source, domain) {

			// Clear the user database when the user logs out
			// Use the domain from the event
			that.clearUserDatabase(domain);
		});

		var args = {
			domain: domain,
		}

		pop.JSLibraryManager.execute('initDocument', args);
		$(document).triggerHandler('initialize.pop.document', [domain]);
	},

	initDomain : function(domain) {
	
		var that = this;

		// Mark the domain as initialized
		that.domains[domain] = {
			initialized: true
		};

		// Initialize the variables holding the memory and databases for that domain;
		that.initDomainVars(domain);

		var args = {
			domain: domain,
		}
		pop.JSLibraryManager.execute('initDomain', args);
		$(document).triggerHandler('initialize.pop.domain', [domain]);
	},

	maybeInitializeDomain : function(domain) {
	
		var that = this;

		if (!that.domains[domain] || !that.domains[domain].initialized) {

			// Initialize the domain
			that.initDomain(domain);
		}
	},

	documentInitialized : function(domain) {
	
		var that = this;

		var args = {
			domain: domain,
		};

		// The difference between documentInitialized and documentInitializedIndependent,
		// is that documentInitializedIndependent does not create a dependency between JS files.
		// Then, when creating the resourceloader-mapping, those files will not be retrieved
		// Instead, their function documentInitializedIndependent will be called if they have been loaded anyway
		pop.JSLibraryManager.execute('documentInitialized', args);
		$(document).triggerHandler('initialized.pop.document');
	},

	documentLoaded : function(domain) {
	
		var that = this;

		// Change flag to say that the document is loaded
		that.loaded = true;

		var args = {
			domain: domain,
		};

		// Comment Leo 03/12/2017: execute `documentInitializedIndependent` here, so that it also executes on scripts with "defer"
		// For `documentInitialized` it can execute above, since scripts containing that function will not be loaded as non-critical
		pop.JSLibraryManager.execute('documentInitializedIndependent', args);
		pop.JSLibraryManager.execute('documentLoaded', args);
		$(document).triggerHandler('loaded.pop.document');
	},

	pageSectionFetchSuccess : function(pageSection, response, options) {
	
		var that = this;

		var args = {
			pageSection: pageSection, 
			response: response,
			options: options
		};
		pop.JSLibraryManager.execute('pageSectionFetchSuccess', args);
		pageSection.triggerHandler('fetched.pop.pageSection');
	},

	blockFetchSuccess : function(pageSection, block, response) {
	
		var that = this;

		pop.JSLibraryManager.execute('blockFetchSuccess', {pageSection: pageSection, block: block, response: response});
		block.triggerHandler('fetched.pop.block');
	},

	backgroundLoad : function(urls) {
	
		var that = this;

		// BackgroundLoad: execute only after the document is loaded. Then, resources.js will be loaded by then,
		// and we need not fear that the URLs fetched will not know what resources they must fetch.
		// Also, it bring Time-to-interactive forward, since the URLs fetched through backgroundLoad are secondary to the experience
		if (that.loaded) {
			that.execBackgroundLoad(urls);
		}
		else {
			$(document).on('loaded.pop.document', function() {
				that.execBackgroundLoad(urls);
			});
		}
	},

	execBackgroundLoad : function(urls) {
	
		var that = this;

		// Trigger loading the frames and other background actions
		var options = {
			'loadingmsg-target': null,
			silentDocument: true
		};
		$.each(urls, function(url, targets) {

			$.each(targets, function(index, target) {

				that.fetch(url, $.extend({target: target}, options));
			});
		});
	},

	// initPageSection : function(domain, pageSection) {
	
	// 	var that = this;

	// 	that.firstLoad[that.getSettingsId(pageSection)] = true;

	// 	// Initialize the params for this branch
	// 	that.initPageSectionRuntimeMemory(domain, pageSection);

	// 	pageSection.triggerHandler('initialize');
	// 	$(document).triggerHandler('initialize.pop.pagesection', [pageSection]);
	// },

	pageSectionInitialized : function(domain, pageSection) {
	
		var that = this;

		pop.JSLibraryManager.execute('pageSectionInitialized', {domain: domain, pageSection: pageSection});
		pageSection.triggerHandler('initialized');
		pageSection.triggerHandler('completed');


		// Once the module has been initialized, then that's it, no more JS running, set firstLoad in false
		that.firstLoad[that.getSettingsId(pageSection)] = false;
	},

	pageSectionNewDOMsInitialized : function(domain, pageSection, newDOMs, priority, options) {
	
		var that = this;

		// Open the corresponding offcanvas section
		// We use .pop-item to differentiate from 'full' and 'empty' pageSectionPages (eg: in pagesection-tabpane-source we need the empty one to close the sideInfo and then be deleted when the tab is closed)
		var pageSectionPage = newDOMs.filter('.pop-pagesection-page');
		if (pageSectionPage.length) {

			// Add the 'fetch-url', 'url' and 'target' as data attributes, so we keep track of the URL that produced the code for the opening page, to be used 
			// when updated stale json content from the Service Workers
			if (options['fetch-params']) {
				$.each(options['fetch-params'], function(key, value) {
					pageSectionPage.data(key, value);
				});
			}

			// Allow the pageSection to remain closed. eg: for the pageTabs in embed 
			var openmode = pop.PageSectionManager.getOpenMode(pageSection);
			var firstLoad = that.isFirstLoad(pageSection);
			if (openmode == 'automatic' || (firstLoad && openmode == 'initial')) {
				pop.PageSectionManager.open(pageSection);
			}
		}

		var args = {
			domain: domain,
			pageSection: pageSection,
			newDOMs: newDOMs
		};
		that.extendArgs(args, options);
		
		// Execute this first, so we can switch the tabPane to the newly added one before executing the JS
		pop.JSLibraryManager.execute('pageSectionNewDOMsBeforeInitialize', args);

		that.initPageSectionBranches(domain, pageSection, priority, options);

		pop.JSLibraryManager.execute('pageSectionNewDOMsInitialized', args);
	},

	initPageSectionBranches : function(domain, pageSection, priority, options) {
	
		var that = this;
		options = options || {};

		// Comment Leo 20/11/2017: we must set for what URL we are getting the session-ids and then deleting them
		// Otherwise, there may be a problem after the introduction of Progressive Booting:
		// 1. We execute 'critical' JS, which doesn't yet delete the pageSection session-ids
		// 2. This execution triggers calling https://getpop.org/en/loaders/loggedinuser-data/
		// 3. This one executes its own JS, and deletes all the pageSection session ids (formerly always under key 'general')
		// 4. When executing the 'noncritical' JS, the session-ids do not exist anymore!
		// Set the value of the URL in the options to make it explicit. Otherwise, take it from the topLevelFeedback
		var url = options['session-ids-url'];
		if (!url) {
			var tlFeedback = pop.Manager.getTopLevelFeedback(domain);
			url = tlFeedback[pop.c.URLPARAM_URL];
		}

		// Set it immediately before running the JS methods
		pop.JSRuntimeManager.setPageSectionURL(url);

		// First initialize the JS for the pageSection
		that.runPageSectionJSMethods(domain, pageSection, priority, options);

		// Then, Initialize all inner scripts for the blocks
		// It is fine that it uses pageSection in the 2nd params, since it's there that it stores the branches information, already selecting the proper element nodes
		var jsSettings = that.getPageSectionJsSettings(domain, pageSection);
		var blockBranches = jsSettings['initjs-blockbranches'];
		if (blockBranches) {

			blockBranches = $(blockBranches.join(','));
			if (priority != pop.c.PROGRESSIVEBOOTING.NONCRITICAL) {
				blockBranches = blockBranches.not('.'+pop.c.JS_INITIALIZED);
			}
			else {
				blockBranches = blockBranches.filter('.'+pop.c.CRITICALJS_INITIALIZED);
			}
			that.initBlockBranches(domain, pageSection, blockBranches, priority, options);
		}

		// Delete the session ids at the end of the rendering, but not for 'critical', since we will be executing 'noncritical' immediately after
		// If Progressive Booting is not enabled, then priority will be null, so no need to worry about this
		if (priority != pop.c.PROGRESSIVEBOOTING.CRITICAL) {

			// To be on the safe side that the URL might've been changed by some process in between, set it once again now
			pop.JSRuntimeManager.setPageSectionURL(url);
			pop.JSRuntimeManager.deletePageSectionLastSessionIds(domain, pageSection);
		}
	},

	triggerDestroyTarget : function(url, target) {

		var that = this;
		target = target || pop.c.URLPARAM_TARGET_MAIN;

		// Remove the tab from the open sessions
		that.removeOpenTab(url, target);

		// Intercept url+'!destroy' and this should call the corresponding destroy for the page
		// Call the interceptor to 
		pop.URLInterceptors.intercept(that.getDestroyUrl(url), {target: target});
	},

	destroyTarget : function(domain, pageSection, target) {

		var that = this;

		// Call 'destroy' from all libraries in pop.JSLibraryManager
		var args = {
			domain: domain,
			pageSection: pageSection,
			destroyTarget: target
		}
		pop.JSLibraryManager.execute('destroyTarget', args);
		target.triggerHandler('destroy');

		// Eliminate the params for each destroyed block
		var blocks = target.find('.pop-block.'+pop.c.JS_INITIALIZED).addBack('.pop-block.'+pop.c.JS_INITIALIZED);
		blocks.each(function() {
			
			var block = $(this);
			// that.deleteRuntimeMemoryPage(domain, pageSection, block, {url: that.getTargetParamsScopeURL(block)});
		})

		// Remove from the DOM
		target.remove();
	},

	destroyPageSectionPage : function(domain, pageSection, pageSectionPage) {

		var that = this;

		var target = pageSectionPage || pageSection;

		// When it's closed, if there's no other pageSectionPage around, then close the whole pageSection
		if (!pageSectionPage.siblings('.pop-pagesection-page').length) {
			pop.PageSectionManager.close(pageSection);
			pop.PageSectionManager.close(pageSection, 'xs');
		}

		that.destroyTarget(domain, pageSection, target);
	},

	pageSectionRendered : function(domain, pageSection, newDOMs, priority, options) {

		var that = this;		

		that.pageSectionNewDOMsInitialized(domain, pageSection, newDOMs, priority, options);
	},

	runScriptsBefore : function(pageSection, newDOMs) {
	
		var that = this;

		var args = {
			pageSection: pageSection,
			newDOMs: newDOMs
		};
		pop.JSLibraryManager.execute('runScriptsBefore', args);
	},

	jsInitialized : function(block) {

		var that = this;
		return block.hasClass(pop.c.JS_INITIALIZED);
	},
	jsLazy : function(block) {

		var that = this;
		return block.hasClass(pop.c.CLASS_LAZYJS);
	},
	setJsInitialized : function(block, priority) {

		var that = this;
		block.addClass(pop.c.JS_INITIALIZED).removeClass(pop.c.CLASS_LAZYJS);
		if (priority == pop.c.PROGRESSIVEBOOTING.CRITICAL) {

			block.addClass(pop.c.CRITICALJS_INITIALIZED);
		}
	},

	initBlockBranches : function(domain, pageSection, blocks, priority, options) {

		var that = this;

		// When being called from the parent node, the branch might still not exist
		// (eg: before it gets activated: #frame-main_blockgroup-tabpanel-main-blockgroup-tabpanel-sections.tab-pane.active > #frame-main_blockgroup-tabpanel-main-blockgroup-tabpanel-sections-body")
		if (!blocks.length) {
			return;
		}

		blocks.each(function() {

			var block = $(this);
			// Ask if it is already initialized or not. This is needed because, otherwise, when opening a tabpane inside of a tabpane,
			// the initialization of leaves in the last level will happen more than once

			var proceed = true;
			if (priority != pop.c.PROGRESSIVEBOOTING.NONCRITICAL) {
				proceed = !that.jsInitialized(block);
			}

			// If the block is lazy initialize, do not initialize first (eg: modals, they are initialized when first shown)
			// force-init: disregard if it's lazy or not: explicitly initialize it
			if (!options['force-init']) {
				
				proceed = proceed && !that.jsLazy(block);
			}

			// Commented so that we can do initBlockBranch(pageSection, pageSection) time and again
			// after it gets rendered appending new DOMs
			if (proceed) {

				// For priority = 'noncritical', no need to initialize, since the block has already been initialized during 'critical'
				// If Progressive Booting is not enabled, then priority will be null, so no need to worry about this
				if (priority != pop.c.PROGRESSIVEBOOTING.NONCRITICAL) {

					that.initBlock(domain, pageSection, block, priority, options);
				}
				else {

					// Directly run the 'noncritical' JS methods, which otherwise is done in initBlock
					pop.JSRuntimeManager.setBlockURL(domain, block);
					that.runBlockJSMethods(domain, pageSection, block, priority, options);
				}

				var jsSettings = that.getJsSettings(domain, pageSection, block);
				var blockBranches = jsSettings['initjs-blockbranches'];
				if (blockBranches) {

					blockBranches = $(blockBranches.join(', '));
					if (priority != pop.c.PROGRESSIVEBOOTING.NONCRITICAL) {
						blockBranches = blockBranches.not('.'+pop.c.JS_INITIALIZED);
					}
					else {
						blockBranches = blockBranches.filter('.'+pop.c.CRITICALJS_INITIALIZED);
					}
					that.initBlockBranches(domain, pageSection, blockBranches, priority, options);
				}
				var blockChildren = jsSettings['initjs-blockchildren'];
				if (blockChildren) {

					var target = block;
					$.each(blockChildren, function(index, selectors) {
						$.each(selectors, function(index, selector) {

							target = target.children(selector);
							if (priority != pop.c.PROGRESSIVEBOOTING.NONCRITICAL) {
								target = target.not('.'+pop.c.JS_INITIALIZED);
							}
							else {
								target = target.filter('.'+pop.c.CRITICALJS_INITIALIZED);
							}
						});
					});
					that.initBlockBranches(domain, pageSection, target, priority, options);
				}
			}
		});
	},

	initBlock : function(domain, pageSection, block, priority, options) {

		var that = this;
		options = options || {};

		// Do an extend of $options, so that the same object is not used for initializing 2 different blocks.
		// Eg: we don't to change the options.url on the same object for newRuntimePage. That could lead to 2 different blocks using the same URL,
		// it happens when doing openTabs with more than 2 tabs, it does it so quick that the calendar on the right all point to the same URL
		// that.initBlockRuntimeMemory(domain, pageSection, block, $.extend({}, options));

		// Comment Leo 09/12/2017: IMPORTANT: Place this function after initializing the block runtime memory above,
		// so that doing block.one('initialize') in .tmpl files (such as em-calendar-inner.tmpl) to access the internal memory works
		that.initializeBlock(block, priority);

		that.runScriptsBefore(pageSection, block);
		that.runBlockJSMethods(domain, pageSection, block, priority, options);
		
		// Important: place these handlers only at the end, so that handlers specified in pop.ManagerMethods are executed first
		// and follow the same order as above
		// This needs to be 'merged' instead of 'rendered' so that it works also when calling mergeTargetModule alone, eg: for the featuredImage
		block.on('rendered', function(e, newDOMs, targetContainers, renderedDomain) {
	
			var block = $(this);
			
			// Set the Block URL for pop.JSRuntimeManager.addModule to know under what URL to place the session-ids
			pop.JSRuntimeManager.setBlockURL(domain, block);
			
			that.runScriptsBefore(pageSection, newDOMs);
			
			// This won't execute again the JS on the block when adding newDOMs, because by then
			// the block ID will have disappeared from the lastSessionIds. The only ids will be the new ones,
			// contained in newDOMs
			that.runBlockJSMethods(renderedDomain, pageSection, block, null, null, options);
		});

		that.blockInitialized(domain, pageSection, block, options);

		// And only now, finally, load the block Content (all JS has already been initialized)
		// (eg: function setFilterBlockParams needs to set the filtering params, but does it on js:initialized event. Only after this, load content)
		that.loadBlockContent(domain, pageSection, block);
	},

	initializeBlock : function(block, priority) {
	
		var that = this;

		that.setJsInitialized(block, priority);

		// Trigger event
		block.triggerHandler('initialize');
	},

	extendArgs : function(args, options) {
	
		var that = this;

		// Allow to extend the args with whatever is provided under 'js-args'
		options = options || {};
		if (options['js-args']) {
			$.extend(args, options['js-args']);
		}
	},

	blockInitialized : function(domain, pageSection, block, options) {
	
		var that = this;

		var args = {
			domain: domain,
			pageSection: pageSection,
			block: block
		};
		that.extendArgs(args, options);

		pop.JSLibraryManager.execute('blockInitialized', args);

		// Trigger event
		block.triggerHandler('js:initialized');
	},

	loadBlockContent : function(domain, pageSection, block) {

		var that = this;

		if (!that.isContentLoaded(pageSection, block)) {

			// Add a class, so we can give an extra style to the block while loading the content
			// This class has already been added in blocks-base.php
			// block.addClass(pop.c.CLASS_LOADINGCONTENT);
			block.one('fetchDomainCompleted', function(e, status, domain) {
		
				block.removeClass(pop.c.CLASS_LOADINGCONTENT);
			});

			// Set the content as loaded
			that.setContentLoaded(pageSection, block);

			var options = {
				action: pop.c.CBACTION_LOADCONTENT,
				'post-data': block.data('post-data'),
				'loading-msg': pop.c.LOADING_MSG,
			};

			// Show disabled layer?
			var jsSettings = that.getJsSettings(domain, pageSection, block);
			if (jsSettings['loadcontent-showdisabledlayer']) {
				options['show-disabled-layer'] = true;
			}
			
			// Comment Leo 07/03/2016: execute the fetchBlock inside document ready, so that it doesn't
			// trigger immediately while rendering the HTML, but waits until all HTML has been rendered.
			// Eg: for the top bar notifications, it was triggering immediately, even before the trigger for /loggedinuser-data,
			// which will bring all the latest notifications. However, because /notifications is statelessdata, it will save the user "lastaccess" timestamp,
			// so overriding the previous timestamp needed by /loggedinuser-data for the latest notifications
			$(document).ready(function($) {
				that.fetchBlock(pageSection, block, options);
			});
		}
	},
	getGroupMethodsByPriority : function(priorityGroupMethods, priority) {
	
		var that = this;
		
		// Execute methods: "critical" or "noncritical" if specified, or all of them if "null"
		var priorities = [pop.c.PROGRESSIVEBOOTING.CRITICAL, pop.c.PROGRESSIVEBOOTING.NONCRITICAL];
		if (priorities.indexOf(priority) >= 0) {

			return priorityGroupMethods[priority];
		}

		// or execute both when the priority is null (=> Progressive Booting is disabled, or calling this function not from pop.Manager.init())
		// If Progressive Booting is not enabled, then priority will be null, so no need to worry about this
		var groupMethods = {};
		$.each(priorities, function(index, priority) {

			if (priorityGroupMethods[priority]) {

				// Watch out: we may have 2 functions, one critical one not, for the same group
				// (Eg: "main" => ["calendar"] and "main" => ["waypoints"])
				// Then, we must merge the values
				$.each(priorityGroupMethods[priority], function(group, methods) {
					
					if (groupMethods[group]) {

						groupMethods[group] = groupMethods[group].concat(methods);
					}
					else {

						groupMethods[group] = methods;
					}
				});				
			}
		});

		return groupMethods;
	},

	runPageSectionJSMethods : function(domain, pageSection, priority, options) {
	
		var that = this;

		var sessionIds = pop.JSRuntimeManager.getPageSectionSessionIds(domain, pageSection, 'last');
		if (!sessionIds) return;

		// Make sure it executes the JS in each module only once.
		// Eg: Otherwise, since having MultiLayouts, it will execute 'popover' for each single 'layout-popover-user' module found, and it repeats itself inside a block many times
		var executedModules = [];
		var moduleMethods = that.getPageSectionJsMethods(domain, pageSection);
		$.each(moduleMethods, function(moduleName, priorityGroupMethods) {
			if (executedModules.indexOf(moduleName) == -1) {
				executedModules.push(moduleName);
				var groupMethods = that.getGroupMethodsByPriority(priorityGroupMethods, priority);
				if (sessionIds[moduleName] && groupMethods) {
					$.each(groupMethods, function(group, methods) {
						var ids = sessionIds[moduleName][group];
						if (ids) {
							var selector = '#'+ids.join(', #');
							var targets = $(selector);
							if (targets.length) {
								$.each(methods, function(index, method) {
									var args = {
										domain: domain,
										pageSection: pageSection,
										targets: targets,
									};
									that.extendArgs(args, options);
									pop.JSLibraryManager.execute(method, args);
								});
							}
						}
					});
				}
			}
		});
	},

	runBlockJSMethods : function(domain, pageSection, block, priority, options) {
	
		var that = this;
		that.runJSMethods(domain, pageSection, block, null, null, priority, options);
		
		// Delete the session ids after running the js methods
		// If Progressive Booting is not enabled, then priority will be null, so no need to worry about this
		if (priority != pop.c.PROGRESSIVEBOOTING.CRITICAL) {
			pop.JSRuntimeManager.deleteBlockLastSessionIds(domain, pageSection, block);
		}
	},
	runJSMethods : function(domain, pageSection, block, moduleFrom, session, priority, options) {
	
		var that = this;

		// Get the blockSessionIds: these contain the ids added to the block only during the last
		// 'rendered' session. This way, when appending newDOMs (eg: with waypoint on scrolling down),
		// it will execute the JS scripts only on these added elements and not the whole block
		var sessionIds = pop.JSRuntimeManager.getBlockSessionIds(domain, pageSection, block, session);
		if (!sessionIds) return;
		
		var moduleMethods = that.getModuleJSMethods(domain, pageSection, block, moduleFrom);
		that.runJSMethodsInner(domain, pageSection, block, moduleMethods, sessionIds, [], priority, options);
	},	
	runJSMethodsInner : function(domain, pageSection, block, moduleMethods, sessionIds, executedModules, priority, options) {
	
		var that = this;
		options = options || {};

		// For each module, analyze what methods must be executed, and then continue down the line
		// doing the same for contained modules
		var moduleName = moduleMethods[pop.c.JS_COMPONENT];
		if (executedModules.indexOf(moduleName) == -1) {
			
			executedModules.push(moduleName);

			if (moduleMethods[pop.c.JS_METHODS]) {
		
				// Execute methods: "critical" or "noncritical" if specified,
				// or execute both when the priority is null (=> Progressive Booting is disabled, or calling this function not from pop.Manager.init())
				// If Progressive Booting is not enabled, then priority will be null, so no need to worry about this
				var groupMethods = that.getGroupMethodsByPriority(moduleMethods[pop.c.JS_METHODS], priority);
				if (sessionIds[moduleName] && groupMethods) {
					$.each(groupMethods, function(group, methods) {
						var ids = sessionIds[moduleName][group];
						if (ids) {
							var selector = '#'+ids.join(', #');
							var targets = $(selector);
							if (targets.length) {
								$.each(methods, function(index, method) {
									var args = {
										domain: domain,
										pageSection: pageSection,
										block: block,
										targets: targets,
									};
									that.extendArgs(args, options);
									pop.JSLibraryManager.execute(method, args);
								});
							}
						}
					});
				}
			}
		}

		// Continue down the line to the following modules
		if (moduleMethods[pop.c.JS_NEXT]) {

			// Next is an array, since each module can contain many others.
			$.each(moduleMethods[pop.c.JS_NEXT], function(index, next) {
				
				that.runJSMethodsInner(domain, pageSection, block, next, sessionIds, executedModules, priority, options);
			})
		}
	},
	getModuleJSMethods : function(domain, pageSection, block, moduleFrom) {
	
		var that = this;

		var moduleMethods = that.getBlockJsMethods(domain, pageSection, block);
		
		// If not moduleFrom provided, then we're using the block as 'from' so we can already
		// return the moduleMethods, which always start from the block
		if (!moduleFrom) {

			return moduleMethods;
		}
		
		// Start searching for the moduleFrom down the line moduleMethods. 
		// Once found, that's the moduleMethods needed => map of moduleFrom => methods to execute
		return that.getModuleJSMethodsInner(pageSection, block, moduleFrom, moduleMethods);
	},	
	getModuleJSMethodsInner : function(pageSection, block, moduleFrom, moduleMethods) {
	
		var that = this;

		// Check if the current level is the module we're looking for. If so, we found it, return it
		// and it will crawl all the way back up
		if (moduleMethods[pop.c.JS_COMPONENT] == moduleFrom) {

			return moduleMethods;
		}

		// If not, keep looking among the contained modules
		var found;
		if (moduleMethods[pop.c.JS_NEXT]) {
			$.each(moduleMethods[pop.c.JS_NEXT], function(index, next) {
				
				found = that.getModuleJSMethodsInner(pageSection, block, moduleFrom, next);
				if (found) {
					// found the result => exit the loop and immediately return this result
					return false;
				}
			});
		}
		return found;
	},	

	maybeRedirect : function(feedback) {

		var that = this;

		// Redirect / Fetch?
		if (feedback.redirect && feedback.redirect.url) {

			// Soft redirect => Used after submitting posts
			if (feedback.redirect.fetch) {

				// Comment Leo 22/09/2015: create and anchor and "click" it, so it can be intercepted (eg: Reset password)
				that.click(feedback.redirect.url)
			}

			// Hard redirect => Used after logging in
			else {

				// Delete the browser history (to avoid inconsistency of state if the users presses browser back button before the redirection is over)
				window.location = feedback.redirect.url;
				return true;
			}
		}

		return false;
	},

	historyReplaceState : function(elem, options) {

		var that = this;

		var url = elem.data('historystate-url');
		var title = elem.data('historystate-title');
		var thumb = elem.data('historystate-thumb');

		pop.BrowserHistory.replaceState(url);

		// Also update the title in the browser tab
		if (title) {
			that.documentTitle = unescapeHtml(title);
			document.title = that.documentTitle;
		}
	},

	isHidden : function(elem) {

		var that = this;
		if (elem.hasClass('hidden')) return true;

		// Check if the element is still in the DOM
		var elem_id = elem.attr('id');
		if (elem_id) {
			if (!($('#'+elem_id).length)) {
				return true;
			}
		}

		// Check if the element is inside a tabPanel which is not active
		var tabPanes = elem.parents('.tab-pane');
		var activeTabPanes = tabPanes.filter('.active');
		if (tabPanes.length > activeTabPanes.length) {
			return true;
		}

		var executed = pop.JSLibraryManager.execute('isHidden', {targets: elem});
		var ret = false;
		$.each(executed, function(index, value) {
			if (value) {
				ret = true;
				return -1;
			}
		});

		return ret;
	},

	isActive : function(elem) {

		var that = this;

		var executed = pop.JSLibraryManager.execute('isActive', {targets: elem});
		var ret = true;
		$.each(executed, function(index, value) {
			if (!value) {
				ret = false;
				return -1;
			}
		});

		return ret;
	},

	isContentLoaded : function(pageSection, block) {

		var that = this;

		var blockQueryState = that.getBlockQueryState(pageSection, block);
		return !blockQueryState[pop.c.DATALOAD_LAZYLOAD];
	},
	setContentLoaded : function(pageSection, block) {

		var that = this;

		var blockQueryState = that.getBlockQueryState(pageSection, block);
		blockQueryState[pop.c.DATALOAD_LAZYLOAD] = false;
	},

	updateDocument : function(domain) {

		var that = this;

		// Update the title in the page
		// that.updateTitle(that.getTopLevelFeedback(domain)[pop.c.URLPARAM_TITLE]);
		that.updateTitle(that.getRequestMeta(domain)[pop.c.URLPARAM_TITLE]);
	},

	updateTitle : function(title) {

		var that = this;

		if (title) {
			that.documentTitle = unescapeHtml(title);
			document.title = that.documentTitle;
		}
	},

	executeSetFilterBlockParams : function(pageSection, block, filter) {
	
		var that = this;
		var blockQueryState = that.getBlockQueryState(pageSection, block);

		// Filter out inputs (input, select, textarea, etc) without value (solution found in http://stackoverflow.com/questions/16526815/jquery-remove-empty-or-white-space-values-from-url-parameters)
		blockQueryState.filter = filter.find('.' + pop.c.FORM_INPUT).filter(function () {return $.trim(this.value);}).serialize();

		// // Only if filtering fields not empty (at least 1 exists) add the name of the filter
		// if (blockQueryState.filter) {
		// 	blockQueryState.filter += '&'+filter.find('.' + pop.c.FILTER_NAME_INPUT).serialize();
		// }
	},

	setFilterBlockParams : function(pageSection, block, filter) {
	
		var that = this;
		if (that.jsInitialized(block)) {
			that.executeSetFilterBlockParams(pageSection, block, filter);
		}
		else {
			block.one('js:initialized', function() {
				that.executeSetFilterBlockParams(pageSection, block, filter);
			});
		}
	},
	
	// Comment Leo 08/07/2016: filter might or might not be under that block. Eg: called by initBlockProxyFilter it is not
	filter : function(pageSection, block, filter) {
	
		var that = this;
		
		// that.setFilterParams(pageSection, filter);
		that.setFilterBlockParams(pageSection, block, filter);

		// Reload
		that.reload(pageSection, block);

		// Scroll Top to show the "Submitting" message
		that.blockScrollTop(pageSection, block);
	},

	blockScrollTop : function(pageSection, block) {
	
		var that = this;
		
		// Scroll Top to show the "Submitting" message
		var modal = block.closest('.modal');
		if (modal.length == 0) {
			that.scrollToElem(pageSection, block.find('.dataload-status').first(), true);
		}
		else {
			modal.animate({ scrollTop: 0 }, 'fast');
		}
	},

	setReloadBlockParams : function(pageSection, block) {
	
		var that = this;
		
		// Comment Leo 25/07/2017: do the reset for all domains
		var queryState = that.getBlockMultiDomainQueryState(pageSection, block);
		$.each(queryState, function(domain, blockDomainQueryState) {
			
			// Delete the data saved
			// Comment Leo 03/04/2015: this is ugly and should be fixed: not all blocks have these elements (paged, stop-fetching)
			// The lists have it (eg: My Events) but an Edit Event page does not. However this one can also be reloaded (eg: first loading an edit page when user
			// is logged out, then log in, it will refetch the block), that's why I added the ifs. However a nicer way should be implemented
			if (blockDomainQueryState[pop.c.DATALOAD_PARAMS] && blockDomainQueryState[pop.c.DATALOAD_PARAMS][pop.c.URLPARAM_PAGED]) {
				blockDomainQueryState[pop.c.DATALOAD_PARAMS][pop.c.URLPARAM_PAGED] = '';
			}
			if (blockDomainQueryState[pop.c.URLPARAM_STOPFETCHING]) {
				blockDomainQueryState[pop.c.URLPARAM_STOPFETCHING] = false;
			}
		});
	},	

	refetch : function(pageSection, block, options) {
	
		var that = this;
		
		options = options || {};
		options.action = pop.c.CBACTION_REFETCH;

		block.triggerHandler('beforeRefetch');

		// Refetch
		that.reload(pageSection, block, options);
	},	

	reset : function(domain, pageSection, block, options) {
	
		var that = this;
		options = options || {};

		// // Sometimes there is no need to restore the initial memory, and even more, it can't be done since it has
		// // unwanted consequences. Eg: when creating a user account, it will reset the form, but the feedbackmessage
		// // with the message "Your account was created successfully" must remain
		// if (!options['skip-restore']) {

		// 	// Reset the params. Eg: "pid", "_wpnonce"
		// 	that.restoreInitialBlockMemory(pageSection, block);
		// }

		that.processBlock(domain, pageSection, block, {operation: pop.c.URLPARAM_OPERATION_REPLACE, action: pop.c.CBACTION_RESET, reset: true});
	},	

	reload : function(pageSection, block, options) {
	
		var that = this;
		// Options: it will potentially already have attr 'action'
		options = options || {};
		options.operation = pop.c.URLPARAM_OPERATION_REPLACE;
		options.reload = true;

		// If pressing on reload, then we must also hide the latestcount message
		options['hide-latestcount'] = true;

		// Delete the data saved
		that.setReloadBlockParams(pageSection, block);

		block.triggerHandler('beforeReload');

		// Load everything again
		that.fetchBlock(pageSection, block, options);
	},	

	loadLatest : function(domain, pageSection, block, options) {
	
		var that = this;
		options = options || {};
		var blockDomainQueryState = that.getBlockDomainQueryState(domain, pageSection, block);

		// Add the latest content on top of everything else
		options.operation = pop.c.URLPARAM_OPERATION_PREPEND;

		// Do not check flag Stop Fetching, that is needed for the appended older content, not prepended newer one
		options['skip-stopfetching-check'] = true;

		// Delete the latestCount when fetch succedded
		options['hide-latestcount'] = true;

		// Add the action and the timestamp
		var post_data = {};
		post_data[pop.c.URLPARAM_ACTIONS+'[]'] = pop.c.URLPARAM_ACTION_LATEST;
		post_data[pop.c.URLPARAM_TIMESTAMP] = blockDomainQueryState[pop.c.URLPARAM_TIMESTAMP];
		options['onetime-post-data'] = $.param(post_data);

		block.triggerHandler('beforeLoadLatest');

		// Load latest content. 
		that.fetchBlock(pageSection, block, options);
	},	

	handlePageSectionLoadingStatus : function(pageSection, operation, options) {
		
		var that = this;
		var loading;

		// If the target is given in the options, use it (eg: userloggedin-data loading message). If not, find it under the status
		options = options || {};
		if (typeof options['loadingmsg-target'] != 'undefined') {

			// The target might be empty, which means: show no message. Then nothing to do
			if (!options['loadingmsg-target']) {
				return;
			}

			loading = $(options['loadingmsg-target']);
		}
		else {
		
			var status = pop.PageSectionManager.getPageSectionStatus(pageSection);
			loading = status.find('.pop-loading');
		}

		that.handleLoadingStatus(loading, operation, options)
	},

	handleLoadingStatus : function(loading, operation) {
		
		var that = this;

		// Comment Leo 09/09/2015: in the past, we passed num as an argument to the function, with value params.loading.length
		// But this doesn't work anymore since adding 'loadingmsg-target', since this one and the general loading share the params.loading
		// values but then then "num" for each one of them will be the addition of both targets
		// So now, instead, save the number in the target under attr 'data-num'
		var num = loading.data('num') || 0;
		if (operation == 'add') {
			num += 1;
		}
		else if (operation == 'remove') {
			num -= 1;
		}
		loading.data('num', num);
		
		if (num) {
			loading.removeClass('hidden');
		}
		else {
			loading.addClass('hidden');
		}

		if (num >= 2) {
			loading.find('.pop-box').text('x'+num);
		}
		else {
			loading.find('.pop-box').text('');			
		}
	},

	fetch : function(url, options) {

		var that = this;
		options = options || {};

		var pageSection = that.getFetchTargetPageSection(options.target);

		// When there's a javascript error, natureParams is null. So check for this and then do normal window redirection
		var params = that.getPageSectionParams(pageSection);
		if (!params) {
			if (options['noparams-reload-url']) {
				
				window.location = url;
			}
			return;
		}
		
		that.fetchPageSection(pageSection, url, options);
	},

	removeLocalStorageItem : function(path, key) {

		var that = this;

		// Allow typeahead to also delete its entries
		var args = {result: key.startsWith(path), key: key, path: path};
		pop.JSLibraryManager.execute('removeLocalStorageItem', args);
		return args.result;
	},

	initLocalStorage : function() {

		var that = this;

		// Delete all stale entries
		if (pop.c.USELOCALSTORAGE && supports_html5_storage()) {
				
			var latest = localStorage['PoP:version'];
			if (!latest || (latest != pop.c.VERSION)){

				// Delete all stale entries: all those starting with any of the allowed domains
				// Solution taken from https://stackoverflow.com/questions/7591893/html5-localstorage-jquery-delete-localstorage-keys-starting-with-a-certain-wo
				Object.keys(localStorage).forEach(function(key) { 

					// Allow typeahead to also delete its entries
					pop.c.ALLOWED_DOMAINS.forEach(function(path) { 

						if (that.removeLocalStorageItem(path, key)) {
							localStorage.removeItem(key); 
							return -1;
						}
					});
				}); 

				// Save the current version
				localStorage['PoP:version'] = pop.c.VERSION;
			}
		}
	},

	openTabs : function() {

		var that = this;
		if (!pop.c.KEEP_OPEN_TABS) return;

		// Get all the tabs open from the previous session, and open them already		
		var options = {
			silentDocument: true,
			'js-args': {
				inactivePane: true,

				// Do not store these tabs again when they come back from the fetch
				addOpenTab: false
			}
		};

		var currentURL = that.getTabsCurrentURL();

		var tabs = that.getScreenOpenTabs();
		$.each(tabs, function(target, urls) {

			// Open the tab on the corresponding target
			options.target = target;

			// If on the main pageSection...
			if (target == pop.c.URLPARAM_TARGET_MAIN) {

				// Do not re-open the one URL the user opened
				var pos = urls.indexOf(currentURL);
				if (pos !== -1) {
					
					// Remove the entry
					urls.splice(pos, 1);
				}
			}

			// Open all tabs
			$.each(urls, function(index, url) {

				that.fetch(url, options);
			});
		});
	},

	getTabsCurrentURL : function() {

		var that = this;
		
		var currentURL = window.location.href;
		
		// Special case for the homepage: the link must have the final '/'
		if (currentURL == pop.c.HOME_DOMAIN) {
			currentURL = pop.c.HOME_DOMAIN+'/';
		}

		// Special case for qTranslateX: if we are loading the homepage, without the language information
		// (eg: https://kuwang.com.ar), and a tab with the language is open (eg: https://kuwang.com.ar/es/)
		// then have it removed, or the homepage will be open twice. For that, we assume the current does have
		// the language information, so it will be removed below
		if (currentURL == pop.c.HOME_DOMAIN+'/' && pop.c.HOMELOCALE_URL) {
			currentURL = pop.c.HOMELOCALE_URL+'/';
		}

		return currentURL;
	},

	getOpenTabsKey : function() {

		var that = this;
		
		// Comment Leo 16/01/2017: we can all params set in the topLevel feedback directly
		var key = pop.c.LOCALE;

		// The tabs will always be opened locally, so it is ok to assume that the domain is our local URL
		var domain = pop.c.HOME_DOMAIN;

		// Also add all the other "From Server" params if initially set (eg: themestyle, settingsformat, mangled)
		// var params = that.getTopLevelFeedback(domain)[pop.c.DATALOAD_PARAMS];
		var params = that.getSiteMeta(domain)[pop.c.DATALOAD_PARAMS] || {};
		$.each(params, function(param, value) {
			key += '|'+param+'='+value;
		});

		return key;
	},

	getScreenOpenTabs : function() {

		var that = this;
		var tabs = that.getOpenTabs();
		var key = that.getOpenTabsKey();

		return tabs[key] || {};
	},

	keepScreenOpenTab : function(url, target) {

		// Function executed to only keep a given tab open and close all the others.
		// Used for the alert "Do you want to open the previous session tabs?" 
		// If clicking cancel, then remove all other tabs, for next time that the user opens the browser
		var that = this;
		var tabs = that.getOpenTabs();
		var key = that.getOpenTabsKey();

		// Remove all other targets also, so that it delets open pages in addons pageSection
		tabs[key] = {};
		tabs[key][target] = [url];
		that.addLocalStorageData('PoP:openTabs', tabs);
	},

	getOpenTabs : function() {

		var that = this;
		if (!pop.c.KEEP_OPEN_TABS) return {};

		return that.getLocalStorageData('PoP:openTabs') || {};
	},

	addOpenTab : function(url, target) {

		var that = this;
		if (!pop.c.KEEP_OPEN_TABS) return false;

		var tabs = that.getOpenTabs();
		var key = that.getOpenTabsKey();
		tabs[key] = tabs[key] || {};
		tabs[key][target] = tabs[key][target] || [];
		if (tabs[key][target].indexOf(url) > -1) {

			// The entry already exists
			return false;			
		}

		tabs[key][target].push(url);
		that.addLocalStorageData('PoP:openTabs', tabs);

		return true;
	},

	removeOpenTab : function(url, target) {

		var that = this;
		if (!pop.c.KEEP_OPEN_TABS) return false;

		var tabs = that.getOpenTabs();
		var key = that.getOpenTabsKey();
		tabs[key] = tabs[key] || {};
		tabs[key][target] = tabs[key][target] || [];
		var pos = tabs[key][target].indexOf(url);
		if (pos === -1) {

			return false;
		}
			
		// Remove the entry
		tabs[key][target].splice(pos, 1);
		if (!tabs[key][target].length) {

			delete tabs[key][target];
			if (!tabs[key].length) {

				delete tabs[key];
			}
		}
		that.addLocalStorageData('PoP:openTabs', tabs);

		return true;
	},

	replaceOpenTab : function(fromURL, toURL, target) {

		var that = this;
		if (!pop.c.KEEP_OPEN_TABS) return;

		if (that.removeOpenTab(fromURL, target)) {
			that.addOpenTab(toURL, target);
		}
	},

	getLocalStorageData : function(localStorageKey, use_version) {

		var that = this;

		// Check if a response is stored in local storage for that combination of URL and params
		if (pop.c.USELOCALSTORAGE && supports_html5_storage()) {
				
			var stored = localStorage[localStorageKey];
			if (stored) {

				// Transform the string back into JSON
				stored = JSON.parse(stored);

				if (use_version) {

					// Make sure the response was generated for the current version of the software
					// And also check if it has not expired
					if ((stored.version == pop.c.VERSION) && (typeof stored.expires == 'undefined' || stored.expires > Date.now())){

						return stored.value;
					}

					// The entry is stale (either different version, or entry expired), so delete it
					delete localStorage[localStorageKey];
					return null;
				}
				else {

					return stored.value;
				}
			}
		}

		return null;
	},

	addLocalStorageData : function(localStorageKey, value, expires) {

		var that = this;
		if (pop.c.USELOCALSTORAGE && supports_html5_storage()) {
				
			var stored = {
				version: pop.c.VERSION,
				value: value
			}

			// Does the entry expire? Save the moment when it does. expires is set in ms
			if (expires) {
				stored.expires = Date.now() + expires;
			}

			// If the size is big and it fails, it throws an exception and interrupts
			// the execution of the code. So catch it.
			try {
				localStorage[localStorageKey] = JSON.stringify(stored);

			}
			catch(err) {
				// Do nothing
				console.error(err);
				// console.log('Error: '+err.message);
			}
		}
	},

	getBlockDisabledLayer : function(block) {

		// return block.children('.blocksection-inners').children('.pop-disabledlayer');
		return block.find('.pop-disabledlayer').first();
	},

	fetchPageSection : function(pageSection, url, options) {

		var that = this;
		var params = that.getPageSectionParams(pageSection);

		// When there's a javascript error, natureParams is null. So check for this and then do normal window redirection
		if (!params) {
			return;
		}

		// If already loading this url (user pressed twice), then do nothing
		if (params.loading.indexOf(url) > -1) {

			return;
		}

		options = options || {};

		var target = that.getTarget(pageSection);
		var domain = getDomain(url);

		// Initialize the domain, if needed
		that.maybeInitializeDomain(domain);

		// Allow PoP Service Workers to modify the options, adding the Network First parameter to allow to fetch the preview straight from the server
		// Also, re-take the URL from the args, so plugins can also modify it. Eg: routing through a CDN with pop-cdn
		var args = {
			domain: domain,
			options: options, 
			url: url
		};
		pop.JSLibraryManager.execute('modifyFetchArgs', args);
		var fetchUrl = args.url;

		// Remove any hashtag the url may have (eg: /add-post/#1482655583982)
		// Needed for when reopening the previous session tabs, and an Add Post page with such a hashtag was open
		// Otherwise, below it makes mess, it doesn't add the needed parameters to the URL
		if (fetchUrl.indexOf('#') > -1) {
			fetchUrl = fetchUrl.substr(0, fetchUrl.indexOf('#'));
		}
		// Add a param to tell the back-end we are doing ajax
		// Important: do not change the order in which these attributes are added, or it can ruin other things,
		// eg: adding the get_precache_list pages for BACKGROUND_LOAD in plugins/poptheme-wassup/themes/wassup/thememodes/simple/thememode.php
		fetchUrl = add_query_arg(pop.c.URLPARAM_TARGET, target, fetchUrl);
		// fetchUrl = add_query_arg(pop.c.URLPARAM_DATAOUTPUTITEMS+'[]', pop.c.URLPARAM_DATAOUTPUTITEMS_COMPONENTSETTINGS, fetchUrl);
		// fetchUrl = add_query_arg(pop.c.URLPARAM_DATAOUTPUTITEMS+'[]', pop.c.URLPARAM_DATAOUTPUTITEMS_COMPONENTDATA, fetchUrl);
		// fetchUrl = add_query_arg(pop.c.URLPARAM_DATAOUTPUTITEMS+'[]', pop.c.URLPARAM_DATAOUTPUTITEMS_DATABASES, fetchUrl);
		// fetchUrl = add_query_arg(pop.c.URLPARAM_DATAOUTPUTITEMS+'[]', pop.c.URLPARAM_DATAOUTPUTITEMS_SESSION, fetchUrl);
		fetchUrl = add_query_arg(pop.c.URLPARAM_OUTPUT, pop.c.URLPARAM_OUTPUT_JSON, fetchUrl);

		// Allow the Resource Loader to load all needed .js/.css files, in advance of the fetch
		pop.JSLibraryManager.execute('preFetchPageSection', {url: fetchUrl, options: options});

		// Contains the attr for the Theme
		var topLevelFeedback = that.getTopLevelFeedback(domain);
		var pageSectionFeedback = that.getPageSectionFeedback(domain, pageSection);
		var paramsData = $.extend({}, topLevelFeedback[pop.c.DATALOAD_PARAMS], pageSectionFeedback[pop.c.DATALOAD_PARAMS], params[pop.c.DATALOAD_PARAMS]);
		// Extend with params given through the options. Eg: WSL "action=authenticated, provider=facebook" params to log-in the user
		if (options.params) {
			$.extend(paramsData, options.params);
		}
		var postData = $.param(paramsData);
		var localStorageKey;

		// Check if a response is stored in local storage for that combination of URL and params
		localStorageKey = fetchUrl+'|'+postData;
		// Only use localStorage for the local domain (getpop.org/content.getpop.org), not for external ones (agendaurbana.org, etc)
		// This is needed to:
		// 1. Save space in localStorage
		// 2. Avoid saving a page (eg: add comment) which has the external domain version parameter, which we can't control in the local app, so we can't ignore those JSON URLs in SW
		var stored = (domain == pop.c.HOME_DOMAIN) ? that.getLocalStorageData(localStorageKey) : false;
		if (stored) {

			that.prePageSectionSuccess(pageSection, stored, options);
			that.processPageSectionResponse(domain, pageSection, url, fetchUrl, stored, options);

			// That's it!
			return;
		}

		// that.executeFetchPageSection(domain, pageSection, url, params, fetchUrl, postData, target, localStorageKey, options);
		var status = pop.PageSectionManager.getPageSectionStatus(pageSection);
		var error = status.find('.pop-error');

		// Show the Disabled Layer over a block?
		if (options['disable-layer-for-block']) {
			that.getBlockDisabledLayer(options['disable-layer-for-block']).removeClass('hidden');
		}

		var crossdomain = that.getCrossDomainOptions(fetchUrl);

		$.ajax($.extend(crossdomain, {
			dataType: "json",
			url: fetchUrl,
			data: postData,
			beforeSend: function(jqXHR, settings) {

				// Addition of the URL to retrieve local information back when it comes back
				// http://stackoverflow.com/questions/11467201/jquery-statuscode-404-how-to-get-jqxhr-url
				// Comment Leo 25/12/2016: set the original url (which might include a hashtag, as in /add-post/#1482655583982)
				// and not the settings.url, which is the actual URL we're sending to. This way, we can $.ajax concurrently to the same URL
				// twice, since they had different hashtags (as in when having 2 Add Post tabs open, and get all reopened with openTabs())
				jqXHR.url = url;

				// Save the fetchUrl to retrieve it under 'complete'
				params.url[jqXHR.url] = url;
				params.target[jqXHR.url] = target;

				// Keep the URL being fetched for updating stale json content using Service Workers
				params['fetch-url'][jqXHR.url] = settings.url;

				// Save the url being loaded
				params.loading.push(url);
		
				that.handlePageSectionLoadingStatus(pageSection, 'add', options);
			},
			complete: function(jqXHR) {

				// Everything below can be executed even if the deferred object executed in .processPageSectionResponse
				// has not resolved yet. 
				var url = params.url[jqXHR.url];
				delete params.url[jqXHR.url];
				delete params.target[jqXHR.url];
				delete params['fetch-url'][jqXHR.url];

				params.loading.splice(params.loading.indexOf(url), 1);

				that.handlePageSectionLoadingStatus(pageSection, 'remove', options);

				// Callback when the url was fetched
				if (options['urlfetched-callbacks']) {

					var handler = 'urlfetched:'+escape(url);
					$.each(options['urlfetched-callbacks'], function(index, callback) {

						$(document).one(handler, callback);
					});
				}

				// Remove the Disabled Layer over a block
				if (options['disable-layer-for-block']) {
					that.getBlockDisabledLayer(options['disable-layer-for-block']).addClass('hidden');
				}
			},			
			success: function(response, textStatus, jqXHR) {

				that.prePageSectionSuccess(pageSection, response, options);

				// Hide the error message
				error.addClass('hidden');

				// If the original URL had a hashtag (eg: /add-post/#1482655583982), the returning url
				// will also have one (using is_multipleopen() => add_unique_id), but not the same one, then 
				// replace the original one with the new one in PoP:openTabs, or otherwise it will keep adding new tabs to the openTabs, 
				// which are the same tab but duplicated for having different hashtags in the URL
				var url = params.url[jqXHR.url];
				var feedbackURL = response.statefuldata.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pop.c.URLPARAM_URL];
				var target = params.target[jqXHR.url];
				
				if (url != feedbackURL) {
					that.replaceOpenTab(url, feedbackURL, target);
				}

				// Add the fetched URL to the options, so we keep track of the URL that produced the code for the opening page, to be used 
				// when updated stale json content from the Service Workers
				options['fetch-params'] = {
					url: url,
					target: target,
					'fetch-url': params['fetch-url'][jqXHR.url]
				};

				// Local storage? Save the response as a string
				// Save it at the end, because sometimes the localStorage fails (lack of space?) and it stops the flow of the JS
				// Important: execute this before calling "processPageSectionResponse" below, since this function alters the response
				// by adding "parent-context" and "root-context" making the object circular, upon which JSON.stringify fails
				// ("Uncaught TypeError: Converting circular structure to JSON")
				// Store only for local domain
				if (response.statefuldata.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pop.c.URLPARAM_STORELOCAL] && domain == pop.c.HOME_DOMAIN) {
						
					that.addLocalStorageData(localStorageKey, response);
				}
				that.processPageSectionResponse(domain, pageSection, url, fetchUrl, response, options);
			},
			error: function(jqXHR, textStatus, errorThrown) {

				var fetchedUrl = params.url[jqXHR.url];
				var url = params.url[jqXHR.url];

				// Show an error if the fetch was not silent
				if (!options.silentDocument) {
					that.showError(pageSection, that.getError(pageSection, url, jqXHR, textStatus, errorThrown));
				}

				that.triggerURLFetchFailed(url);
				pageSection.triggerHandler('fetchFailed');
			}
		}));
	},

	prePageSectionSuccess : function(pageSection, response, options) {

		var that = this;
		
		// Allow pop-cdn to incorporate the thumbprint values in the topLevelFeedback before backgroundLoad
		that.pageSectionFetchSuccess(pageSection, response, options);

		// The server might have requested to load extra URLs (eg: forcedserverload_fields)
		that.backgroundLoad(response.statefuldata.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pop.c.URLPARAM_BACKGROUNDLOADURLS]);
	},

	getCrossDomainOptions : function(url) {

		var that = this;
		var options = {};

		// If the URL is not from this same website, but from any aggregated website, then allow the cross domain
		if(!url.startsWith(pop.c.HOME_DOMAIN)) {

			$.each(pop.c.ALLOWED_DOMAINS, function(index, allowed) {

				if(url.startsWith(allowed)) {

					options.xhrFields = {
						withCredentials: true
					};
					options.crossDomain = true;

					return -1;
				}
			});
		}

		return options;
	},

	showError : function(pageSection, message) {

		var that = this;

		var status = pop.PageSectionManager.getPageSectionStatus(pageSection);
		var error = status.find('.pop-error');
		
		error.children('div.pop-box').html(message);
		error.removeClass('hidden');
		that.scrollTop(error);
	},

	triggerURLFetched : function(url, options) {

		var that = this;

		// Signal that this URL was fetched. Eg: btnSetLoading
		// If not escaped, the catch doesn't work
		$(document).triggerHandler('urlfetched:'+escape(url));
		$(document).triggerHandler('urlfetched', [url, options]);
		$(document).triggerHandler('urlfetchcompleted:'+escape(url));
	},
	triggerURLFetchFailed : function(url) {

		var that = this;

		// Signal that this URL was fetched. Eg: btnSetLoading
		// If not escaped, the catch doesn't work
		$(document).triggerHandler('urlfetchfailed:'+escape(url));
		$(document).triggerHandler('urlfetchfailed', [url]);
		$(document).triggerHandler('urlfetchcompleted:'+escape(url));
	},

	processPageSectionResponse : function(domain, pageSection, url, fetchUrl, response, options) {

		var that = this;

		var target = that.getTarget(pageSection);

		// // For each URL to be intercepted, save under which page URL and target its memory has been stored
		// that.addInitialBlockMemory(response);
		
		// Integrate the DB
		// that.integrateDatabases(domain, response);

		// the function below will be overriden by pop.ManagerResourceLoaderOverride, 
		// to check if the resources for the URL have already been loaded. 
		// If not, then wait 100ms and check again, until they are loaded. Only then proceed to process the response
		that.checkExecuteProcessPageSectionResponse(domain, pageSection, url, fetchUrl, response, options);
	},

	checkExecuteProcessPageSectionResponse : function(domain, pageSection, url, fetchUrl, response, options) {

		// Allow this function to be overriden by PoP Resource Loader for doing code splitting
		var that = this;
		that.executeProcessPageSectionResponse(domain, pageSection, url, response, options);
	},

	executeProcessPageSectionResponse : function(domain, pageSection, url, response, options) {

		var that = this;

		// Add to the queue of promises to execute and merge the module
		var dfd = $.Deferred();
		var lastPromise = that.mergingPromise;
		that.mergingPromise = dfd.promise();

		// If while processing the pageSection we get error "Mempage not available",
		// do not let it break the execution of other JS, contain it
		// Comment Leo 13/09/2017: There will always be a lastPromise, since it was added on the init() function
		lastPromise.done(function() {
		
			var error = null;
			try {

				that.processPageSection(url, domain, pageSection, response, options);
				that.triggerURLFetched(url, options);
			}
			catch(err) {
				
				that.triggerURLFetchFailed(url);
				error = err;
			}

			// Resolve the deferred
			dfd.resolve();

			if (error) {

				if (pop.c.THROW_EXCEPTION_ON_TEMPLATE_ERROR) {

					throw error;
				}
				console.error(error);
			}
		});
	},

	maybeUpdateDocument : function(domain, pageSection, options) {
		
		var that = this;
		options = options || {};

		// Check if explicitly said to not update the document
		if (options.silentDocument) {
			return;
		}

		// Sometimes update (eg: main), sometimes not (eg: modal)
		var settings = that.getFetchPageSectionSettings(pageSection);
		if (settings.updateDocument) {

			if (!options.skipPushState) {
				
				var topLevelFeedback = that.getTopLevelFeedback(domain);
				pop.BrowserHistory.pushState(topLevelFeedback[pop.c.URLPARAM_URL]);
			}
			that.updateDocument(domain);
		}
	},

	processPageSection : function(url, domain, pageSection, response, options) {
		
		var that = this;

		that.integrateResponse(url, domain, response);

		// Integrate the response feedback
		// that.integrateTopLevelFeedback(domain, response);
		var topLevelFeedback = that.getTopLevelFeedback(domain);

		// Show any error message from the toplevel feedback
		var errorMessage = topLevelFeedback[pop.c.URLPARAM_ERROR];
		if (errorMessage) {
			that.showError(pageSection, errorMessage);
		}

		// If reloading the URL, then we fetched that URL from the server independently of that page already loaded in the client (ie: it will not be intercepted)
		// When the page comes back, we gotta destroy the previous one (eg: Add Highlight)
		var url = topLevelFeedback[pop.c.URLPARAM_URL];
		if (options.reloadurl) {

			// Get the url, and destroy those pages
			var target = that.getTarget(pageSection);
			that.triggerDestroyTarget(url, target);
		}

		// Set the "silent document" value return in the topLevelFeedback
		// But we still allow the value to have been set before. Eg: history.js (makes it silent when clicking back/forth on browser)
		if (topLevelFeedback[pop.c.URLPARAM_SILENTDOCUMENT]) {
			options.silentDocument = true;
		}
		that.maybeUpdateDocument(domain, pageSection, options);

		// Do a Redirect?
		if (options.maybeRedirect && that.maybeRedirect(topLevelFeedback)) {
			return;
		}

		// Integrate the response		
		// that.integratePageSectionResponse(domain, response);

		// First Check if the response also includes other pageSections. Eg: when fetching Projects, it will also bring its MainRelated
		// Check first so that later on it can refer javascript on these already created objects
		pop.JSRuntimeManager.setPageSectionURL(url);
		$.each(response.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT], function(rpssId, rpsConfiguration) {

			var psId = rpsConfiguration[pop.c.JS_FRONTENDID];//rpsConfiguration['frontend-id'];
			var pageSection = $('#'+psId);
			that.renderPageSection(domain, pageSection, null, options);
		
			// Trigger
			pageSection.triggerHandler('fetched', [options, url, domain]);
			pageSection.triggerHandler('completed');
		});	
	},

	scrollToElem : function(elem, position, animate) {

		var that = this;
		pop.JSLibraryManager.execute('scrollToElem', {elem: elem, position: position, animate: animate});
	},
	scrollTop : function(elem, top, animate) {

		var that = this;

		// This will call functions from perfectScrollbar, bootstrap modal, and custom functions
		pop.JSLibraryManager.execute('scrollTop', {elem: elem, top: top, animate: animate});
	},

	getSettingsId : function(objectOrId) {
		
		// target: pageSection or Block, or already pssId or bsId (when called from a .tmpl.js file)
		var that = this;

		if ($.type(objectOrId) == 'object') {
			
			var object = objectOrId;
			return object.attr('data-moduleoutputname');
		}

		return objectOrId;
	},

	getError : function(pageSection, url, jqXHR, textStatus, errorThrown) {

		var that = this;
		var target = that.getTarget(pageSection);
		if (jqXHR.status === 0) { // status = 0 => user is offline
			
			return pop.c.ERROR_OFFLINE.format(url, target);
		}
		else if (jqXHR.status == 404) {
			
			return pop.c.ERROR_404;
		}
		return pop.c.ERROR_MSG.format(url, target);
	},

	closeFeedbackMessage : function(elem) {
		
		var that = this;

		// Add a hook for Bootstrap to perform the action
		var args = {
			elem: elem
		};
		pop.JSLibraryManager.execute('closeFeedbackMessage', args);
	},
	closeFeedbackMessages : function(pageSection) {
		
		var that = this;

		// Add a hook for Bootstrap to perform the action
		var args = {
			pageSection: pageSection
		};
		pop.JSLibraryManager.execute('closeFeedbackMessages', args);
	},

	processBlock : function(domain, pageSection, block, options) {
		
		var that = this;

		// var pssId = that.getSettingsId(pageSection);
		// var bsId = that.getSettingsId(block);
		// var memory = that.getMemory(domain);

		// Add 'dbObjectIDs' from the dbobjectids, as to be read in scroll-inner.tmpl / carousel-inner.tmpl
		options.extendContext = {
			// dbObjectIDs: memory.statefuldata.dbobjectids[pssId][bsId],
			dbObjectIDs: that.getDataset(domain, pageSection, block),
			ignorePSRuntimeId: true
		};

		// Set the Block URL for pop.JSRuntimeManager.addModule to know under what URL to place the session-ids
		pop.JSRuntimeManager.setBlockURL(domain, block);

		that.renderTarget(domain, pageSection, block, options);
	},

	fetchBlock : function(pageSection, block, options) {
		
		var that = this;
		options = options || {};

		if (!options['skip-stopfetching-check']) {

			if (that.stopFetchingBlock(pageSection, block)) {
				return;
			}
		}

		that.executeFetchBlock(pageSection, block, options);
	},

	getBlockPostData : function(domain, pageSection, block, options) {
		
		var that = this;
		options = options || {};
		var paramsGroup = options.paramsGroup || 'all';

		var blockQueryState = that.getBlockQueryState(pageSection, block);
		var blockDomainQueryState = that.getBlockDomainQueryState(domain, pageSection, block);
		
		// Filter all params which are empty
		var params = {};
		if (paramsGroup == 'all') {
			$.extend(params, blockQueryState[pop.c.DATALOAD_PARAMS]);

			// Also add the params specific to that domain
			if (blockDomainQueryState[pop.c.DATALOAD_PARAMS]) {
				$.extend(params, blockDomainQueryState[pop.c.DATALOAD_PARAMS]);
			}
		}
		
		// Visible params: visible when using 'Open All' button
		if (blockQueryState[pop.c.DATALOAD_VISIBLEPARAMS]) {
			$.extend(params, blockQueryState[pop.c.DATALOAD_VISIBLEPARAMS]);
		}

		if (blockDomainQueryState[pop.c.DATALOAD_VISIBLEPARAMS]) {
			$.extend(params, blockDomainQueryState[pop.c.DATALOAD_VISIBLEPARAMS]);
		}
		
		$.each(params, function(key, value) {
			if (!value) delete params[key];
		});

		var post_data = $.param(params);
		if (blockQueryState.filter) {
			if (post_data) {
				post_data += '&';
			}
			post_data += blockQueryState.filter;
		}

		return post_data;
	},

	handleBlockError : function(error, jqXHR, options) {

		var that = this;

		// Remove the 'disabled' layer
		if (options['show-disabled-layer']) {
			that.getBlockDisabledLayer(block).addClass('hidden');
		}

		// First show status-specific error messages, then the general one
		// Is the user offline? (status = 0 => user is offline) Show the right message
		var msgSelectors = ['.errormsg.status-'+jqXHR.status, '.errormsg.general'];
		$.each(msgSelectors, function(index, msgSelector) {
			
			var msg = error.find(msgSelector);
			if (msg.length) {
			
				// Show that one message and disable the others
				msg.removeClass('hidden').siblings('.errormsg').addClass('hidden');

				// Stop iterating the msgSelectors, we found the one message we wanted
				return false;
			}
		});

		// Allow the "loading" and "error" message to not show up. Eg: for loadLatest, which is executed automatically
		if (!options['skip-status']) {
			error.removeClass('hidden');
			that.scrollTop(error);
		}
	},

	getMultiDomainBlockStatus : function(blockQueryState, fetch_urls, timestamp) {

		// Following instructions can be executed immediately when calling `complete`,
		// even if the merging has yet not taken place
		var status = {
			timestamp: timestamp,
			isFirst: (blockQueryState.timestamps[timestamp].length === Object.keys(fetch_urls).length),
			isLast: (blockQueryState.timestamps[timestamp].length === 1)
		};
		return status;
	},

	executeFetchBlock : function(pageSection, block, options) {
		
		var that = this;
		options = options || {};		

		// Default type: GET
		var type = options.type || 'GET';

		// If the params are already sent in the options, then use it
		// It's needed for loading the 'Edit Event' page, where the params are provided by the collapse in attr data-params
		// Override the post-data in the params, and then use it time and again (needed for the Navigator, it will set the filtering fields of the intercepted url into its post-data and send these again and again on waypoint scroll down - its own filter fields are empty!)
		var blockQueryState = that.getBlockQueryState(pageSection, block);
		if (options['post-data']) {
			blockQueryState['post-data'] = options['post-data'];
		}

		var fetch_urls = {};
		var query_urls = that.getQueryMultiDomainUrls(pageSection, block);
		$.each(query_urls, function(domain, fetchUrl) {

			// If the block has no query url, then continue to next one
			if (!fetchUrl) {
				return;
			}

			// If it must stop fetching for this one domain, then continue
			if (!options['skip-stopfetching-check']) {
				if (that.stopFetchingDomainBlock(domain, pageSection, block)) {
					return;
				}	
			}

			// Initialize the domain, if needed
			that.maybeInitializeDomain(domain);

			// Initialize the multidomain feedback, so that when requesting for the feedback from a domain different than the local one, below, it doesn't explode
			that.initMultiDomainFeedback(fetchUrl, domain, pageSection, block);

			// Allow PoP Service Workers to modify the options, adding the Network First parameter to allow to fetch the preview straight from the server
			var args = {
				domain: domain,
				options: options, 
				url: fetchUrl,
				type: type
			};
			pop.JSLibraryManager.execute('modifyFetchBlockArgs', args);
			fetchUrl = args.url;

			var topLevelFeedback = that.getTopLevelFeedback(domain);
			var pageSectionFeedback = that.getPageSectionFeedback(domain, pageSection);
			var blockFeedback = that.getBlockFeedback(domain, pageSection, block);
			
			var blockQueryStateData = $.extend({}, topLevelFeedback[pop.c.DATALOAD_PARAMS], pageSectionFeedback[pop.c.DATALOAD_PARAMS], blockFeedback[pop.c.DATALOAD_PARAMS]);
			
			var post_data = $.param(blockQueryStateData);

			if (blockQueryState['post-data']) {

				if (post_data) {
					post_data += '&';
				}
				post_data += blockQueryState['post-data'];
			}
			// onetime-post-data does not get integrated into the blockQueryState, so it will be used only when it is added through the options
			// needed for doing loadLatest
			if (options['onetime-post-data']) {

				if (post_data) {
					post_data += '&';
				}
				post_data += options['onetime-post-data'];
			}
			// Allow the blockQueryStateData to add the blockQueryState or not. Needed for the loadLatest content, where we want to get rid of pagination and other params
			var block_post_data = that.getBlockPostData(domain, pageSection, block);
			if (post_data && block_post_data) {
				post_data += '&';
			}
			post_data += block_post_data;

			// Add a param to tell the back-end we are doing ajax
			var target = that.getTarget(pageSection);
			fetchUrl = add_query_arg(pop.c.URLPARAM_TARGET, target, fetchUrl);
			fetchUrl = add_query_arg(pop.c.URLPARAM_DATAOUTPUTITEMS+'[]', pop.c.URLPARAM_DATAOUTPUTITEMS_COMPONENTDATA, fetchUrl);
			fetchUrl = add_query_arg(pop.c.URLPARAM_DATAOUTPUTITEMS+'[]', pop.c.URLPARAM_DATAOUTPUTITEMS_DATABASES, fetchUrl);
			fetchUrl = add_query_arg(pop.c.URLPARAM_DATAOUTPUTITEMS+'[]', pop.c.URLPARAM_DATAOUTPUTITEMS_SESSION, fetchUrl);
			fetchUrl = add_query_arg(pop.c.URLPARAM_OUTPUT, pop.c.URLPARAM_OUTPUT_JSON, fetchUrl);

			var loadingUrl = fetchUrl + post_data;
			// If already loading this url (user pressed twice), then do nothing
			if (blockQueryState.loading.indexOf(loadingUrl) > -1) {

				return;
			}

			// Success validating and preparing the URL, add it to the queue of URLs to fetch
			fetch_urls[domain] = {
				url: fetchUrl,
				loading: loadingUrl,
				data: post_data,
			};
		});

		// If there are URLs to fetch...
		if (!$.isEmptyObject(fetch_urls)) {

			var loading = block.find('.pop-loading');
			var error = block.find('.pop-error');

			// Allow the "loading" and "error" message to not show up. Eg: for loadLatest, which is executed automatically
			if (!options['skip-status']) {

				// Check if we must force a particular status message (eg: when loading lazy content, it should always be "Loading")
				if (options['loading-msg']) {

					// Save the current value
					var loading_msg = loading.find('.pop-loading-msg');
					var current_msg = loading_msg.html();

					// Replace with new message
					loading_msg.html(options['loading-msg']);

					// Keep current message for restoring later
					options['loading-msg'] = current_msg;
				}
				loading.removeClass('hidden');
				error.addClass('hidden');	

				// Close Message
				that.closeFeedbackMessage(block);
			}

			// Hide buttons / set loading msg
			that.triggerEvent(pageSection, block, 'beforeFetch', [options]);

			// When doing refetch, and initializing the data (aka doing GET, not POST), then show the 'disabled' layer
			if (options['show-disabled-layer']) {
				that.getBlockDisabledLayer(block).removeClass('hidden');
			}

			// Keep a timestamp to send in the status, to show that all 'fetchDomainCompleted' belong to the same operation
			// It is needed for the map, for removing markers after reloading, so that it is done only after the first domain fetching data, and not all of them
			var timestamp = Date.now();
			$.each(fetch_urls, function(domain, fetchInfo) {

				var fetchUrl = fetchInfo.url;
				var loadingUrl = fetchInfo.loading;
				var post_data = fetchInfo.data;
				var crossdomain = that.getCrossDomainOptions(fetchUrl);

				$.ajax($.extend(crossdomain, {
					dataType: "json",
					url: fetchUrl,
					data: post_data,
					type: type,
					beforeSend: function(jqXHR, settings) {

						// Addition of the URL to retrieve local information back when it comes back
						// http://stackoverflow.com/questions/11467201/jquery-statuscode-404-how-to-get-jqxhr-url
						jqXHR.url = settings.url;

						// Save the fetchUrl to retrieve it under 'complete'
						blockQueryState.url[jqXHR.url] = loadingUrl;

						// Save the url being loaded
						blockQueryState.loading.push(loadingUrl);

						// Save the url under the timestamp
						blockQueryState.timestamps[timestamp] = blockQueryState.timestamps[timestamp] || [];
						blockQueryState.timestamps[timestamp].push(loadingUrl);

						// Save the Operation in the blockQueryState
						blockQueryState.operation[loadingUrl] = options.operation;

						// Save the Action in the blockQueryState
						blockQueryState.action[loadingUrl] = options.action;

						// Save the domain, so we can retrieve it later after the fetch comes back (the URL may be modified by the ContentCDN)
						blockQueryState.domain[loadingUrl] = domain;
						
						// the url is needed to re-identify the block, since all it's given to us on the response is the moduleoutputname
						// which is not enough anymore since we can have different blocks with the same moduleoutputname, so we need to find once again the id
						blockQueryState['paramsscope-url'][loadingUrl] = that.getTargetParamsScopeURL(block);
						
						// Is it a realod?
						if (options.reload) {

							blockQueryState.reload.push(loadingUrl);				
						}
		
						if (!options['skip-status']) {
							that.handleLoadingStatus(loading, 'add');
						}
					},	
					success: function(response, textStatus, jqXHR) {

						// Allow pop-cdn to incorporate the thumbprint values in the topLevelFeedback before backgroundLoad
						that.blockFetchSuccess(pageSection, block, response);

						// Start loading extra urls
						that.backgroundLoad(response.statefuldata.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pop.c.URLPARAM_BACKGROUNDLOADURLS]);

						// loadLatest: when it comes back, hide the latestcount div
						if (options['hide-latestcount']) {
							block.children('.blocksection-latestcount')
								.find('.pop-latestcount').addClass('hidden')
								.find('.pop-count').text('0');
						}

						// Remove the 'disabled' layer. Do it here instead of fetchBlockSucess, so that the layer can be added again, if needed, in JS function blockHandleDisabledLayer
						if (options['show-disabled-layer']) {
							that.getBlockDisabledLayer(block).addClass('hidden');
						}

						// We need to pass the status down the road, so methods can know if this is the first domain processed from a list of domains or not
						// It is needed for when doing operation REPLACE, do it only the first time, but not later, or it will replace data from previous domains
						var status = that.getMultiDomainBlockStatus(blockQueryState, fetch_urls, timestamp);

						// Comment Leo 03/12/2015: Using Deferred Object for the following reason:
						// Templates for 2 URLs cannot be merged at the same time, since they both access the same settings (unique thread)
						// So we need to make the module merging process synchronous. For that we implement a queue of deferred object,
						// Where each one of them merges only after the previous one process has finished, + mergingPromise = false for the first case and when there are no other elements in the queue
						// By the end of the success function all merging will be done, then we can proceed with following item in the queue
						var lastPromise = that.mergingPromise;//[domain];
						var dfd = $.Deferred();
						var thisPromise = dfd.promise();
						that.mergingPromise = thisPromise;
						// Comment Leo 13/09/2017: There will always be a lastPromise, since it was added on the init() function
						lastPromise.done(function() {

							// Catch the "Mempage not available" exception, or the app might crash
							try {
								that.executeFetchBlockSuccess(pageSection, block, response, status, jqXHR);
							}
							catch(err) {
								// Do nothing
								console.error(err);
								// console.log('Error: '+err.message);
							}

							// Resolve this promise
							dfd.resolve();
						});
					},
					error: function(jqXHR, textStatus, errorThrown) {

						that.handleBlockError(error, jqXHR, options);
						that.triggerEvent(pageSection, block, 'fetchFailed');
					},
					complete: function(jqXHR) {

						// Following instructions can be executed immediately when calling `complete`,
						// even if the merging has yet not taken place
						var status = that.getMultiDomainBlockStatus(blockQueryState, fetch_urls, timestamp);
						var action = blockQueryState.action[loadingUrl];
						status.action = action;

						var loadingUrl = blockQueryState.url[jqXHR.url];
						delete blockQueryState.url[jqXHR.url];

						var domain = blockQueryState.domain[loadingUrl];

						// Remove the loading state of the tab
						blockQueryState.loading.splice(blockQueryState.loading.indexOf(loadingUrl), 1);
						delete blockQueryState.operation[loadingUrl];
						delete blockQueryState.action[loadingUrl];
						delete blockQueryState.domain[loadingUrl];
						delete blockQueryState['paramsscope-url'][loadingUrl];

						// Remove the URL under the timestamp, and add extra status properties while doing so
						blockQueryState.timestamps[timestamp].splice(blockQueryState.timestamps[timestamp].indexOf(loadingUrl), 1);
						if (!blockQueryState.timestamps[timestamp].length) {
							delete blockQueryState.timestamps[timestamp];
						}

						// Reload: remove if it exists
						var index = blockQueryState.reload.indexOf(loadingUrl);
						if (index > -1) {
							status.reload = true;
							blockQueryState.reload.splice(index, 1);
						}
		
						if (!options['skip-status']) {
							that.handleLoadingStatus(loading, 'remove');
						}

						// Following instructions can be executed only after the merging has finished
						var lastPromise = that.mergingPromise;
						var dfd = $.Deferred();
						var thisPromise = dfd.promise();
						that.mergingPromise = thisPromise;

						// If while processing the pageSection we get error "Mempage not available",
						// do not let it break the execution of other JS, contain it
						// Comment Leo 13/09/2017: There will always be a lastPromise, since it was added on the init() function
						lastPromise.done(function() {
							
							try {
								that.fetchBlockComplete(domain, pageSection, block, status, options);
							}
							catch(err) {
								// Do nothing
								console.error(err);
								// console.log('Error: '+err.message);
							}

							// Resolve the deferred
							dfd.resolve();
						});

						if (options['scroll-top']) {

							// Scroll Top to show the forms' checkpoint message
							pop.Manager.blockScrollTop(pageSection, block);
						}
					}
				}));
			});
		}
	},

	fetchBlockComplete: function(domain, pageSection, block, status, options) {

		var that = this;

		var blockQueryState = that.getBlockQueryState(pageSection, block);

		// Display the dataset count?
		if (options['datasetcount-target']) {
			
			that.displayDatasetCount(domain, pageSection, block, $(options['datasetcount-target']), options['datasetcount-updatetitle']);
		}

		// Only if not loading other URLs still
		if (!blockQueryState.loading.length) {
			
			var loading = block.find('.pop-loading');
			if (options['loading-msg']) {

				// Restore previous message
				var loading_msg = loading.find('.pop-loading-msg');
				loading_msg.html(options['loading-msg']);
			}
			loading.addClass('hidden');	
		}

		that.triggerEvent(pageSection, block, 'fetchDomainCompleted', [status, domain]);

		// If this is the last domain fetched
		if (status.isLast) {
			
			that.triggerEvent(pageSection, block, 'fetchCompleted', [status]);
		}
	},

	stopFetchingBlock : function(pageSection, block) {

		// There are 2 interpretations to stop fetching data for the block: with domain and without domain
		// #1 - with domain: check if that specific domain can still fetch data
		// #2 - without domain: check the whole block, i.e. all domains must have the stop-fetching flag in true
		var that = this;
		var ret = true;
		var query_urls = that.getQueryMultiDomainUrls(pageSection, block);
		$.each(query_urls, function(domain, query_url) {

			var blockQueryDomainState = that.getBlockDomainQueryState(domain, pageSection, block);
			if (!blockQueryDomainState[pop.c.URLPARAM_STOPFETCHING]) {
				ret = false;
				return -1;
			}
		});

		return ret;
	},

	stopFetchingDomainBlock : function(domain, pageSection, block) {

		var that = this;
		var blockQueryDomainState = that.getBlockDomainQueryState(domain, pageSection, block);
		return blockQueryDomainState[pop.c.URLPARAM_STOPFETCHING];
	},

	displayDatasetCount : function(domain, pageSection, block, target, updateTitle) {
		
		var that = this;

		var dataset = that.getDataset(domain, pageSection, block);
		if (dataset.length) {

			// Mode: 'add' or 'replace'
			var mode = target.data('datasetcount-mode') || 'add';
			var count = dataset.length;
			if (mode == 'add') {
				count += target.text() ? parseInt(target.text()) : 0;
			}
			if (count) {
				target
					.removeClass('hidden')
					.text(count);

				// update the title
				if (updateTitle) {
					document.title = '('+count+') '+that.documentTitle;
				}
			}
		}
	},

	initMultiDomainFeedback : function(url, domain, pageSection, block) {

		var that = this;

		// Check if the domain from which we fetched the info is different than the loaded URL
		// If that's the case, then it's data aggregation from a different website, we initialize
		// all the properties from that domain
		// var memory = that.getMemory(domain);
		var localDomain = that.getBlockTopLevelDomain(block);
		if (domain != localDomain) {

			// Clone the stateful data for this URL
			var storeData = that.getStoreData(domain);
			storeData.stateful[url] = $.extend(true, {}, storeData.stateful[that.getBlockTopLevelURL(domain, block)]);

			// var pssId = that.getSettingsId(pageSection);
			// var bsId = that.getSettingsId(block);
			// var localMemory = that.getMemory(localDomain);

			// // Copy the properties from the local memory's domain to the operating domain?
			// // This is done the first time it is accessed, eg: if memory.feedback.toplevel is empty
			// // Copy using deep, so that the copy is not done by reference. Otherwise, all domains topLevels will be overriding each other!
			// if ($.isEmptyObject(memory.statefuldata.feedback.toplevel)) {
				
			// 	memory.statefuldata.feedback.toplevel = $.extend(true, {}, localMemory.feedback.toplevel);

			// 	// Important: do NOT copy everything! In particular, do not copy the loggedin user information
			// 	if (typeof memory.statefuldata.feedback.toplevel[pop.c.DATALOAD_USER] != 'undefined') {
			// 		delete memory.statefuldata.feedback.toplevel[pop.c.DATALOAD_USER];
			// 	}
			// }
			// if ($.isEmptyObject(memory.statefuldata.feedback.pagesection[pssId])) {
				
			// 	memory.statefuldata.feedback.pagesection[pssId] = $.extend(true, {}, localMemory.feedback.pagesection[pssId]);
			// 	memory.statefuldata.feedback.block[pssId] = {};
			// }
			// if ($.isEmptyObject(memory.statefuldata.feedback.block[pssId][bsId])) {
				
			// 	memory.statefuldata.feedback.block[pssId][bsId] = $.extend(true, {}, localMemory.feedback.block[pssId][bsId]);
			// }
		}
	},

	// initMultiDomainMemory : function(domain, pageSection, block, response) {

	// 	var that = this;

	// 	// Check if the domain from which we fetched the info is different than the loaded URL
	// 	// If that's the case, then it's data aggregation from a different website, we initialize
	// 	// all the properties from that domain
	// 	var memory = that.getMemory(domain);
	// 	var localDomain = that.getBlockTopLevelDomain(block);
	// 	if (domain != localDomain) {

	// 		var localMemory = that.getMemory(localDomain);

	// 		// Copy the properties from the local memory's domain to the operating domain?
	// 		// This is done the first time it is accessed, eg: if memory.feedback.toplevel is empty
	// 		$.each(response.statefuldata.querystate.sharedbydomains, function(rpssId, rpsParams) {	

	// 			// If the memory is empty (eg: first time that we are loading a different domain), then recreate it under the domain scope
	// 			if ($.isEmptyObject(memory.statefuldata.querystate.sharedbydomains[rpssId])) {

	// 				memory.statefuldata.querystate.sharedbydomains[rpssId] = {};
	// 				memory.statefuldata.querystate.uniquetodomain[rpssId] = {};
	// 				memory.statefuldata.dbobjectids[rpssId] = {};

	// 				memory.statelessdata.settings['js-settings'][rpssId] = {};
	// 				memory.statefuldata.settings['js-settings'][rpssId] = {};
	// 				memory.statefuldata.settings.jsmethods.pagesection[rpssId] = $.extend({}, localMemory.settings.jsmethods.pagesection[rpssId]);
	// 				memory.statefuldata.settings.jsmethods.block[rpssId] = {};
	// 				memory.statelessdata.settings['modules-cbs'][rpssId] = {};
	// 				memory.statelessdata.settings['modules-paths'][rpssId] = {};
	// 				memory.statelessdata.settings['db-keys'][rpssId] = {};
	// 				memory.statefuldata.settings.configuration[rpssId] = {};
	// 				memory.statelessdata.settings.configuration[rpssId] = {};
	// 				memory.statelessdata.statelessdata.settings.configuration[rpssId][pop.c.JS_SUBCOMPONENTS] = {};

	// 				// Configuration: first copy the modules, and then the 1st level configuration => pageSection configuration
	// 				// This is a special case because the blocks are located under 'modules', so doing $.extend will override the existing modules in 'memory', however we want to keep them
	// 				var psConfiguration = memory.statelessdata.settings.configuration[rpssId];
	// 				var lpsConfiguration = localMemory.settings.configuration[rpssId];
	// 				$.each(lpsConfiguration, function(key, value) {

	// 					// Do not process the key modules, that will be done later
	// 					if (key == pop.c.JS_SUBCOMPONENTS || key == 'modules') return;

	// 					// Do not process the root-context and parent-context keys, which contain inner references,
	// 					// to avoid JS error "Maximum call stack size called" when doing the deep extend below
	// 					if (key == 'root-context' || key == 'parent-context') return;

	// 					// If it is an array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
	// 					// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
	// 					that.copyToConfiguration(key, value, psConfiguration, true);
	// 				});
	// 			}

	// 			$.each(rpsParams, function(rbsId, rbParams) {

	// 				// Comment Leo 10/08/2017: IMPORTANT: No need to use `deep` copy when doing $.extend() below, except for the `configuration`!!!
	// 				// Because all properties will not be modified across domains, then copy by reference will work.
	// 				// However, for the configuration, Handlebars will modify it per domain (setting the context), so they must be copied by copy, not by reference!
	// 				// If the memory is empty (eg: first time that we are loading a different domain), then recreate it under the domain scope
	// 				if ($.isEmptyObject(memory.statefuldata.querystate.sharedbydomains[rpssId][rbsId])) {

	// 					memory.statefuldata.querystate.sharedbydomains[rpssId][rbsId] = $.extend({}, localMemory.querystate.sharedbydomains[rpssId][rbsId]);
	// 					memory.statefuldata.querystate.uniquetodomain[rpssId][rbsId] = $.extend({}, localMemory.querystate.uniquetodomain[rpssId][rbsId]);
	// 					memory.statefuldata.dbobjectids[rpssId][rbsId] = $.extend({}, localMemory.dbobjectids[rpssId][rbsId]);

	// 					memory.statefuldata.settings['js-settings'][rpssId][rbsId] = $.extend({}, localMemory.settings['js-settings'][rpssId][rbsId]);
	// 					memory.statelessdata.settings['js-settings'][rpssId][rbsId] = $.extend({}, localMemory.settings['js-settings'][rpssId][rbsId]);
	// 					memory.statelessdata.settings.jsmethods.block[rpssId][rbsId] = $.extend({}, localMemory.settings.jsmethods.block[rpssId][rbsId]);
	// 					memory.statelessdata.settings['modules-cbs'][rpssId][rbsId] = $.extend({}, localMemory.settings['modules-cbs'][rpssId][rbsId]);
	// 					memory.statelessdata.settings['modules-paths'][rpssId][rbsId] = $.extend({}, localMemory.settings['modules-paths'][rpssId][rbsId]);
	// 					memory.statelessdata.settings['db-keys'][rpssId][rbsId] = $.extend({}, localMemory.settings['db-keys'][rpssId][rbsId]);
						
	// 					// Comment Leo 10/08/2017: this comment below actually doesn't work, so I had to remove the `that.mergingPromise` keeping a promise per domain...
	// 					// // Modules under the first level configuration
	// 					// // Comment Leo 10/08/2017: IMPORTANT: Using deep copy just for the configuration, because
	// 					// // it will be modified by Handlebars when printing the HTML (adding the variables to the context),
	// 					// // so then all configurations from different domains must be copies and cannot reference to the same original configuration
	// 					// // Otherwise, we can't print the HTML for different domains concurrently, as it is done now (check that `that.mergingPromise` keeps a promise per domain,
	// 					// // so these can be printed concurrently)
	// 					// Doing deep copy, so that the domain memory does not override the local domain
	// 					// We gotta delete keys 'root-context' and 'parent-context' first, otherwise the deep copy does not work, we will get
	// 					// JS error "Maximum call stack size called" when doing the deep extend below
	// 					var bConfiguration = localMemory.settings.configuration[rpssId][pop.c.JS_SUBCOMPONENTS][rbsId];
	// 					delete bConfiguration['root-context'];
	// 					delete bConfiguration['parent-context'];
	// 					memory.statelessdata.settings.configuration[rpssId][pop.c.JS_SUBCOMPONENTS][rbsId] = $.extend(true, {}, bConfiguration);
	// 				}
	// 			});
	// 		});
	// 	}
	// },

	integrateResponse : function(url, domain, response) {

		var that = this;

		var storeData = that.getStoreData(domain);

		// Databases and stateless data can be integrated straight
		$.extend(true, storeData.databases, response.databases || {});
		// $.extend(true, storeData.statelessdata, response.statelessdata || {});
	},

	executeFetchBlockSuccess : function(pageSection, block, response, status, jqXHR) {

		var that = this;

		var blockQueryState = that.getBlockQueryState(pageSection, block);

		// And finally process the block
		var loadingUrl = blockQueryState.url[jqXHR.url];
		var action = blockQueryState.action[loadingUrl];
		var url = blockQueryState['paramsscope-url'][loadingUrl];
		var runtimeOptions = {url: url};
		var processOptions = {operation: blockQueryState.operation[loadingUrl], action: blockQueryState.action[loadingUrl], 'fetch-status': status};

		// Comment Leo 08/08/2017: we need to keep the domain in the params, instead of extracting it from `loadingUrl`,
		// because this URL will be different when using the ContentCDN, so the domain that comes back would
		// be different to the one we used to set the properties, before executing the block data fetch
		// // Check if the domain from which we fetched the info is different than the loaded URL
		// // If that's the case, then it's data aggregation from a different website, we initialize
		// // all the properties from that domain
		var domain = blockQueryState.domain[loadingUrl];
		// that.initMultiDomainMemory(domain, pageSection, block, response);

		// var memory = that.getMemory(domain);
		that.integrateResponse(loadingUrl, domain, response);

		// // Restore initial runtimeConfiguration: eg, for TPP Debate website, upon loading a single post, 
		// // it will trigger to load the "After reading this" Add Stance with the already-submitted stance, when it comes back
		// // it must make sure to draw the original configuration, that's why restoring it below. Otherwise,
		// // if clicking quick in 2 posts before the loading is finished, the configuration gets overwritten and the 1st post
		// // is contaminated with configuration from the 2nd post
		// var restoreinitial_actions = [pop.c.CBACTION_LOADCONTENT, pop.c.CBACTION_REFETCH, pop.c.CBACTION_RESET];
		// var restoreinitial = restoreinitial_actions.indexOf(action) > -1;
		// if (restoreinitial) {
		// 	var initialMemory = that.getInitialBlockMemory(that.getBlockTopLevelURL(block)/*runtimeOptions.url*/);
		// 	$.each(response.statefuldata.querystate.sharedbydomains, function(rpssId, rpsParams) {	
		// 		$.each(rpsParams, function(rbsId, rbParams) {
		// 			// Use a direct reference instead of $.extend() because this one creates mess by messing up references when loading other
		// 			// modules, since their initialBlockMemory will be cross-referencing and overriding each other
		// 			memory.runtimesettings.configuration[rpssId][rbsId] = initialMemory.runtimesettings.configuration[rpssId][rbsId];
		// 		});
		// 	});
		// }

		// Integrate topLevel
		// that.integrateTopLevelFeedback(domain, response);

		// Integrate the response data into the moduleSettings
		// that.integrateDatabases(domain, response);

		// Integrate the block
		$.each(response.statefuldata.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT], function(rpssId, rpsParams) {

			var psConfiguration = that.getPageSectionConfiguration(domain, rpssId);
			var rPageSection = $('#'+psConfiguration.id);
			// if (!memory.statefuldata.feedback.block[rpssId]) {
			// 	memory.statefuldata.feedback.block[rpssId] = {};
			// }
			// $.extend(memory.statefuldata.feedback.block[rpssId], response.statefuldata.feedback.block[rpssId]);
			// if (!memory.statefuldata.dbobjectids[rpssId]) {
			// 	memory.statefuldata.dbobjectids[rpssId] = {};
			// }
			// $.extend(memory.statefuldata.dbobjectids[rpssId], response.statefuldata.dbobjectids[rpssId]);
			// if (!memory.statefuldata.querystate.sharedbydomains[rpssId]) {
			// 	memory.statefuldata.querystate.sharedbydomains[rpssId] = {};
			// }
			// if (!memory.statefuldata.querystate.uniquetodomain[rpssId]) {
			// 	memory.statefuldata.querystate.uniquetodomain[rpssId] = {};
			// }
				
			$.each(rpsParams, function(rbsId, rbParams) {

				try {
					// Integrate the params
					// If the user closed the tab that originated the ajax call before the response, then the params do not exist anymore
					// and getBlockParams will throw an exception. Then catch it, and do nothing else (no need anyway!)
					var rBlock = $('#'+that.getBlockId(rpssId, rbsId, runtimeOptions));

					// Comment Leo 04/12/2015: IMPORTANT: This extend below must be done always, even if `processblock-ifhasdata` condition below applies
					// and so we skip the merging, however the params must be set for later use. Eg: TPP Debate Create Stance block
					// skip-params: used for the loadLatest, so that the response of the params is not integrated back, messing up the paged, limit, etc
					$.extend(that.getBlockQueryState(rPageSection, rBlock, runtimeOptions), rbParams);
					// Also extend the domain querystate
					$.extend(that.getBlockDomainQueryState(domain, rPageSection, rBlock, runtimeOptions), response.statefuldata.querystate.uniquetodomain[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][rpssId][rbsId]);

					// When loading content, we can say to re-draw the block if there is data to be drawn, and do nothing instead
					// This is needed for the "Add your Thought on TPP": if the user is not logged in, and writes a Thought, and then logs in,
					// then the Thought must not be overridden
					var process_actions = [pop.c.CBACTION_LOADCONTENT, pop.c.CBACTION_REFETCH];
					if (process_actions.indexOf(action) > -1) {

						var jsSettings = that.getJsSettings(domain, rPageSection, rBlock);
						if (jsSettings['processblock-ifhasdata'] && !response.statefuldata.dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][rpssId][rbsId].length) {
							return;	
						}
					}

					// And finally process the block
					that.processBlock(domain, rPageSection, rBlock, processOptions);
				}
				catch(err) {
					// Do nothing
					console.error(err);
					// console.log('Error: '+err.message);
					// console.trace();
				}
			});
		});

		block.triggerHandler('fetched', [response, action, domain]);
	},

	triggerEvent : function(pageSection, block, event, args) {

		var that = this;

		// Trigger on this block
		block.triggerHandler(event, args);
	},

	// integrateTopLevelFeedback : function(domain, response) {
	
	// 	var that = this;
	// 	var tlFeedback = that.getMemory(domain).statefuldata.feedback.toplevel;

	// 	// Integrate the response into the topLevelFeedback
	// 	// Iterate all fields from the response topLevel. If it's an object, extend it. if not, just copy the value
	// 	// This is done so that previously sent values (eg: lang, sent only on loading_frame()) are not overridden.
	// 	$.each(response.statefuldata.feedback.toplevel, function(key, value) {

	// 		// If it is an empty array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
	// 		// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
	// 		if ($.type(value) == 'array' && value.length == 0) {
	// 			// do Nothing
	// 		}
	// 		else if ($.type(value) == 'object') {

	// 			// If it is an object, extend it. If not, just assign the value
	// 			if (!tlFeedback[key]) {
	// 				tlFeedback[key] = {};
	// 			}
	// 			$.extend(tlFeedback[key], value);
	// 		}
	// 		else {
	// 			tlFeedback[key] = value;
	// 		}
	// 	});
	// },

	// integrateDatabase : function(database, responsedb) {
	
	// 	var that = this;

	// 	// Integrate the response Database into the database
	// 	$.each(responsedb, function(dbKey, dbObjectIDAttributes) {

	// 		// Initialize DB entry
	// 		database[dbKey] = database[dbKey] || {};

	// 		// When there are no elements in dbObjectIDAttributes, the object will appear not as an object but as an array
	// 		// In that case, it will be empty, so skip
	// 		if ($.type(dbObjectIDAttributes) == 'array') {
	// 			return;
	// 		}

	// 		// Extend with new values
	// 		$.each(dbObjectIDAttributes, function(objectID, dbObjectAttributes) {

	// 			if (!database[dbKey][objectID]) {
	// 				database[dbKey][objectID] = {};
	// 			}
	// 			$.extend(database[dbKey][objectID], dbObjectAttributes);
	// 		});
	// 	});
	// },

	getMergeTargetContainer : function(target) {
	
		var that = this;

		if (target.data('merge-container')) {
			return $(target.data('merge-container'));
		}

		return target;
	},

	getMergeClass : function(moduleName) {
	
		var that = this;
		return pop.c.CLASSPREFIX_MERGE + moduleName;
	},

	getMergeTarget : function(target, moduleName, options) {
	
		var that = this;
		options = options || {};

		var selector = '.' + that.getMergeClass(moduleName);

		// Allow to set the target in the options. Eg: used in the Link Fullview feed to change the src of each iframe when Google translating
		var mergeTarget = options['merge-target'] ? $(options['merge-target']) : target.find(selector).addBack(selector);

		return that.getMergeTargetContainer(mergeTarget);
	},

	generateUniqueId : function(domain) {

		var that = this;

		// Create a new uniqueId
		var unique = Date.now();

		// assign it to the toplevel feedback
		// var tlFeedback = that.getTopLevelFeedback(domain);
		// tlFeedback[pop.c.UNIQUEID] = unique;

		// Implement
		console.log('Watch out! Commented out 2 lines above!');

		return unique;
	},

	getUniqueId : function(domain) {

		var that = this;

		// var tlFeedback = that.getTopLevelFeedback(domain);
		// return tlFeedback[pop.c.UNIQUEID];
		return that.getRequestMeta(domain)[pop.c.UNIQUEID];
	},

	addUniqueId : function(url) {

		var that = this;

		var domain = getDomain(url);
		var unique = that.getUniqueId(domain);
		return url+'#'+unique;
	},

	mergeTargetModule : function(domain, pageSection, target, moduleName, options) {
	
		var that = this;
		options = options || {};

		var rerender_actions = [pop.c.CBACTION_LOADCONTENT, pop.c.CBACTION_REFETCH, pop.c.CBACTION_RESET];
		var rerender = rerender_actions.indexOf(options.action) > -1;
		if (rerender) {
			// When rerendering, create the unique-id again, since not all the components allow to re-create a new component with an already-utilized id (eg: editor.js)
			that.generateUniqueId(domain);
		}
		
		var html = that.getModuleHtml(domain, pageSection, target, moduleName, options);
		var targetContainer = that.getMergeTarget(target, moduleName, options);

		// Default operation: REPLACE
		options.operation = options.operation || pop.c.URLPARAM_OPERATION_REPLACE;

		// Delete all children before appending?
		if (options.operation == pop.c.URLPARAM_OPERATION_REPLACE) {

			// Allow others to do something before this is all gone (eg: destroy LocationsMap so it can be regenerated using same id)
			target.triggerHandler('replace', [options.action]);
			
			// Needed because of module POP_TEMPLATE_FORM_INNER function get_module_cb_actions (processors/system/structures-inner.php)
			// When any of these actions gets executed, the form will actually be re-drawn (ie not just data coming from the server, but all the components inside the form will be rendered again)
			// This is needed when intercepting Edit Project and then, on the fly, loading that Project data to edit. This is currently not implemented
			if (rerender) {
			
				target.triggerHandler('rerender', [options.action]);
			}

			targetContainer.empty();
		}
		var merged = that.mergeHtml(html, targetContainer, options.operation);

		// Call the callback javascript functions under the moduleBlock (only aggregator one for PoPs)
		target.triggerHandler('merged', [merged.newDOMs]);

		return merged;
	},

	mergeHtml : function(html, container, operation) {

		var that = this;

		// We can create the element first and then move it to the final destination, and it will be the same:
		// From https://api.jquery.com/append/:
		// "If an element selected this way is inserted into a single location elsewhere in the DOM, it will be moved into the target (not cloned)"
		var newDOMs = $(html);
		if (operation == pop.c.URLPARAM_OPERATION_PREPEND) {
			container.prepend(newDOMs);
		}
		else if (operation == pop.c.URLPARAM_OPERATION_REPLACE) {
			container.html(newDOMs);
		}
		else if (operation == pop.c.URLPARAM_OPERATION_REPLACEINLINE) {
			container.replaceWith(newDOMs);
			container = newDOMs;
		}
		else {
			// Append by default
			container.append(newDOMs);
		}

		that.triggerHTMLMerged();

		return {targetContainer: container, newDOMs: newDOMs};
	},

	triggerHTMLMerged : function() {

		var that = this;

		// Needed for the calendar to know when the element is finally inserted into the DOM, to be able to operate with it
		$(document).triggerHandler('component:merged');
	},

	renderPageSection : function(domain, pageSection, priority, options) {
	
		var that = this;
		options = options || {};

		$.extend(options, that.getFetchPageSectionSettings(pageSection));

		// If doing server-side rendering, then no need to render the view using javascript templates,
		// however we must still identify the newDOMs as to execute the JS on the elements
		// Allow PoP Server-Side Rendering to inject its own logic here
		var args = {
			newDOMs: null,
			domain: domain, 
			pageSection: pageSection, 
			priority: priority, 
			options: options,
		};
		pop.JSLibraryManager.execute('renderPageSectionDOMs', args);
		
		var newDOMs = args.newDOMs;
		if (!newDOMs) {

			// PoP Server-Side Rendering didn't do anything, so create the DOMs now through JS
			newDOMs = that.renderTarget(domain, pageSection, pageSection, options);
		}

		// Sometimes no newDOMs are actually produced. Eg: when calling /userloggedin-data
		// So then do not call pageSectionRendered, or it can make mess (eg: it scrolls up when /userloggedin-data comes back)
		if (newDOMs.length) {

			that.pageSectionRendered(domain, pageSection, newDOMs, priority, options);
		}
		// If first loading the site, we still need to execute the Javascript
		// for pageSections without elements inside, such as pageSection addons
		else if (that.isFirstLoad(pageSection)) {
				
			that.initPageSectionBranches(domain, pageSection, priority, options);
		}
	},

	getPageSectionDOMs : function(domain, pageSection) {
	
		var that = this;

		var modules_cbs = that.getModulesCbs(domain, pageSection, pageSection);
		var targetContainers = $();
		var newDOMs = $();
		$.each(modules_cbs, function(index, moduleName) {

			// The DOMs are the existing elements on the pageSection merge target container
			var targetContainer = that.getMergeTarget(pageSection, moduleName);
			targetContainers.add(targetContainer);
			newDOMs = newDOMs.add(targetContainer.children());
		});

		that.triggerRendered(domain, pageSection, newDOMs, targetContainers);

		return newDOMs;
	},

	renderTarget : function(domain, pageSection, target, options) {
	
		var that = this;

		options = options || {};

		// Default operation: REPLACE, unless it is multidomain and processing a 2nd domain in that block, in which data could be replacing the just added data from other domains
		// Eg: feedbackmessage when there are no results from the first domain, may be overriten by a second domain
		var fetchStatus = options['fetch-status'] || {isFirst: true, isLast: true};
		
		// Comment Leo 30/08/2017: Because the options will be passed to other javascript functions through event 'beforeMerge',
		// we need to keep consistency of the operation and the options operation. 
		// Otherwise, in fullcalendar.js, it will get the REPLACE operation for multidomain instead of an APPEND, deleting all events from previous domains when doing a reset
		options.operation = options.operation || (fetchStatus.isFirst ? pop.c.URLPARAM_OPERATION_REPLACE : pop.c.URLPARAM_OPERATION_APPEND);
		// Special case multidomain: if the operation is REPLACE, but it is not the first element, then APPEND, or else the data from the 2nd, 3rd, etc, domains will replace the preceding ones
		if (options.operation == pop.c.URLPARAM_OPERATION_REPLACE && !fetchStatus.isFirst) {
			options.operation = pop.c.URLPARAM_OPERATION_APPEND;
		}

		// And having set-up all the handlers, we can trigger the handler
		target.triggerHandler('beforeRender', [options]);

		var modules_cbs = that.getModulesCbs(domain, pageSection, target, options.action);
		var targetContainers = $();
		var newDOMs = $();
		$.each(modules_cbs, function(index, moduleName) {

			var merged = that.mergeTargetModule(domain, pageSection, target, moduleName, options);
			targetContainers = targetContainers.add(merged.targetContainer);
			newDOMs = newDOMs.add(merged.newDOMs);
		});

		that.triggerRendered(domain, target, newDOMs, targetContainers);

		return newDOMs;
	},

	triggerRendered : function(domain, target, newDOMs, targetContainers) {
	
		var that = this;

		target.triggerHandler('rendered', [newDOMs, targetContainers, domain]);
		$(document).triggerHandler('rendered', [target, newDOMs, targetContainers, domain]);
	},

	getPageSectionConfiguration : function(domain, pageSection) {
	
		var that = this;
		
		var pssId = that.getSettingsId(pageSection);

		return that.getCombinedStateData(domain).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId];
		// return $.extend(true, {}, that.getStatefulData(domain, that.getPageSectionTopLevelURL(domain, pageSection)).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId], that.getStatelessData(domain).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId]);
		// return that.getMemory(domain).statelessdata.settings.configuration[pssId];
		return that.getStatelessData(domain).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId];
	},

	getTargetConfiguration : function(domain, pageSection, target, moduleName) {
	
		var that = this;
		var modulePath = that.getModulePath(domain, pageSection, target, moduleName);
		var targetConfiguration = that.getPageSectionConfiguration(domain, pageSection);
		
		// Go down all levels of the configuration, until finding the level for the module-cb
		if (modulePath) {
			$.each(modulePath, function(index, pathLevel) {

				targetConfiguration = targetConfiguration[pathLevel];
			});
		}

		// We reached the target configuration. Now override with the new values
		return targetConfiguration;
	},

	getModuleHtml : function(domain, pageSection, target, moduleName, options, dbKey, objectID) {

		var that = this;
		var targetConfiguration = that.getTargetConfiguration(domain, pageSection, target, moduleName);
		options = options || {};

		var targetContext = targetConfiguration;

		// If merging a subcomponent (eg: appending data to Carousel), then we need to recreate the block Context
		var modulePath = that.getModulePath(domain, pageSection, target, moduleName);
		if (modulePath.length) {
			var block = that.getBlock(target);
			that.initContextSettings(domain, pageSection, block, targetContext);
			that.extendContext(targetContext, domain, dbKey, objectID);
		}

		// extendContext: don't keep the overriding in the configuration. This way, we can use the preloading without having to reset
		// the configurations to an original value (eg: for featuredImage, it copies the img on the settings)
		var extendContext = options.extendContext;
		if (extendContext) {
			targetContext = $.extend({}, targetContext, extendContext);
		}

		return that.getHtml(domain, moduleName, targetContext);
	},

	extendContext : function(context, domain, dbKey, objectID, override) {

		// If merging a subcomponent (eg: appending data to Carousel), then we need to recreate the block Context
		// Also used from within function enterModules to create the context to pass to each module
		var that = this;
		override = override || {};
		$.extend(context, override);

		// Load dbObject?
		if (dbKey) {

			$.extend(context, {dbKey: dbKey});
			if (objectID) {

				var dbObject = that.getDBObject(domain, dbKey, objectID);
				$.extend(context, {dbObject: dbObject, dbObjectDBKey: dbKey});
			}
		}
	},

	getBlockDefaultParams : function() {
	
		var that = this;

		return {
			url: {},
			loading: [],
			reload: [],
			operation: {},
			action: {},
			domain: {},
			timestamps: {},
			'paramsscope-url': {},
			'post-data': '',
			// Filter params are actually initialized in setFilterParams function. 
			// That is because a filter knows its moduleBlock, but a moduleBlock not its filter
			// (eg: in the sidebar)
			filter: ''
		};
	},
	getBlockDefaultMultiDomainParams : function() {
	
		var that = this;
		return {};
	},
	getPageSectionDefaultParams : function() {
	
		var that = this;

		return {
			url: {},
			target: {},
			'fetch-url': {},
			loading: []
		};
	},
	
	// initPageSectionRuntimeMemory : function(domain, pageSection, options) {
	
	// 	// Initialize TopLevel / Blocks from the info provided in the feedback
	// 	var that = this;

	// 	var runtimeMempage = that.newRuntimeMemoryPage(domain, pageSection, pageSection, options);

	// 	var pssId = that.getSettingsId(pageSection);
	// 	runtimeMempage.querystate = that.getPageSectionDefaultParams();

	// 	// Allow JS libraries to hook up and initialize their own params
	// 	var args = {
	// 		domain: domain,
	// 		pageSection: pageSection,
	// 		runtimeMempage: runtimeMempage
	// 	};
	// 	pop.JSLibraryManager.execute('initPageSectionRuntimeMemory', args);
	// 	pop.JSLibraryManager.execute('initPageSectionRuntimeMemoryIndependent', args);
	// },
	// initBlockRuntimeMemory : function(domain, pageSection, block, options) {
	
	// 	// Initialize TopLevel / Blocks from the info provided in the feedback
	// 	var that = this;

	// 	var runtimeMempage = that.newRuntimeMemoryPage(domain, pageSection, block, options);

	// 	var tlFeedback = that.getTopLevelFeedback(domain);
	// 	var url = tlFeedback[pop.c.URLPARAM_URL];
	// 	var statefulData = that.getStatefulData(domain, url);

	// 	// var memory = that.getMemory(domain);
	// 	var pssId = that.getSettingsId(pageSection);
	// 	var bsId = that.getSettingsId(block);

	// 	statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
	// 	statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS] = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS] || {};
	// 	statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS][bsId] = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS][bsId] || {};
	// 	var runtimeConfiguration = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS][bsId];

	// 	runtimeMempage.id = block.attr('id');
	// 	runtimeMempage['query-url'] = runtimeConfiguration['query-url'];
	// 	runtimeMempage['query-multidomain-urls'] = {};

	// 	// Params: it is slit into 2: #1. block params, and #2. dataloader-source-params (multidomain-params)
	// 	// #1 contains params that are unique to the block, which are equally posted to all sources, eg: filter
	// 	// #2 contains params whose value can change from source to source, eg: stop-loading
	// 	runtimeMempage.querystate = {
	// 		sharedbydomains: $.extend(that.getBlockDefaultParams(), statefulData.querystate.sharedbydomains[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId]),
	// 		uniquetodomain: {},
	// 	};
	// 	var multidomain_urls = runtimeConfiguration['query-multidomain-urls'];
	// 	$.each(multidomain_urls, function(index, query_url) {

	// 		var query_url_domain = getDomain(query_url);
	// 		runtimeMempage['query-multidomain-urls'][query_url_domain] = query_url;
	// 		runtimeMempage.querystate.uniquetodomain[query_url_domain] = $.extend(that.getBlockDefaultMultiDomainParams(), statefulData.querystate.uniquetodomain[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId]);
	// 	})

	// 	// Allow JS libraries to hook up and initialize their own params
	// 	var args = {
	// 		domain: domain,
	// 		pageSection: pageSection,
	// 		block: block,
	// 		runtimeMempage: runtimeMempage
	// 	};
	// 	pop.JSLibraryManager.execute('initBlockRuntimeMemory', args);
	// 	pop.JSLibraryManager.execute('initBlockRuntimeMemoryIndependent', args);
	// },

	getViewport : function(pageSection, el) {
	
		var that = this;
		var viewport = el.closest('.pop-viewport');
		if (viewport.length) {
			return viewport;
		}

		// Default: the pageSection
		return pageSection;
	},

	getDOMContainer : function(pageSection, el) {
	
		var that = this;
		if (pop.c.DOMCONTAINER_ID) {
			return $('#'+pop.c.DOMCONTAINER_ID);
		}

		// Default: the viewport
		return that.getViewport(pageSection, el);
	},

	getBlock : function(el) {
	
		var that = this;
		if (el.hasClass('pop-block')) {

			return el;
		}
		if (el.closest('.pop-block').length) {
		
			return el.closest('.pop-block');
		}

		// This is needed for the popover: since it is appended to the pop-pagesection, it knows what block it 
		// belongs to thru element with attr data-block
		if (el.closest('[data-block]').length) {
		
			return $(el.closest('[data-block]').data('block'));
		}
		
		return null;
	},

	getTargetParamsScopeURL : function(target) {

		var that = this;
		return target.data(pop.c.PARAMS_PARAMSSCOPE_URL);
	},
	getPageSectionTopLevelURL : function(domain, pageSection) {

		var that = this;
		// return window.location.href;
		if ($.type(pageSection) == 'object') {
			pageSection.data(pop.c.PARAMS_PARAMSSCOPE_URL);
		}
		return that.getTopLevelFeedback(domain)[pop.c.URLPARAM_URL];
		// return pageSection.data(pop.c.PARAMS_TOPLEVEL_URL);
	},
	getBlockTopLevelURL : function(domain, block) {

		var that = this;
		// return window.location.href;
		if ($.type(block) == 'object') {
			return block.data(pop.c.PARAMS_TOPLEVEL_URL);
		}

		return that.getTopLevelFeedback(domain)[pop.c.URLPARAM_URL];
		// return that.getPageSectionTopLevelURL(domain, that.getPageSection(block));
		// return that.getPageSectionTopLevelURL(that.getPageSectionPage(block));
	},
	getBlockTopLevelDomain : function(block) {

		var that = this;
		return block.data(pop.c.PARAMS_TOPLEVEL_DOMAIN);
	},

	isBlockGroup : function(block) {
	
		var that = this;		
		return block.hasClass('pop-blockgroup');
	},

	getBlockGroupActiveBlock : function(blockGroup) {
	
		var that = this;
		
		// Supposedly only 1 active pop-block can be inside a group of BlockGroups, so this should either
		// return 1 result or none
		return blockGroup.find('.tab-pane.active .pop-block, .collapse.in .pop-block');
	},

	getBlockGroupBlocks : function(blockGroup) {
	
		var that = this;
		return blockGroup.find('.pop-block');
	},

	getTemplate : function(domain, moduleOrTemplateName) {
	
		// If empty, then the module is already the template
		var that = this;		
		var templates = that.getTemplates(domain);
		return templates[moduleOrTemplateName] || moduleOrTemplateName;
	},

	// initPageSectionSettings : function(domain, pageSection, psConfiguration) {
	
	// 	// Initialize TopLevel / Blocks from the info provided in the feedback
	// 	var that = this;

	// 	var tls = that.getTopLevelSettings(domain);
	// 	$.extend(psConfiguration, {tls: tls});

	// 	var pss = that.getPageSectionSettings(domain, pageSection);
	// 	var pssId = that.getSettingsId(pageSection);
	// 	var psId = psConfiguration[pop.c.JS_FRONTENDID];
	// 	$.extend(psConfiguration, {pss: pss});	
	// 	// // In addition, the pageSection must merge with the runtimeConfiguration, which is otherwise done in function enterModule
	// 	// $.extend(psConfiguration, that.getRuntimeConfiguration(domain, pssId, pssId, psConfiguration[pop.c.JS_COMPONENT]));	

	// 	// Expand the JS Keys for the configuration
	// 	that.expandJSKeys(psConfiguration);

	// 	var storeData = that.getStoreData(domain);
	// 	var url = "temporary-hack";

	// 	// Fill each block configuration with its pssId/bsId/settings
	// 	if (psConfiguration[pop.c.JS_SUBCOMPONENTS]) {
	// 		$.each(psConfiguration[pop.c.JS_SUBCOMPONENTS], function(bsId, bConfiguration) {
	// 	// if (storeData.statefuldata[url].settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS]) {
	// 	// 	$.each(storeData.statefuldata[url].settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS], function(bsId, rbConfiguration) {
				
	// 			var bConfiguration = psConfiguration[pop.c.JS_SUBCOMPONENTS][bsId];
	// 			var bId = bConfiguration[pop.c.JS_FRONTENDID];
	// 			// The blockTopLevelDomain is the same as the domain when initializing the pageSection
	// 			var bs = that.getBlockSettings(domain, domain, pssId, bsId, psId, bId);
	// 			$.extend(bConfiguration, {tls: tls, pss: pss, bs: bs});	

	// 			// Expand the JS Keys for the configuration
	// 			that.expandJSKeys(bConfiguration);
	// 		});	
	// 	}
	// },

	initContextSettings : function(domain, pageSection, block, context) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var that = this;

		var tls = that.getTopLevelSettings(domain);
		$.extend(context, {tls: tls});

		var pss = that.getPageSectionSettings(domain, pageSection);
		$.extend(context, {pss: pss});	

		var pssId = that.getSettingsId(pageSection);
		var psId = pageSection.attr('id');
		var bsId = that.getSettingsId(block);
		var bId = block.attr('id');
		var bs = that.getBlockSettings(domain, that.getBlockTopLevelDomain(block), pssId, bsId, psId, bId);
		$.extend(context, {pss: pss, bs: bs});	

		// Expand the JS Keys for the configuration
		that.expandJSKeys(context);

		// If there's no dbKey, also add it
		// This is because there is a bug: first loading /log-in/, it will generate the settings adding dbKey when rendering
		// the module down the path. However, it then calls /loaders/initial-frames?target=main, and it will bring 
		// again the /log-in preloading settings, which will override the ones from the log-in window that is open, making it lose the dbKey,
		// which is needed by the content-inner module.
		if (!context.dbKey) {
			context.dbKey = bs.dbkeys.id;
		}
	},

	copyToConfiguration : function(key, value, configuration, deep) {

		var that = this;

		// If it is an array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
		// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
		if ($.type(value) == 'array') {
			configuration[key] = {};
		}
		else if ($.type(value) == 'object') {
			// If it is an object, extend it. If not, just assign the value
			if (!configuration[key]) {
				configuration[key] = {};
			}

			// If copying from the JSON response to the local memory, no need for deep, reference is good
			// If copying from the local memory to a domain memory, must make it deep, so that references are not shared and the memory is not overriden by accident
			if (deep) {
				$.extend(true, configuration[key], value);
			}
			else {
				$.extend(configuration[key], value);
			}
		}
		else {
			configuration[key] = value;
		}
	},

	// integratePageSectionResponse : function(domain, response) {
	
	// 	var that = this;
		
	// 	// var memory = that.getMemory(domain);
	// 	// $.extend(true, memory.statelessdata, response.statelessdata);
	// 	// $.extend(true, memory.statefuldata, response.statefuldata);

	// 	// Stateful data is to be integrated under the corresponding URL
	// 	var storeData = that.getStoreData(domain);
	// 	var url = "temporary-hack";
	// 	$.extend(true, storeData.statelessdata, response.statelessdata || {});
	// 	$.extend(true, storeData.statefuldata[url], response.statefuldata || {});
	// 	$.extend(true, storeData.combinedstatedata[url], response.statelessdata || {});
	// 	$.extend(true, storeData.combinedstatedata[url], response.statefuldata || {});
	// 	// $.each(storeData.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT], function(pssId, rpsConfiguration) {

	// 	// 	$.extend(true, storeData.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = $.extend(true, storeData.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
	// 	// 	$.extend(true, storeData.statefuldata[url].settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = $.extend(true, storeData.statefuldata[url].settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};

	// 	// 	$.extend(true, storeData.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId], response.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {});
	// 	// 	$.extend(true, storeData.statefuldata[url].settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId], response.statefuldata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {});
	// 	// }

	// 	$.each(response.statelessdata.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT], function(pssId, rpsConfiguration) {

	// 		// // Initialize all the pageSection keys if needed 
	// 		// // (this is only needed since adding support for multicomponent, since their response may involve a pageSection which had never been initialized)
	// 		// memory.statefuldata.querystate.sharedbydomains[pssId] = memory.statefuldata.querystate.sharedbydomains[pssId] || {};
	// 		// memory.statefuldata.querystate.uniquetodomain[pssId] = memory.statefuldata.querystate.uniquetodomain[pssId] || {};
	// 		// memory.statefuldata.dbobjectids[pssId] = memory.statefuldata.dbobjectids[pssId] || {};
	// 		// memory.statefuldata.feedback.pagesection[pssId] = memory.statefuldata.feedback.pagesection[pssId] || {};
	// 		// memory.statefuldata.feedback.block[pssId] = memory.statefuldata.feedback.block[pssId] || {};
	// 		// memory.statefuldata.settings['js-settings'][pssId] = memory.statefuldata.settings['js-settings'][pssId] || {};
	// 		// memory.statelessdata.settings['js-settings'][pssId] = memory.statelessdata.settings['js-settings'][pssId] || {};
	// 		// memory.statelessdata.settings.jsmethods.pagesection[pssId] = memory.statelessdata.settings.jsmethods.pagesection[pssId] || {};
	// 		// memory.statelessdata.settings.jsmethods.block[pssId] = memory.statelessdata.settings.jsmethods.block[pssId] || {};
	// 		// memory.statelessdata.settings['modules-cbs'][pssId] = memory.statelessdata.settings['modules-cbs'][pssId] || {};
	// 		// memory.statelessdata.settings['modules-paths'][pssId] = memory.statelessdata.settings['modules-paths'][pssId] || {};
	// 		// memory.statelessdata.settings['db-keys'][pssId] = memory.statelessdata.settings['db-keys'][pssId] || {};
	// 		// memory.statelessdata.settings.configuration[pssId] = memory.statelessdata.settings.configuration[pssId] || {};
	// 		// memory.statefuldata.settings.configuration[pssId] = memory.statefuldata.settings.configuration[pssId] || {};

	// 		// $.extend(memory.statefuldata.querystate.sharedbydomains[pssId], response.statefuldata.querystate.sharedbydomains[pssId]);
	// 		// $.extend(memory.statefuldata.querystate.uniquetodomain[pssId], response.statefuldata.querystate.uniquetodomain[pssId]);
	// 		// $.extend(memory.statefuldata.dbobjectids[pssId], response.statefuldata.dbobjectids[pssId]);
	// 		// $.extend(memory.statefuldata.feedback.pagesection[pssId], response.statefuldata.feedback.pagesection[pssId]);
	// 		// $.extend(memory.statefuldata.feedback.block[pssId], response.statefuldata.feedback.block[pssId]);

	// 		// $.extend(memory.statefuldata.settings['js-settings'][pssId], response.statefuldata.settings['js-settings'][pssId]);
	// 		// $.extend(memory.statelessdata.settings['js-settings'][pssId], response.statelessdata.settings['js-settings'][pssId]);
	// 		// $.extend(memory.statelessdata.settings.jsmethods.pagesection[pssId], response.statelessdata.settings.jsmethods.pagesection[pssId]);
	// 		// $.extend(memory.statelessdata.settings.jsmethods.block[pssId], response.statelessdata.settings.jsmethods.block[pssId]);
	// 		// $.extend(memory.statelessdata.settings['modules-cbs'][pssId], response.statelessdata.settings['modules-cbs'][pssId]);
	// 		// $.extend(memory.statelessdata.settings['modules-paths'][pssId], response.statelessdata.settings['modules-paths'][pssId]);
	// 		// $.extend(memory.statelessdata.settings['db-keys'][pssId], response.statelessdata.settings['db-keys'][pssId]);

	// 		// // Configuration: first copy the modules, and then the 1st level configuration => pageSection configuration
	// 		// // This is a special case because the blocks are located under 'modules', so doing $.extend will override the existing modules in 'memory', however we want to keep them
	// 		// var psConfiguration = that.getStatelessData(domain).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId];
	// 		var psConfiguration = that.getCombinedStateData(domain).settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId];
	// 		// $.each(rpsConfiguration, function(key, value) {

	// 		// 	// If it is an array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
	// 		// 	// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
	// 		// 	that.copyToConfiguration(key, value, psConfiguration, false);
	// 		// });

	// 		var psId = rpsConfiguration[pop.c.JS_FRONTENDID];
	// 		var pageSection = $('#'+psId);
	// 		that.initPageSectionSettings(domain, pageSection, psConfiguration);
	// 	});
	// },

	getTopLevelSettings : function(domain) {
	
		var that = this;

		// Comment Leo 24/08/2017: no need for the pre-defined ID
		return {
			domain: domain,
			'domain-id': getDomainId(domain),
			feedback: that.getTopLevelFeedback(domain),
		};
	},

	getPageSectionSettings : function(domain, pageSection) {
	
		var that = this;

		var pssId = that.getSettingsId(pageSection);
		var psId = pageSection.attr('id');

		var pageSectionSettings = {
			feedback: that.getPageSectionFeedback(domain, pageSection),
			pssId: pssId,
			psId: psId
		};

		return $.extend(true, {}, pageSectionSettings);
		// return pageSectionSettings;
	},

	isMultiDomain : function(blockTLDomain, pssId, bsId) {
	
		var that = this;

		var url = that.getBlockTopLevelURL(blockTLDomain, bsId);
		var statefulData = that.getStatefulData(blockTLDomain, url);

		statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
		statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS] = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS] || {};
		statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS][bsId] = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS][bsId] || {};
		var runtimeConfiguration = statefulData.settings.configuration[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][pop.c.JS_SUBCOMPONENTS][bsId];

		// Comments Leo 27/07/2017: the query-multidomain-urls are stored under the domain from which the block was initially rendered,
		// and not that from where the data is being rendered
		// var multidomain_urls = that.getRuntimeSettings(blockTLDomain, pssId, bsId, 'query-multidomain-urls');
		// var runtimeConfiguration = that.getStatefulSettings(blockTLDomain, that.getBlockTopLevelURL(bsId), pssId, bsId, 'configuration');
		var multidomain_urls = runtimeConfiguration['query-multidomain-urls'];
		return (multidomain_urls && multidomain_urls.length >= 2);
	},

	getBlockSettings : function(domain, blockTLDomain, pssId, bsId, psId, bId) {
	
		var that = this;
		var blockSettings = {
			dbkeys: that.getDatabaseKeys(domain, pssId, bsId),
			dbobjectids: that.getDataset(domain, pssId, bsId),
			feedback: that.getBlockFeedback(domain, pssId, bsId),
			bsId: bsId,
			bId: bId,
			'toplevel-domain': blockTLDomain,
			'is-multidomain': that.isMultiDomain(blockTLDomain, pssId, bsId)
		};

		return $.extend(true, {}, blockSettings);
		// return blockSettings;
	},

	getDataset : function(domain, pageSection, block) {
	
		var that = this;
		var pssId = that.getSettingsId(pageSection);
		var bsId = that.getSettingsId(block);
		var url = that.getBlockTopLevelURL(domain, block);
		
		// return that.getMemory(domain).statefuldata.dbobjectids[pssId][bsId];
		that.getStatefulData(domain, url).dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = that.getStatefulData(domain, url).dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
		that.getStatefulData(domain, url).dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId] = that.getStatefulData(domain, url).dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId] || {};
		return that.getStatefulData(domain, url).dbobjectids[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];
	},

	getBlockFeedback : function(domain, pageSection, block) {
	
		var that = this;
		var pssId = that.getSettingsId(pageSection);
		var bsId = that.getSettingsId(block);
		var url = that.getBlockTopLevelURL(domain, block);
		
		// return that.getMemory(domain).statefuldata.feedback.block[pssId][bsId];
		that.getStatefulData(domain, url).feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = that.getStatefulData(domain, url).feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
		that.getStatefulData(domain, url).feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId] = that.getStatefulData(domain, url).feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId] || {};
		return that.getStatefulData(domain, url).feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];
	},

	getPageSectionFeedback : function(domain, pageSection) {
	
		var that = this;
		var pssId = that.getSettingsId(pageSection);
		var url = that.getPageSectionTopLevelURL(domain, pageSection);
		that.getStatefulData(domain, url).feedback.pagesection[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = that.getStatefulData(domain, url).feedback.pagesection[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
		return that.getStatefulData(domain, url).feedback.pagesection[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId];
	},

	// getTopLevelFeedback : function(domain) {
	
	// 	var that = this;
	// 	var url = window.location.href;
	// 	return that.getStatefulData(domain, url).feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT];
	// },

	getStatefulSettings : function(domain, url, pageSection, target, item) {
	
		var that = this;
		
		var pssId = that.getSettingsId(pageSection);
		var targetId = that.getSettingsId(target);
		var statefulData = that.getStatefulData(domain, url);

		statefulData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = statefulData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
		statefulData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId] = statefulData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId] || {};
		return statefulData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId];
	},
	getStatelessSettings : function(domain, pageSection, target, item) {
	
		var that = this;
		
		var pssId = that.getSettingsId(pageSection);
		var targetId = that.getSettingsId(target);
		var statelessData = that.getStatelessData(domain);

		statelessData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] = statelessData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
		statelessData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId] = statelessData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId] || {};
		return statelessData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId];
	},
	// getCombinedStateSettings : function(domain, url, pageSection, target, item) {
	
	// 	var that = this;
		
	// 	var pssId = that.getSettingsId(pageSection);
	// 	var targetId = that.getSettingsId(target);
	// 	var combinedStateData = that.getCombinedStateData(domain, url);

	// 	return combinedStateData.settings[item][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][targetId];
	// },
	// getRuntimeSettings : function(domain, pageSection, target, item) {
	
	// 	var that = this;
		
	// 	var pssId = that.getSettingsId(pageSection);
	// 	var targetId = that.getSettingsId(target);
	// 	var memory = that.getMemory(domain);
		
	// 	return memory.runtimesettings[item][pssId][targetId];
	// },

	getQueryUrl : function(pageSection, block) {
	
		var that = this;
		return that.getRuntimeMemoryPage(pageSection, block)['query-url'] || {};
	},

	getQueryMultiDomainUrls : function(pageSection, block) {
	
		var that = this;
		return that.getRuntimeMemoryPage(pageSection, block)['query-multidomain-urls'] || {};
	},

	// getRuntimeConfiguration : function(domain, pageSection, target, el) {

	// 	var that = this;

	// 	// When getting the block configuration, there's no need to pass el param
	// 	el = el || target;
		
	// 	var elsId = that.getModuleOrObjectSettingsId(el);
	// 	var configuration = that.getRuntimeSettings(domain, pageSection, target, 'configuration');

	// 	return configuration[elsId] || {};
	// },

	getDatabaseKeys : function(domain, pageSection, block) {
	
		var that = this;
		return that.getStatelessSettings(domain, pageSection, block, 'dbkeys');
	},

	getBlockFilteringUrl : function(domain, pageSection, block, use_pageurl) {
	
		var that = this;
		var url = that.getQueryUrl(pageSection, block);

		// If the block doesn't have a filtering url (eg: the Author Description, https://www.mesym.com/p/leo/?tab=description) then use the current browser url
		if (!url && use_pageurl) {;
			url = that.getTopLevelFeedback(domain)[pop.c.URLPARAM_URL];
		}

		// Add only the 'visible' params to the URL
		var post_data = that.getBlockPostData(domain, pageSection, block, {paramsGroup: 'visible'});
		if (post_data) {
			if (url.indexOf('?') > -1) {
				url += '&';
			}
			else {
				url += '?';
			}
			url += post_data;
		}
		return url;
	},

	click : function(url, target, container) {
	
		var that = this;
		target = target || '';
		container = container || $(document.body);
		
		// Create a new '<a' element with the url as href, and "click" it
		// We do this instead of pop.Manager.fetchMainPageSection(datum.url); so that it can be intercepted
		// So this is needed for the Top Quicklinks/Search
		var linkHtml = '<a href="'+url+'" target="'+target+'" class="hidden"></a>';
		var link = $(linkHtml).appendTo(container);
		link.trigger('click');
	},

	getAPIUrl : function(url) {
	
		var that = this;

		// Add the corresponding parameters, like this:
		// $url?output=json&scheme=data&mangled=false
		// Add mangled=false so that the developers get a consistent name, which will not change with software updates,
		// and also so that they can understand what data it is
		$.each(pop.c.API_URLPARAMS, function(param, value) {
			url = add_query_arg(param, value, url);
		});

		return url;
	},

	getDestroyUrl : function(url) {
	
		var that = this;

		// Comment Leo 10/06/2016: The URL can start with other domains, for the Platform of Platforms
		var domain = getDomain(url);
		return domain+'/destroy'+url.substr(domain.length);
	},
	
	getModuleOrObjectSettingsId : function(el) {

		var that = this;
		
		// If it's an object, return an attribute	
		if ($.type(el) == 'object') {

			return el.data('modulename');
		}

		// String was passed, return it
		return el;
	},

	getPageSectionJsSettings : function(domain, pageSection) {
	
		// This is a special case
		var that = this;
		var pssId = that.getSettingsId(pageSection);
		
		return that.getStatelessSettings(domain, pageSection, pageSection, 'js-settings') || {};
	},
	getJsSettings : function(domain, pageSection, block, el) {

		var that = this;

		// When getting the block settings, there's no need to pass el param
		el = el || block;
		
		var pssId = that.getSettingsId(pageSection);
		var bsId = that.getSettingsId(block);
		var jsSettingsId = that.getModuleOrObjectSettingsId(el);

		// Combine the JS settings and the runtime JS settings together
		// var domain = that.getBlockTopLevelDomain(block);
		var settings = that.getStatelessSettings(domain, pageSection, block, 'js-settings');
		// var runtimeSettings = that.getRuntimeSettings(domain, pageSection, block, 'js-settings');

		var jsSettings = {};
		if (settings[jsSettingsId]) {
			$.extend(jsSettings, settings[jsSettingsId]);
		}
		// if (runtimeSettings[jsSettingsId]) {
		// 	// Make it deep, because in the typeahead, the thumbprint info is saved under ['dataset']['thumbprint'], so key 'dataset' must not be overriden
		// 	$.extend(true, jsSettings, runtimeSettings[jsSettingsId]);
		// }
		return jsSettings;
	},
	getPageSectionJsMethods : function(domain, pageSection) {
	
		var that = this;

		var pssId = that.getSettingsId(pageSection);
		// var memory = that.getMemory(domain);
		return that.getStatelessData(domain).settings['jsmethods']['pagesection'][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId] || {};
	},
	getBlockJsMethods : function(domain, pageSection, block) {
	
		var that = this;

		var pssId = that.getSettingsId(pageSection);
		var bsId = that.getSettingsId(block);
		// var memory = that.getMemory(domain);

		return that.getStatelessData(domain).settings['jsmethods']['block'][pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId] || {};
	},
	// restoreInitialBlockMemory : function(pageSection, block, options) {

	// 	var that = this;
	// 	// var pssId = that.getSettingsId(pageSection);
	// 	// var bsId = that.getSettingsId(block);
	// 	// var url = that.getTargetParamsScopeURL(block);
	// 	// var initialMemory = that.getInitialBlockMemory(url);

	// 	// var queryState = that.getRuntimeMemoryPage(pageSection, block, options)['querystate'];
	// 	// querystate.sharedbydomains = $.extend(that.getBlockDefaultParams(), initialMemory.querystate.sharedbydomains[pssId][bsId]);

	// 	// var dbobjectids = initialMemory.dbobjectids[pssId][bsId];
	// 	// var query_urls = that.getQueryMultiDomainUrls(pageSection, block);
	// 	// $.each(query_urls, function(domain, query_url) {
			
	// 	// 	querystate.uniquetodomain[domain] = $.extend(that.getBlockDefaultMultiDomainParams(), initialMemory.querystate.uniquetodomain[pssId][bsId]);
			
	// 	// 	var memory = that.getMemory(domain);
	// 	// 	memory.statefuldata.feedback.block[pssId][bsId] = $.extend({}, initialMemory.feedback.block[pssId][bsId]);

	// 	// 	// If the initialMemory dbobjectids is empty and the memory one is not, then the extend fails to override
	// 	// 	// So ask for that case explicitly
	// 	// 	if (dbobjectids.length) {
	// 	// 		$.extend(memory.statefuldata.dbobjectids[pssId][bsId], dbobjectids);
	// 	// 	}
	// 	// 	else {
	// 	// 		memory.statefuldata.dbobjectids[pssId][bsId] = [];
	// 	// 	}
	// 	// });
	// },
	
	getBlockQueryState : function(pageSection, block, options) {
	
		var that = this;

		return that.getRuntimeMemoryPage(pageSection, block, options)['querystate'].sharedbydomains;
	},
	getBlockMultiDomainQueryState : function(pageSection, block, options) {
	
		var that = this;

		return that.getRuntimeMemoryPage(pageSection, block, options)['querystate'].uniquetodomain;
	},
	getBlockDomainQueryState : function(domain, pageSection, block, options) {
	
		var that = this;

		return that.getBlockMultiDomainQueryState(pageSection, block, options)[domain] || {};
	},
	getPageSectionParams : function(pageSection, options) {
	
		var that = this;

		return that.getRuntimeMemoryPage(pageSection, pageSection, options)['querystate'];
	},
	
	getTarget : function(pageSection) {
	
		var that = this;
		var id = pageSection.attr('id');

		// Default case: if the target doesn't exist, use the main target
		var ret = pop.c.URLPARAM_TARGET_MAIN;
		$.each(pop.c.FETCHTARGET_SETTINGS, function(target, psId) {

			if (psId == id) {
				ret = target;
				return -1;
			}
		});

		return ret;
	},
	targetExists : function(target) {

		var that = this;
		return pop.c.FETCHTARGET_SETTINGS[target];
	},
	getFetchTargetPageSection : function(target) {
	
		var that = this;

		if (!target || target == '_self' || !pop.c.FETCHTARGET_SETTINGS[target]) {
			target = pop.c.URLPARAM_TARGET_MAIN;
		}

		return $('#'+pop.c.FETCHTARGET_SETTINGS[target]);
	},
	getFetchPageSectionSettings : function(pageSection) {
	
		var that = this;
		var psId = pageSection.attr('id');
		return pop.c.FETCHPAGESECTION_SETTINGS[psId] || {};
	},
	
	getModulesCbs : function(domain, pageSection, target, action) {
	
		var that = this;

		action = action || 'main';

		var modulesCbs = that.getStatelessSettings(domain, pageSection, target, 'modules-cbs');
		var cbs = modulesCbs.cbs;
		var actions = modulesCbs.actions;

		// If it's an empty array, return already (ask if it's array, because only when empty is array, when full it's object)
		if ($.isArray(actions) && !actions.length) {

			return cbs;
		}
		
		// Iterate all the callbacks, check if they match the passed action
		var allowed = [];
		$.each(cbs, function(index, cb) {

			// Exclude callback if it has actions, but not this one action
			if (!actions[cb] || actions[cb].indexOf(action) > -1) {

				// The action doesn't belong, kick the callback module out
				allowed.push(cb);
			}
		});

		return allowed;
	},

	getModulePath : function(domain, pageSection, target, moduleName) {
	
		var that = this;
		
		var componentPaths = that.getStatelessSettings(domain, pageSection, target, 'modules-paths');
		return componentPaths[moduleName];
	},
	
	getExecutableTemplate : function(domain, moduleOrTemplateName) {

		var that = this;
		var template = that.getTemplate(domain, moduleOrTemplateName);
		return Handlebars.templates[template];
	},

	getPageSectionGroup : function(elem) {
	
		var that = this;
		return elem.closest('.pop-pagesection-group').addBack('.pop-pagesection-group');
	},
	getPageSection : function(elem) {
	
		var that = this;
		return elem.closest('.pop-pagesection').addBack('.pop-pagesection');
	},
	getPageSectionPage : function(elem) {
	
		var that = this;		
		var page = elem.closest('.pop-pagesection-page').addBack('.pop-pagesection-page');
		if (page.length) {
			return page;
		}
		return that.getPageSection(elem);
	},
	
	getBlockId : function(pageSection, block, options) {
	
		var that = this;		

		return that.getRuntimeMemoryPage(pageSection, block, options).id;
	},
	
	getHtml : function(domain, moduleOrTemplateName, context) {

		var that = this;	
		var executableTemplate = that.getExecutableTemplate(domain, moduleOrTemplateName);

		// Comment Leo 29/11/2014: some browser plug-ins will not allow the template to be created
		// Eg: AdBlock Plus. So when that happens (eg: when requesting template "socialmedia-source") template is undefined
		// So if this happens, then just return nothing
		var error = null;
		if (typeof executableTemplate == 'undefined') {

			error = new Error('No template for ' + moduleOrTemplateName);
		}
		else {

			try {
				return executableTemplate(context);
			}
			catch (err) {
				
				error = err;
			}
		}

		// If it reached here, it's because there is some error
		if (pop.c.THROW_EXCEPTION_ON_TEMPLATE_ERROR) {

			throw error;
		}

		console.error(error);
		return '';
	},

	getDBObject : function(domain, dbKey, objectID) {

		var that = this;
		var userItem = {}, item = {};
		var userdatabase = that.getUserDatabase(domain);
		var database = that.getDatabase(domain);
		if (userdatabase[dbKey] && userdatabase[dbKey][objectID]) {
			userItem = userdatabase[dbKey][objectID];
		}
		if (database[dbKey] && database[dbKey][objectID]) {
			item = database[dbKey][objectID];
		}
		return $.extend({}, userItem, item);
	},

	getFrameTarget : function(pageSection) {

		var that = this;
		return pageSection.data('frametarget') || pop.c.URLPARAM_TARGET_MAIN;
	},

	getClickFrameTarget : function(pageSection) {

		var that = this;
		return pageSection.data('clickframetarget') || that.getFrameTarget(pageSection);
	},

	getOriginalLink : function(link) {

		var that = this;

		// If the link is an interceptor, then return the data from the original intercepted element
		// Otherwise, it is the link itself, retrieve from there
		var intercepted = link.data('interceptedTarget');
		if (intercepted) {
			return that.getOriginalLink(intercepted);
		}

		return link;
	},
};
})(jQuery);