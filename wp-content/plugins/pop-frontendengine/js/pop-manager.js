(function($){
popManager = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	
	// Comment Leo 10/08/2017: actually, they can't be independent, since they will still share the same context, because the configuration is copied by reference, not by copy...
	// So then must stack all domains under the same promise for the html rendering...
	// Comment Leo 21/07/2017: since adding multicomponents for different domains, we use a different mergingTemplatePromise for each domain
	// mergingTemplatePromise : {},//false,
	mergingTemplatePromise : false,
	// Comment Leo 21/07/2017: since adding multicomponents for different domains, we group memory, database and userDatabase under property `state`, under which we specify the domain
	state : {},
	sitemapping : {},
	// memory : {
	// 	settings: {},
	// 	runtimesettings: {},
	// 	dataset: {},
	// 	feedback: {
	// 		block: {},
	// 		pagesection: {},
	// 		toplevel: {}
	// 	},
		// 'query-state': {
		// 	general: {},
		// 	domain: {},
		// }
	// },
	// database : {},
	// userdatabase : {},
	runtimeMemory : {
		general: {},
		url: {}
	},
	// Used to override the dataset/feedback/params when resetting the block
	initialBlockMemory : {},
	// Used to override the values before replicating
	urlPointers : {},
	replicableMemory : {},
	firstLoad : {},
	documentTitle : null, // We keep a copy of the document title, so we can add the notification number in it
	domains : {}, // Keep a list of all domains, so we know when to initialize them

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	getMemory : function(domain) {

		var t = this;
		// domain = domain || M.HOME_DOMAIN;
		return t.state[domain].memory;
	},
	getDatabase : function(domain) {

		var t = this;
		// domain = domain || M.HOME_DOMAIN;
		return t.state[domain].database;
	},
	getUserDatabase : function(domain) {

		var t = this;
		// domain = domain || M.HOME_DOMAIN;
		return t.state[domain].userdatabase;
	},
	getInitialBlockMemory : function(url) {

		var t = this;

		// If the url is the first one loaded, then the initial memory is stored there.
		// Otherwise, there is a bug when loading https://www.mesym.com/en/log-in/:
		// The memory will be loaded under this url, but immediately it will fetch /loaders/initial-frames?target=main,
		// and it will fetch /log-in again. However, the Notifications will not be there, since it's loaded only on loadingframe(),
		// and it will produce a JS error when integrating the initialMemory into the memory (restoreinitial)
		if (url == M.INITIAL_URL) {
			return t.initialBlockMemory[url];
		}

		// The url is either stored as the initial block memory, or it is an intercepted url, so the configuration
		// is stored under another url
		var storedUnder = t.urlPointers[url];
		if (storedUnder) {
			return t.initialBlockMemory[storedUnder.url];	
		}
		return t.initialBlockMemory[url] || {};
	},
	addInitialBlockMemory : function(response) {

		var t = this;
		var url = response.feedback.toplevel[M.URLPARAM_URL];
		if (!t.initialBlockMemory[url]) {
			t.initialBlockMemory[url] = {
				dataset: {},
				feedback: {
					block: {},
				},
				'query-state': {
					general: {},
					domain: {},
				},
				runtimesettings: {
					configuration: {}
				}
			};
		}
		var initialMemory = t.initialBlockMemory[url];
		$.each(response.settings.configuration, function(pssId, rpsConfiguration) {

			if (!initialMemory['query-state'].general[pssId]) {
				initialMemory['query-state'].general[pssId] = {};
				initialMemory['query-state'].domain[pssId] = {};
				initialMemory.runtimesettings.configuration[pssId] = {};
				initialMemory.dataset[pssId] = {};
				initialMemory.feedback.block[pssId] = {};
			}
			$.extend(initialMemory['query-state'].general[pssId], response['query-state'].general[pssId]);
			$.extend(initialMemory['query-state'].domain[pssId], response['query-state'].domain[pssId]);
			$.extend(initialMemory.runtimesettings.configuration[pssId], response.runtimesettings.configuration[pssId]);
			$.extend(initialMemory.dataset[pssId], response.dataset[pssId]);
			$.extend(initialMemory.feedback.block[pssId], response.feedback.block[pssId]);
		});
	},
	getReplicableMemory : function(url, target) {

		var t = this;
		target = target || M.URLPARAM_TARGET_MAIN;

		var storedUnder = t.urlPointers[url];
		return t.replicableMemory[storedUnder.url][storedUnder.target];
	},
	saveUrlPointers : function(response, target) {

		var t = this;
		target = target || M.URLPARAM_TARGET_MAIN;
		var url = response.feedback.toplevel[M.URLPARAM_URL];

		// For each URL to be intercepted, save under which page URL and target its memory has been stored
		$.each(response.feedback.pagesection, function(pssId, psFeedback) {
			if (psFeedback['intercept-urls']) {
				$.each(psFeedback['intercept-urls'], function(ipssId, iObject) {
					$.each(iObject, function(ielemsId, iUrl) {
						t.urlPointers[iUrl] = {
							url: url,
							target: target
						};
					});
				});
			}
		});
		// Also needed for the initialBlockMemory
		$.each(response.feedback.block, function(pssId, psFeedback) {
			$.each(psFeedback, function(bsId, bFeedback) {
				if (bFeedback['intercept-urls']) {
					$.each(bFeedback['intercept-urls'], function(ibsId, iObject) {
						$.each(iObject, function(ielemsId, iUrl) {
							t.urlPointers[iUrl] = {
								url: url,
								target: target
							};
						});
					});
				}
			});
		});
	},
	addReplicableMemory : function(response, target) {

		var t = this;
		target = target || M.URLPARAM_TARGET_MAIN;

		// Store the memory only if the response involves pageSections with replicable elements
		// To find out, check all the configurations, that any of them has `blockunits-replicable`
		var hasreplicable = false;
		$.each(response.settings.configuration, function(pssId, psConfiguration) {
			if (psConfiguration[M.JS_BLOCKSETTINGSIDS][M.JS_BLOCKUNITSREPLICABLE].length) {
				hasreplicable = true;
				return -1;
			}
		});

		if (hasreplicable) {

			// Keep a copy of the memory, to be restored when a replicable elements is intercepted
			var url = response.feedback.toplevel[M.URLPARAM_URL];
			if (!t.replicableMemory[url]) {
				t.replicableMemory[url] = {};
			}
			t.replicableMemory[url][target] = {
				dataset: $.extend(true, {}, response.dataset),
				feedback: $.extend(true, {}, response.feedback),
				'query-state': $.extend(true, {}, response['query-state'])
			}
		}
	},
	getRuntimeMemory : function(domain, pageSection, target, options) {

		var t = this;

		options = options || {};

		// To tell if it's general or url, check for data-paramscope in the pageSection
		var pageSectionPage = t.getPageSectionPage(target);
		var scope = pageSectionPage.data('paramsscope');
		if (scope == M.SETTINGS_PARAMSSCOPE_URL) {
			
			var url = options.url || t.getTopLevelFeedback(domain)[M.URLPARAM_URL];//''+window.location.href;
			if (!t.runtimeMemory.url[url]) {
			
				// Create a new instance for this URL
				t.runtimeMemory.url[url] = {};
			}

			// Save which url the params for this target is under
			target.data(M.PARAMS_PARAMSSCOPE_URL, url);

			return t.runtimeMemory.url[url];
		}

		// The entry can be created either under 'general' for all pageSections who are static, ie: they don't bring any new content with the url (eg: top-frame)
		// or under 'url' for the ones who depend on a given url, eg: main
		return t.runtimeMemory.general;
	},
	newRuntimeMemoryPage : function(domain, pageSection, target, options) {

		var t = this;
		
		// Take the URL from the topLevelFeedback and not from window.location.href when creating a new one.
		// this is so that we don't need to update the browser url, which sometimes we don't want, eg: when replicating Add Comment in the addon pageSection
		options = options || {};
		if (!options.url) {
			var tlFeedback = t.getTopLevelFeedback(domain);
			options.url = tlFeedback[M.URLPARAM_URL];
		}
		var mempage = t.getRuntimeMemory(domain, pageSection, target, options);

		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(target);

		if (!mempage[pssId]) {
			mempage[pssId] = {};
		}
		mempage[pssId][targetId] = {
			'query-state': {
				general: {},
				domain: {},
			},
			id: null
		};

		return mempage[pssId][targetId];
	},
	deleteRuntimeMemoryPage : function(domain, pageSection, target, options) {

		var t = this;
		var mempage = t.getRuntimeMemory(domain, pageSection, target, options);

		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(target);

		if (mempage[pssId]) {
			delete mempage[pssId][targetId];

			if ($.isEmptyObject(mempage[pssId])) {
				delete mempage[pssId];
			}
		}
	},
	getRuntimeMemoryPage : function(pageSection, targetOrId, options) {

		var t = this;

		// In function executeFetchBlock will get a response with the settingsId of the block, not the block. 
		// In that case, we can't do block.data('paramsscope-url'), so instead we pass the url in the options
		options = options || {};

		// if the target has paramsscope-url set then look for its params under that url key
		var mempage, url;
		if (options.url) {
			url = options.url;
		}
		else if ($.type(targetOrId) == 'object') {
			var target = targetOrId;
			url = t.getTargetParamsScopeURL(target);//target.data(M.PARAMS_PARAMSSCOPE_URL);
		}
		if (url) {
			
			mempage = t.runtimeMemory.url[url];
		}
		else {

			// Otherwise, it's general
			mempage = t.runtimeMemory.general;
		}

		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(targetOrId);

		// If this doesn't exist, it's because the tab was closed and we are still retrieving the mempage later on (eg: a fetch-more had been invoked and the response came after tab was closed)
		// Since this behavior is not right, then just thrown an exception
		if (!mempage[pssId] || !mempage[pssId][targetId]) {

			var error = "Mempage not available";
			if (url) {
				error += " for url " + url;
			}
			throw new Error(error);
		}

		return mempage[pssId][targetId];
	},

	isFirstLoad : function(pageSection) {
		
		var t = this;
		
		return t.firstLoad[popManager.getSettingsId(pageSection)];
	},

	initDomainVars : function(domain) {
	
		var t = this;

		t.state[domain] = {
			memory : {
				settings: {
					'js-settings': {},
					jsmethods: {
						pagesection: {},
						block: {},
					},
					'templates-cbs': {},
					'templates-paths': {},
					'db-keys': {},
					configuration: {},
					// 'template-sources': {},
				},
				runtimesettings: {
					'query-url': {},
					'query-multidomain-urls': {},
					configuration: {},
					'js-settings': {},
				},
				dataset: {},
				feedback: {
					block: {},
					pagesection: {},
					toplevel: {}
				},
				'query-state': {
					general: {},
					domain: {},
				}
			},
			database : {},
			userdatabase : {},
		};
	},

	init : function() {
	
		var t = this;
		
		// Comment Leo 22/08/2016: when is_search_engine(), there is no #toplevel, so do nothing
		if ($('#'+popPageSectionManager.getTopLevelSettingsId()).length) {

			var domain = M.HOME_DOMAIN;

			t.initDocument(domain);

			// Initialize the domain
			t.initDomain(domain, false);

			// Initialize Settings, Feedback and Data
			t.initTopLevelJson(domain);

			// Make sure the localStorage has no stale entries
			t.initLocalStorage();

			// Log in the user immediately, before rendering the HTML. This way, conditional wrappers can access the state of the user
			// being logged in, such as for "isUserIdSameAsLoggedInUser"
			popUserAccount.initialLogin(domain);

			// Obtain what pageSections to merge from the configuration
			var memory = t.getMemory(domain);

			// Keep the pageSection DOM elems
			var pageSections = [];

			// Comment Leo 01/12/2016: Split the logic below into 2: first render all the pageSections (t.renderPageSection(pageSection)),
			// and then execute all JS on them (t.pageSectionInitialized(pageSection)), and in between remove the "loading" screen
			// this way, the first paint is much faster, for a better user experience
			// Step 0: initialize the pageSection
			$.each(memory.settings.configuration, function(pssId, psConfiguration) {

				var psId = psConfiguration[M.JS_FRONTENDID];
				var pageSection = $('#'+psId);

				// Before anything: add the settings-id to the div (so we can access below getPageSectionId)
				pageSection.attr('data-settings-id', pssId);
				pageSection.addClass('pop-pagesection');

				t.initPageSectionSettings(domain, pageSection, psConfiguration);

				// Insert into the Runtime to generate the ID
				t.addPageSectionIds(domain, pageSection, psConfiguration[M.JS_TEMPLATE]);

				// Allow plugins to catch events for the rendering
				t.initPageSection(pageSection);

				pageSections.push(pageSection);
			});


			// Step 1: render the pageSection
			var options = {
				'serverside-rendering': M.USESERVERSIDERENDERING
			}
			$.each(pageSections, function(index, pageSection) {

				// // Re-calculate the progress
				// t.progress = parseInt(delta*(index+1));

				t.renderPageSection(domain, pageSection, options);
			});

			// Step 2: remove the "loading" screen
			$(document.body).removeClass('pop-loadingframe');

			// Step 3: execute JS
			$.each(pageSections, function(index, pageSection) {

				t.pageSectionInitialized(domain, pageSection);
			});

			// Step 4: remove the "loading" screen
			$(document.body).removeClass('pop-loadingjs');

			var topLevelFeedback = t.getTopLevelFeedback(domain);
			var url = topLevelFeedback[M.URLPARAM_URL];
			if (!topLevelFeedback[M.URLPARAM_SILENTDOCUMENT]) {
				
				// Update URL: it will remove the unwanted items, eg: mode=embed (so that if the user clicks on the newWindow btn, it opens properly)
				popBrowserHistory.replaceState(url);
				t.updateDocument(domain);
			}

			t.documentInitialized(domain);

			// If the server requested to extra load more URLs
			t.backgroundLoad(M.BACKGROUND_LOAD); // Initialization of modules (eg: Modals, Addons)
			t.backgroundLoad(topLevelFeedback[M.URLPARAM_BACKGROUNDLOADURLS]); // Data to be loaded from server (eg: forceserverload_fields)
		}
	},

	expandJSKeys : function(context) {
	
		var t = this;

		// In order to save file size, context keys can be compressed, eg: 'modules' => 'm', 'template' => 't'. However they might be referenced with their full name
		// in .tmpl files, so reconstruct the full name in the context duplicating these entries
		if (context && M.COMPACT_JS_KEYS) {

			// Hardcoding always 'modules' allows us to reference this key, with certainty of its name, in the .tmpl files
			if (context[M.JS_MODULES]) {
				context.modules = context[M.JS_MODULES];
			}
			if (context['bs'] && context['bs']['db-keys'] && context['bs']['db-keys'][M.JS_SUBCOMPONENTS]) {
				context['bs']['db-keys'].subcomponents = context['bs']['db-keys'][M.JS_SUBCOMPONENTS];
			}
			if (context[M.JS_TEMPLATE]) {
				context.template = context[M.JS_TEMPLATE];
			}
			if (context[M.JS_TEMPLATEIDS]) {
				context['template-ids'] = context[M.JS_TEMPLATEIDS];
			}
			if (context[M.JS_SETTINGSID]) {
				context['settings-id'] = context[M.JS_SETTINGSID];
			}
			if (context[M.JS_SETTINGSIDS]) {
				context['settings-ids'] = context[M.JS_SETTINGSIDS];

				if (context[M.JS_SETTINGSIDS][M.JS_BLOCKUNITS]) {
					context['settings-ids']['blockunits'] = context[M.JS_SETTINGSIDS][M.JS_BLOCKUNITS];
				}
			}
			if (context[M.JS_INTERCEPT]) {
				context.intercept = context[M.JS_INTERCEPT];
			}
			if (context[M.JS_BLOCKSETTINGSIDS]) {
				context['block-settings-ids'] = context[M.JS_BLOCKSETTINGSIDS];

				if (context[M.JS_BLOCKSETTINGSIDS][M.JS_BLOCKUNITS]) {
					context['block-settings-ids']['blockunits'] = context[M.JS_BLOCKSETTINGSIDS][M.JS_BLOCKUNITS];
				}
				if (context[M.JS_BLOCKSETTINGSIDS][M.JS_BLOCKUNITSREPLICABLE]) {
					context['block-settings-ids']['blockunits-replicable'] = context[M.JS_BLOCKSETTINGSIDS][M.JS_BLOCKUNITSREPLICABLE];
				}
				if (context[M.JS_BLOCKSETTINGSIDS][M.JS_BLOCKUNITSFRAME]) {
					context['block-settings-ids']['blockunits-frame'] = context[M.JS_BLOCKSETTINGSIDS][M.JS_BLOCKUNITSFRAME];
				}
			}

			// Replicate
			if (context[M.JS_REPLICATEBLOCKSETTINGSIDS]) {
				context['replicate-blocksettingsids'] = context[M.JS_REPLICATEBLOCKSETTINGSIDS];

				if (context[M.JS_INTERCEPTSKIPSTATEUPDATE]) {
					context['intercept-skipstateupdate'] = context[M.JS_INTERCEPTSKIPSTATEUPDATE];
				}
				if (context[M.JS_UNIQUEURLS]) {
					context['unique-urls'] = context[M.JS_UNIQUEURLS];
				}
				if (context[M.JS_REPLICATETYPES]) {
					context['replicate-types'] = context[M.JS_REPLICATETYPES];
				}
			}

			// Params
			if (context[M.JS_PARAMS]) {
				context.params = context[M.JS_PARAMS];
			}
			if (context[M.JS_ITEMOBJECTPARAMS]) {
				context['itemobject-params'] = context[M.JS_ITEMOBJECTPARAMS];
			}
			if (context[M.JS_PREVIOUSTEMPLATESIDS]) {
				context['previoustemplates-ids'] = context[M.JS_PREVIOUSTEMPLATESIDS];
			}
			if (context[M.JS_BLOCKFEEDBACKPARAMS]) {
				context['blockfeedback-params'] = context[M.JS_BLOCKFEEDBACKPARAMS];
			}

			// Appendable
			if (context[M.JS_APPENDABLE]) {
				context.appendable = context[M.JS_APPENDABLE];
			}

			// Frequently used keys in many different templates
			if (context[M.JS_CLASS]) {
				context.class = context[M.JS_CLASS];
			}
			if (context[M.JS_CLASSES]) {
				context.classes = context[M.JS_CLASSES];

				if (context[M.JS_CLASSES][M.JS_APPENDABLE]) {
					context.classes.appendable = context[M.JS_CLASSES][M.JS_APPENDABLE];
				}
			}
			if (context[M.JS_STYLE]) {
				context.style = context[M.JS_STYLE];
			}
			if (context[M.JS_STYLES]) {
				context.styles = context[M.JS_STYLES];
			}
			if (context[M.JS_TITLES]) {
				context.titles = context[M.JS_TITLES];
			}

			// Allow Custom .js to add their own JS Keys (eg: Fontawesome)
			popJSLibraryManager.execute('expandJSKeys', {context: context});
		}
	},

	addPageSectionIds : function(domain, pageSection, template) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var psId = pageSection.attr('id');

		// Insert into the Runtime to generate the ID
		popJSRuntimeManager.addPageSectionId(domain, pssId, template, psId);

		var args = {
			domain: domain,
			pageSection: pageSection,
			template: template
		}

		popJSLibraryManager.execute('addPageSectionIds', args);
	},

	initDocument : function(domain) {
	
		var t = this;

		// $(document).on('user:loggedinout', function(e, source) {
		$(document).on('user:loggedout', function(e, source, domain) {

			// Clear the user database when the user logs out
			// Comment Leo 07/03/2016: this was initially loggedinout, however it deletes the userdatabase immediately, when the user is logged in and accessing a stateful page
			// var domain = M.HOME_DOMAIN;
			// Use the domain from the event
			t.clearUserDatabase(domain);
		});

		var args = {
			domain: domain,
		}

		popJSLibraryManager.execute('initDocument', args);
		$(document).triggerHandler('initialize.pop.document', [domain]);
	},

	initDomain : function(domain, fetchData) {
	
		var t = this;

		// Mark the domain as initialized
		t.domains[domain] = {
			initialized: true
		};

		// Initialize the variables holding the memory and databases for that domain;
		t.initDomainVars(domain);

		if (fetchData) {

			// Fetch the corresponding data from the server to initialize the domain
			var url = M.PLACEHOLDER_DOMAINURL.format(encodeURIComponent(domain));
			var entries = {};
			entries[url] = [M.URLPARAM_TARGET_MAIN];
			t.backgroundLoad(entries);
		}

		var args = {
			domain: domain,
		}

		popJSLibraryManager.execute('initDomain', args);
		$(document).triggerHandler('initialize.pop.domain', [domain]);
	},

	maybeInitializeDomain : function(domain) {
	
		var t = this;

		if (!t.domains[domain] || !t.domains[domain].initialized) {

			// Initialize the domain
			t.initDomain(domain, true);
		}
	},

	documentInitialized : function(domain) {
	
		var t = this;

		var args = {
			domain: domain,
		};

		popJSLibraryManager.execute('documentInitialized', args);
		$(document).triggerHandler('initialized.pop.document');
	},

	pageSectionFetchSuccess : function(pageSection, response) {
	
		var t = this;

		popJSLibraryManager.execute('pageSectionFetchSuccess', {pageSection: pageSection, response: response});
		pageSection.triggerHandler('fetched.pop.pageSection');
	},

	blockFetchSuccess : function(pageSection, block, response) {
	
		var t = this;

		popJSLibraryManager.execute('blockFetchSuccess', {pageSection: pageSection, block: block, response: response});
		block.triggerHandler('fetched.pop.block');
	},

	backgroundLoad : function(urls) {
	
		var t = this;

		// Trigger loading the frames and other background actions
		var options = {
			'loadingmsg-target': null,
			silentDocument: true,
		};
		$.each(urls, function(url, targets) {

			$.each(targets, function(index, target) {

				t.fetch(url, $.extend({target: target}, options));
			});
		});
	},

	initPageSection : function(pageSection) {
	
		var t = this;

		t.firstLoad[t.getSettingsId(pageSection)] = true;

		popJSLibraryManager.execute('initPageSection', {pageSection: pageSection});
		pageSection.triggerHandler('initialize');
		$(document).triggerHandler('initialize.pop.pagesection', [pageSection]);
	},

	pageSectionInitialized : function(domain, pageSection) {
	
		var t = this;

		// Initialize the params for this branch
		t.initPageSectionRuntimeMemory(domain, pageSection);

		popJSLibraryManager.execute('pageSectionInitialized', {domain: domain, pageSection: pageSection});
		pageSection.triggerHandler('initialized');
		pageSection.triggerHandler('completed');


		// Once the template has been initialized, then that's it, no more JS running, set firstLoad in false
		t.firstLoad[popManager.getSettingsId(pageSection)] = false;
	},

	pageSectionNewDOMsInitialized : function(domain, pageSection, newDOMs, options) {
	
		var t = this;

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
			var openmode = popPageSectionManager.getOpenMode(pageSection);
			var firstLoad = t.isFirstLoad(pageSection);
			if (openmode == 'automatic' || (firstLoad && openmode == 'initial')) {
				popPageSectionManager.open(pageSection);
			}
		}

		var args = {
			domain: domain,
			pageSection: pageSection,
			newDOMs: newDOMs
		};
		t.extendArgs(args, options);
		
		// Execute this first, so we can switch the tabPane to the newly added one before executing the JS
		popJSLibraryManager.execute('pageSectionNewDOMsBeforeInitialize', args);

		t.initPageSectionBranches(domain, pageSection, newDOMs, options);

		popJSLibraryManager.execute('pageSectionNewDOMsInitialized', args);
	},

	initPageSectionBranches : function(domain, pageSection, newDOMs, options) {
	
		var t = this;

		// First initialize the JS for the pageSection
		t.runPageSectionJSMethods(domain, pageSection, options);

		// Then, Initialize all inner scripts for the blocks
		// It is fine that it uses pageSection in the 2nd params, since it's there that it stores the branches information, already selecting the proper element nodes
		var jsSettings = popManager.getPageSectionJsSettings(domain, pageSection);
		var blockBranches = jsSettings['initjs-blockbranches'];
		if (blockBranches) {

			var branches = $(blockBranches.join(',')).not('.'+M.JS_INITIALIZED);
			t.initBlockBranches(domain, pageSection, branches, options);
		}

		// Delete the session ids at the end of the rendering
		popJSRuntimeManager.deletePageSectionLastSessionIds(domain, pageSection);
	},

	triggerDestroyTarget : function(url, target) {

		var t = this;
		target = target || M.URLPARAM_TARGET_MAIN;

		// Remove the tab from the open sessions
		t.removeOpenTab(url, target);

		// Intercept url+'!destroy' and this should call the corresponding destroy for the page
		// Call the interceptor to 
		popURLInterceptors.intercept(t.getDestroyUrl(url), {target: target});
	},

	destroyTarget : function(domain, pageSection, target) {

		var t = this;

		// Call 'destroy' from all libraries in popJSLibraryManager
		var args = {
			domain: domain,
			pageSection: pageSection,
			destroyTarget: target
		}
		popJSLibraryManager.execute('destroyTarget', args);
		target.triggerHandler('destroy');

		// Eliminate the params for each destroyed block
		var blocks = target.find('.pop-block.'+M.JS_INITIALIZED).addBack('.pop-block.'+M.JS_INITIALIZED);
		blocks.each(function() {
			
			var block = $(this);
			t.deleteRuntimeMemoryPage(domain/*popManager.getBlockTopLevelDomain(block)*/, pageSection, block, {url: t.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/});
		})

		// Remove from the DOM
		target.remove();
	},

	destroyPageSectionPage : function(domain, pageSection, pageSectionPage) {

		var t = this;

		var target = pageSectionPage || pageSection;

		// When it's closed, if there's no other pageSectionPage around, then close the whole pageSection
		if (!pageSectionPage.siblings('.pop-pagesection-page').length) {
			popPageSectionManager.close(pageSection);
			popPageSectionManager.close(pageSection, 'xs');
		}

		t.destroyTarget(domain, pageSection, target);
	},

	pageSectionRendered : function(domain, pageSection, newDOMs, options) {

		var t = this;		

		t.pageSectionNewDOMsInitialized(domain, pageSection, newDOMs, options);

		var args = {
			domain: domain,
			pageSection: pageSection,
			newDOMs: newDOMs
		}
		t.extendArgs(args, options);

		popJSLibraryManager.execute('pageSectionRendered', args);
		pageSection.triggerHandler('pageSectionRendered');
	},

	runScriptsBefore : function(pageSection, newDOMs) {
	
		var t = this;

		var args = {
			pageSection: pageSection,
			newDOMs: newDOMs
		};
		popJSLibraryManager.execute('runScriptsBefore', args);
	},

	jsInitialized : function(block) {

		var t = this;
		return block.hasClass(M.JS_INITIALIZED);
	},
	jsLazy : function(block) {

		var t = this;
		return block.hasClass(M.CLASS_LAZYJS);
	},
	setJsInitialized : function(block) {

		var t = this;
		block.addClass(M.JS_INITIALIZED).removeClass(M.CLASS_LAZYJS);
	},

	initBlockBranches : function(domain, pageSection, blocks, options) {

		var t = this;

		// When being called from the parent node, the branch might still not exist
		// (eg: before it gets activated: #frame-main_blockgroup-tabpanel-main-blockgroup-tabpanel-sections.tab-pane.active > #frame-main_blockgroup-tabpanel-main-blockgroup-tabpanel-sections-body")
		if (!blocks.length) {
			return;
		}

		blocks.each(function() {

			var block = $(this);
			// Ask if it is already initialized or not. This is needed because, otherwise, when opening a tabpane inside of a tabpane,
			// the initialization of leaves in the last level will happen more than once

			var proceed = !t.jsInitialized(block);

			// If the block is lazy initialize, do not initialize first (eg: modals, they are initialized when first shown)
			// force-init: disregard if it's lazy or not: explicitly initialize it
			if (!options['force-init']) {
				
				proceed = proceed && !t.jsLazy(block);
			}

			// Commented so that we can do initBlockBranch(pageSection, pageSection) time and again
			// after it gets rendered appending new DOMs
			if (proceed) {

				t.setJsInitialized(block);
				t.initBlock(domain, pageSection, block, options);

				var jsSettings = popManager.getJsSettings(domain, pageSection, block);
				var blockBranches = jsSettings['initjs-blockbranches'];
				if (blockBranches) {
					t.initBlockBranches(domain, pageSection, $(blockBranches.join(', ')).not('.'+M.JS_INITIALIZED), options);
				}
				var blockChildren = jsSettings['initjs-blockchildren'];
				if (blockChildren) {

					var target = block;
					$.each(blockChildren, function(index, selectors) {
						$.each(selectors, function(index, selector) {

							target = target.children(selector).not('.'+M.JS_INITIALIZED);
						});
					});
					t.initBlockBranches(domain, pageSection, target, options);
				}
			}
		});
	},

	initBlock : function(domain, pageSection, block, options) {

		var t = this;
		options = options || {};
		
		// Do an extend of $options, so that the same object is not used for initializing 2 different blocks.
		// Eg: we don't to change the options.url on the same object for newRuntimePage. That could lead to 2 different blocks using the same URL,
		// it happens when doing openTabs with more than 2 tabs, it does it so quick that the calendar on the right all point to the same URL
		t.initBlockRuntimeMemory(domain, pageSection, block, $.extend({}, options));

		// Allow scripts and others to perform certain action after the runtimeMemory was generated
		t.initializeBlock(pageSection, block, options);

		t.runScriptsBefore(pageSection, block);
		t.runBlockJSMethods(domain, pageSection, block, options);
		
		// Important: place these handlers only at the end, so that handlers specified in popManagerMethods are executed first
		// and follow the same order as above
		// This needs to be 'merged' instead of 'rendered' so that it works also when calling mergeTargetTemplate alone, eg: for the featuredImage
		block.on('rendered', function(e, newDOMs, targetContainers, renderedDomain) {
	
			var block = $(this);
			
			// Set the Block URL for popJSRuntimeManager.addTemplateId to know under what URL to place the session-ids
			popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
			
			t.runScriptsBefore(pageSection, newDOMs);
			
			// This won't execute again the JS on the block when adding newDOMs, because by then
			// the block ID will have disappeared from the lastSessionIds. The only ids will be the new ones,
			// contained in newDOMs
			t.runBlockJSMethods(renderedDomain, pageSection, block, null, options);
		});

		t.blockInitialized(domain, pageSection, /*pageSectionPage, */block, options);

		// And only now, finally, load the block Content (all JS has already been initialized)
		// (eg: function setFilterBlockParams needs to set the filtering params, but does it on js:initialized event. Only after this, load content)
		t.loadBlockContent(domain, pageSection, block);
	},

	initializeBlock : function(pageSection, block, options) {
	
		var t = this;

		var args = {
			pageSection: pageSection,
			block: block
		};
		t.extendArgs(args, options);

		popJSLibraryManager.execute('initBlock', args);

		// Trigger event
		block.triggerHandler('initialize');
	},

	extendArgs : function(args, options) {
	
		var t = this;

		// Allow to extend the args with whatever is provided under 'js-args'
		options = options || {};
		if (options['js-args']) {
			$.extend(args, options['js-args']);
		}
	},

	blockInitialized : function(domain, pageSection, block, options) {
	
		var t = this;

		var args = {
			domain: domain,
			pageSection: pageSection,
			block: block
		};
		t.extendArgs(args, options);

		popJSLibraryManager.execute('blockInitialized', args);

		// Trigger event
		block.triggerHandler('js:initialized');
	},

	loadBlockContent : function(domain, pageSection, block) {

		var t = this;

		if (!t.isContentLoaded(pageSection, block)) {

			// Set the content as loaded
			t.setContentLoaded(pageSection, block);

			var options = {
				action: M.CBACTION_LOADCONTENT,
				'post-data': block.data('post-data')
			};

			// Show disabled layer?
			var jsSettings = t.getJsSettings(domain, pageSection, block);
			if (jsSettings['loadcontent-showdisabledlayer']) {
				options['show-disabled-layer'] = true;
			}
			
			// Comment Leo 07/03/2016: execute the fetchBlock inside document ready, so that it doesn't
			// trigger immediately while rendering the HTML, but waits until all HTML has been rendered.
			// Eg: for the top bar notifications, it was triggering immediately, even before the trigger for /loggedinuser-data,
			// which will bring all the latest notifications. However, because /notifications is stateless, it will save the user "lastaccess" timestamp,
			// so overriding the previous timestamp needed by /loggedinuser-data for the latest notifications
			$(document).ready(function($) {
				t.fetchBlock(pageSection, block, options);
			});
		}
	},

	runPageSectionJSMethods : function(domain, pageSection, options) {
	
		var t = this;

		var sessionIds = popJSRuntimeManager.getPageSectionSessionIds(domain, pageSection);
		if (!sessionIds) return;

		// Make sure it executes the JS in each template only once.
		// Eg: Otherwise, since having MultiLayouts, it will execute 'popover' for each single 'layout-popover-user' template found, and it repeats itself inside a block many times
		var executedTemplates = [];
		var templateMethods = popManager.getPageSectionJsMethods(domain, pageSection);
		$.each(templateMethods, function(template, groupMethods) {
			if (executedTemplates.indexOf(template) == -1) {
				executedTemplates.push(template);
				if (sessionIds[template] && groupMethods) {
					$.each(groupMethods, function(group, methods) {
						var ids = sessionIds[template][group];
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
									t.extendArgs(args, options);
									popJSLibraryManager.execute(method, args);
								});
							}
						}
					});
				}
			}
		});
	},

	runBlockJSMethods : function(domain, pageSection, block, options) {
	
		var t = this;
		t.runJSMethods(domain, pageSection, block, null, null, options);
		
		// Delete the session ids after running the js methods
		popJSRuntimeManager.deleteBlockLastSessionIds(domain, pageSection, block);
	},
	runJSMethods : function(domain, pageSection, block, templateFrom, session, options) {
	
		var t = this;

		// Get the blockSessionIds: these contain the ids added to the block only during the last
		// 'rendered' session. This way, when appending newDOMs (eg: with waypoint on scrolling down),
		// it will execute the JS scripts only on these added elements and not the whole block
		var sessionIds = popJSRuntimeManager.getBlockSessionIds(domain, pageSection, block, session);
		if (!sessionIds) return;
		
		var templateMethods = t.getTemplateJSMethods(domain, pageSection, block, templateFrom);
		t.runJSMethodsInner(domain, pageSection, block, templateMethods, sessionIds, [], options);
	},	
	runJSMethodsInner : function(domain, pageSection, block, templateMethods, sessionIds, executedTemplates, options) {
	
		var t = this;
		options = options || {};

		// For each template, analyze what methods must be executed, and then continue down the line
		// doing the same for contained templates
		var template = templateMethods[M.JS_TEMPLATE];//templateMethods.template;
		var groupMethods = templateMethods[M.JS_METHODS];//templateMethods.methods;

		if (executedTemplates.indexOf(template) == -1) {
			
			executedTemplates.push(template);
			
			if (sessionIds[template] && groupMethods) {
				$.each(groupMethods, function(group, methods) {
					var ids = sessionIds[template][group];
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
								t.extendArgs(args, options);
								popJSLibraryManager.execute(method, args);
							});
						}
					}
				});
			}
		}

		// Continue down the line to the following templates
		if (templateMethods[M.JS_NEXT]/*templateMethods.next*/) {

			// Next is an array, since each template can contain many others.
			$.each(templateMethods[M.JS_NEXT]/*templateMethods.next*/, function(index, next) {
				
				t.runJSMethodsInner(domain, pageSection, /*pageSectionPage, */block, next, sessionIds, executedTemplates, options);
			})
		}
	},
	getTemplateJSMethods : function(domain, pageSection, block, templateFrom) {
	
		var t = this;

		var templateMethods = t.getBlockJsMethods(domain, pageSection, block);
		
		// If not templateFrom provided, then we're using the block as 'from' so we can already
		// return the templateMethods, which always start from the block
		if (!templateFrom) {

			return templateMethods;
		}
		
		// Start searching for the templateFrom down the line templateMethods. 
		// Once found, that's the templateMethods needed => map of templateFrom => methods to execute
		return t.getTemplateJSMethodsInner(pageSection, block, templateFrom, templateMethods);
	},	
	getTemplateJSMethodsInner : function(pageSection, block, templateFrom, templateMethods) {
	
		var t = this;

		// Check if the current level is the template we're looking for. If so, we found it, return it
		// and it will crawl all the way back up
		// if (templateMethods.template == templateFrom) {
		if (templateMethods[M.JS_TEMPLATE] == templateFrom) {

			return templateMethods;
		}

		// If not, keep looking among the contained templates
		var found;
		if (templateMethods[M.JS_NEXT]/*templateMethods.next*/) {
			$.each(templateMethods[M.JS_NEXT]/*templateMethods.next*/, function(index, next) {
				
				found = t.getTemplateJSMethodsInner(pageSection, block, templateFrom, next);
				if (found) {
					// found the result => exit the loop and immediately return this result
					return false;
				}
			});
		}
		return found;
	},	

	maybeRedirect : function(feedback) {

		var t = this;

		// Redirect / Fetch Template?
		if (feedback.redirect && feedback.redirect.url) {

			// Soft redirect => Used after submitting posts
			if (feedback.redirect.fetch) {

				// Comment Leo 22/09/2015: create and anchor and "click" it, so it can be intercepted (eg: Reset password)
				t.click(feedback.redirect.url)
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

		var t = this;

		var url = elem.data('historystate-url');
		var title = elem.data('historystate-title');
		var thumb = elem.data('historystate-thumb');

		popBrowserHistory.replaceState(url);

		// Also update the title in the browser tab
		if (title) {
			t.documentTitle = unescapeHtml(title);
			document.title = t.documentTitle;
		}
	},

	hideIfEmpty : function(domain, pageSection, block) {

		var t = this;

		var feedback = popManager.getBlockFeedback(domain/*popManager.getBlockTopLevelDomain(block)*/, pageSection, block);
		return feedback[M.URLPARAM_HIDEBLOCK];
	},

	isHidden : function(elem) {

		var t = this;
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

		var executed = popJSLibraryManager.execute('isHidden', {targets: elem});
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

		var t = this;

		var executed = popJSLibraryManager.execute('isActive', {targets: elem});
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

		var t = this;

		var blockQueryState = t.getBlockQueryState(pageSection, block);

		// The "|| false" is needed because waypoints doesn't work passing 'undefined' to its enabled, either true or false
		return blockQueryState[M.DATALOAD_CONTENTLOADED] || false;
	},
	setContentLoaded : function(pageSection, block) {

		var t = this;

		var blockQueryState = t.getBlockQueryState(pageSection, block);
		blockQueryState[M.DATALOAD_CONTENTLOADED] = true;
	},

	updateDocument : function(domain) {

		var t = this;

		// Update the title in the page
		t.updateTitle(t.getTopLevelFeedback(domain)[M.URLPARAM_TITLE]);
	},

	updateTitle : function(title) {

		var t = this;

		if (title) {
			t.documentTitle = unescapeHtml(title);
			document.title = t.documentTitle;
		}
	},

	executeSetFilterBlockParams : function(pageSection, block, filter) {
	
		var t = this;
		var blockQueryState = t.getBlockQueryState(pageSection, block);

		// Filter out inputs (input, select, textarea, etc) without value (solution found in http://stackoverflow.com/questions/16526815/jquery-remove-empty-or-white-space-values-from-url-parameters)
		blockQueryState.filter = filter.find('.' + M.FILTER_INPUT).filter(function () {return $.trim(this.value);}).serialize();

		// Only if filtering fields not empty (at least 1 exists) add the name of the filter
		if (blockQueryState.filter) {
			blockQueryState.filter += '&'+filter.find('.' + M.FILTER_NAME_INPUT).serialize();
		}
	},

	setFilterBlockParams : function(pageSection, block, filter) {
	
		var t = this;
		if (t.jsInitialized(block)) {
			t.executeSetFilterBlockParams(pageSection, block, filter);
		}
		else {
			block.one('js:initialized', function() {
				t.executeSetFilterBlockParams(pageSection, block, filter);
			});
		}
	},
	
	// Comment Leo 08/07/2016: filter might or might not be under that block. Eg: called by initBlockProxyFilter it is not
	filter : function(pageSection, block, filter) {
	
		var t = this;
		
		// t.setFilterParams(pageSection, filter);
		t.setFilterBlockParams(pageSection, block, filter);

		// Reload
		t.reload(pageSection, block);

		// Scroll Top to show the "Submitting" message
		t.blockScrollTop(pageSection, block);
	},

	blockScrollTop : function(pageSection, block) {
	
		var t = this;
		
		// Scroll Top to show the "Submitting" message
		var modal = block.closest('.modal');
		if (modal.length == 0) {
			t.scrollToElem(pageSection, block.children('.blocksection-status').first(), true);
		}
		else {
			modal.animate({ scrollTop: 0 }, 'fast');
		}
	},

	setReloadBlockParams : function(pageSection, block) {
	
		var t = this;

		// var blockQueryState = t.getBlockQueryState(pageSection, block);
		// // Delete the data saved
		// // Comment Leo 03/04/2015: this is ugly and should be fixed: not all blocks have these elements (paged, stop-fetching)
		// // The lists have it (eg: My Events) but an Edit Event page does not. However this one can also be reloaded (eg: first loading an edit page when user
		// // is logged out, then log in, it will refetch the block), that's why I added the ifs. However a nicer way should be implemented
		// if (blockQueryState[M.DATALOAD_PARAMS] && blockQueryState[M.DATALOAD_PARAMS][M.URLPARAM_PAGED]) {
		// 	blockQueryState[M.DATALOAD_PARAMS][M.URLPARAM_PAGED] = '';
		// }
		// if (blockQueryState[M.URLPARAM_STOPFETCHING]) {
		// 	blockQueryState[M.URLPARAM_STOPFETCHING] = false;
		// }
		
		// Comment Leo 25/07/2017: do the reset for all domains
		var queryState = t.getBlockMultiDomainQueryState(pageSection, block);
		$.each(queryState, function(domain, blockDomainQueryState) {
			
			// Delete the data saved
			// Comment Leo 03/04/2015: this is ugly and should be fixed: not all blocks have these elements (paged, stop-fetching)
			// The lists have it (eg: My Events) but an Edit Event page does not. However this one can also be reloaded (eg: first loading an edit page when user
			// is logged out, then log in, it will refetch the block), that's why I added the ifs. However a nicer way should be implemented
			if (blockDomainQueryState[M.DATALOAD_PARAMS] && blockDomainQueryState[M.DATALOAD_PARAMS][M.URLPARAM_PAGED]) {
				blockDomainQueryState[M.DATALOAD_PARAMS][M.URLPARAM_PAGED] = '';
			}
			if (blockDomainQueryState[M.URLPARAM_STOPFETCHING]) {
				blockDomainQueryState[M.URLPARAM_STOPFETCHING] = false;
			}
		});
	},	

	refetch : function(pageSection, block, options) {
	
		var t = this;
		
		options = options || {};
		options.action = M.CBACTION_REFETCH;

		block.triggerHandler('beforeRefetch');

		// Refetch
		popManager.reload(pageSection, block, options);
	},	

	reset : function(domain, pageSection, block, options) {
	
		var t = this;
		options = options || {};

		// Sometimes there is no need to restore the initial memory, and even more, it can't be done since it has
		// unwanted consequences. Eg: when creating a user account, it will reset the form, but the messagefeedback
		// with the message "Your account was created successfully" must remain
		if (!options['skip-restore']) {

			// Reset the params. Eg: "pid", "_wpnonce"
			t.restoreInitialBlockMemory(pageSection, block);
		}

		// var domain = popManager.getBlockTopLevelDomain(block);
		t.processBlock(domain, pageSection, block, {operation: M.URLPARAM_OPERATION_REPLACE, action: M.CBACTION_RESET, reset: true});
	},	

	reload : function(pageSection, block, options) {
	
		var t = this;
		// Options: it will potentially already have attr 'action'
		options = options || {};
		options.operation = M.URLPARAM_OPERATION_REPLACE;
		options.reload = true;

		// If pressing on reload, then we must also hide the latestcount message
		options['hide-latestcount'] = true;

		// Delete the data saved
		t.setReloadBlockParams(pageSection, block);

		block.triggerHandler('beforeReload');

		// Load everything again
		t.fetchBlock(pageSection, block, options);
	},	

	loadLatest : function(domain, pageSection, block, options) {
	
		var t = this;
		options = options || {};
		var blockDomainQueryState = t.getBlockDomainQueryState(domain, pageSection, block);

		// Add the latest content on top of everything else
		options.operation = M.URLPARAM_OPERATION_PREPEND;

		// Do not check flag Stop Fetching, that is needed for the appended older content, not prepended newer one
		options['skip-stopfetching-check'] = true;

		// Delete the latestCount when fetch succedded
		options['hide-latestcount'] = true;

		// Add the action and the timestamp
		var post_data = {};
		post_data[M.URLPARAM_ACTION] = M.URLPARAM_ACTION_LATEST;
		post_data[M.URLPARAM_TIMESTAMP] = blockDomainQueryState[M.URLPARAM_TIMESTAMP];
		options['onetime-post-data'] = $.param(post_data);

		block.triggerHandler('beforeLoadLatest');

		// Load latest content. 
		t.fetchBlock(pageSection, block, options);
	},	

	handlePageSectionLoadingStatus : function(pageSection, operation, options) {
		
		var t = this;
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
		
			var status = popPageSectionManager.getPageSectionStatus(pageSection);
			loading = status.find('.pop-loading');

			// In addition, also hide the error message
			var error = status.find('.pop-error');
			error.addClass('hidden');
		}

		t.handleLoadingStatus(loading, operation, options)
	},

	handleLoadingStatus : function(loading, operation) {
		
		var t = this;

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

		var t = this;
		options = options || {};

		var pageSection = t.getFetchTargetPageSection(options.target);

		// When there's a javascript error, hierarchyParams is null. So check for this and then do normal window redirection
		var params = t.getPageSectionParams(pageSection);
		if (!params) {
			if (options['noparams-reload-url']) {
				
				window.location = url;
			}
			return;
		}
		
		t.fetchPageSection(pageSection, url, options);
	},

	removeLocalStorageItem : function(path, key) {

		var t = this;

		// Allow typeahead to also delete its entries
		var args = {result: key.startsWith(path), key: key, path: path};
		popJSLibraryManager.execute('removeLocalStorageItem', args);
		return args.result;
	},

	initLocalStorage : function() {

		var t = this;

		// Delete all stale entries
		if (M.USELOCALSTORAGE && supports_html5_storage()) {
				
			var latest = localStorage['PoP:version'];
			if (!latest || (latest != M.VERSION)){

				// Delete all stale entries: all those starting with any of the allowed domains
				// Solution taken from https://stackoverflow.com/questions/7591893/html5-localstorage-jquery-delete-localstorage-keys-starting-with-a-certain-wo
				Object.keys(localStorage).forEach(function(key) { 

					// Allow typeahead to also delete its entries
					M.ALLOWED_DOMAINS.forEach(function(path) { 

						if (t.removeLocalStorageItem(path, key)) {
							localStorage.removeItem(key); 
							return -1;
						}
					});
				}); 

				// Save the current version
				localStorage['PoP:version'] = M.VERSION;
			}
		}
	},

	openTabs : function() {

		var t = this;
		if (!M.KEEP_OPEN_TABS) return;

		// Get all the tabs open from the previous session, and open them already		
		var options = {
			silentDocument: true,
			'js-args': {
				inactivePane: true,

				// Do not store these tabs again when they come back from the fetch
				addOpenTab: false
			}
		};

		var currentURL = t.getTabsCurrentURL();

		var tabs = t.getScreenOpenTabs();
		$.each(tabs, function(target, urls) {

			// Open the tab on the corresponding target
			options.target = target;

			// If on the main pageSection...
			if (target == M.URLPARAM_TARGET_MAIN) {

				// Do not re-open the one URL the user opened
				var pos = urls.indexOf(currentURL);
				if (pos !== -1) {
					
					// Remove the entry
					urls.splice(pos, 1);
				}
			}

			// Open all tabs
			$.each(urls, function(index, url) {

				t.fetch(url, options);
			});
		});
	},

	getTabsCurrentURL : function() {

		var t = this;
		
		var currentURL = window.location.href;
		
		// Special case for the homepage: the link must have the final '/'
		if (currentURL == M.HOME_DOMAIN) {
			currentURL = M.HOME_DOMAIN+'/';
		}

		// Special case for qTranslateX: if we are loading the homepage, without the language information
		// (eg: https://kuwang.com.ar), and a tab with the language is open (eg: https://kuwang.com.ar/es/)
		// then have it removed, or the homepage will be open twice. For that, we assume the current does have
		// the language information, so it will be removed below
		if (currentURL == M.HOME_DOMAIN+'/' && M.HOMELOCALE_URL) {
			currentURL = M.HOMELOCALE_URL+'/';
		}

		return currentURL;
	},

	getOpenTabsKey : function() {

		var t = this;
		
		// Comment Leo 16/01/2017: we can all params set in the topLevel feedback directly
		var key = M.LOCALE;

		// The tabs will always be opened locally, so it is ok to assume that the domain is our local URL
		var domain = M.HOME_DOMAIN;

		// Also add all the other "From Server" params if initially set (eg: themestyle, settingsformat, mangled)
		var params = t.getTopLevelFeedback(domain)[M.DATALOAD_PARAMS];
		$.each(params, function(param, value) {
			key += '|'+param+'='+value;
		});

		return key;
	},

	getScreenOpenTabs : function() {

		var t = this;
		var tabs = t.getOpenTabs();
		var key = t.getOpenTabsKey();

		return tabs[key] || {};
	},

	keepScreenOpenTab : function(url, target) {

		// Function executed to only keep a given tab open and close all the others.
		// Used for the alert "Do you want to open the previous session tabs?" 
		// If clicking cancel, then remove all other tabs, for next time that the user opens the browser
		var t = this;
		var tabs = t.getOpenTabs();
		var key = t.getOpenTabsKey();

		// Remove all other targets also, so that it delets open pages in addons pageSection
		tabs[key] = {};
		tabs[key][target] = [url];
		t.storeData('PoP:openTabs', tabs);
	},

	getOpenTabs : function() {

		var t = this;
		if (!M.KEEP_OPEN_TABS) return {};

		return t.getStoredData('PoP:openTabs') || {};
	},

	addOpenTab : function(url, target) {

		var t = this;
		if (!M.KEEP_OPEN_TABS) return false;

		var tabs = t.getOpenTabs();
		var key = t.getOpenTabsKey();
		tabs[key] = tabs[key] || {};
		tabs[key][target] = tabs[key][target] || [];
		if (tabs[key][target].indexOf(url) > -1) {

			// The entry already exists
			return false;			
		}

		tabs[key][target].push(url);
		t.storeData('PoP:openTabs', tabs);

		return true;
	},

	removeOpenTab : function(url, target) {

		var t = this;
		if (!M.KEEP_OPEN_TABS) return false;

		var tabs = t.getOpenTabs();
		var key = t.getOpenTabsKey();
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
		t.storeData('PoP:openTabs', tabs);

		return true;
	},

	replaceOpenTab : function(fromURL, toURL, target) {

		var t = this;
		if (!M.KEEP_OPEN_TABS) return;

		if (t.removeOpenTab(fromURL, target)) {
			t.addOpenTab(toURL, target);
		}
	},

	getStoredData : function(localStorageKey, use_version) {

		var t = this;

		// Check if a response is stored in local storage for that combination of URL and params
		if (M.USELOCALSTORAGE && supports_html5_storage()) {
				
			var stored = localStorage[localStorageKey];
			if (stored) {

				// Transform the string back into JSON
				stored = $.parseJSON(stored);

				if (use_version) {

					// Make sure the response was generated for the current version of the software
					// And also check if it has not expired
					if ((stored.version == M.VERSION) && (typeof stored.expires == 'undefined' || stored.expires > Date.now())){

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

	storeData : function(localStorageKey, value, expires) {

		var t = this;
		if (M.USELOCALSTORAGE && supports_html5_storage()) {
				
			var stored = {
				version: M.VERSION,
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
				// console.error(err.message);
			}
		}
	},

	fetchPageSection : function(pageSection, url, options) {

		var t = this;
		var params = t.getPageSectionParams(pageSection);

		// When there's a javascript error, hierarchyParams is null. So check for this and then do normal window redirection
		if (!params) {
			return;
		}

		// If already loading this url (user pressed twice), then do nothing
		if (params.loading.indexOf(url) > -1) {

			return;
		}

		options = options || {};

		var target = t.getTarget(pageSection);
		var domain = getDomain(url);

		// Initialize the domain, if needed
		t.maybeInitializeDomain(domain);

		// Allow PoP Service Workers to modify the options, adding the Network First parameter to allow to fetch the preview straight from the server
		// Also, re-take the URL from the args, so plugins can also modify it. Eg: routing through a CDN with pop-cdn
		var args = {
			domain: domain,
			options: options, 
			url: url
		};
		popJSLibraryManager.execute('modifyFetchArgs', args);
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
		fetchUrl = add_query_arg(M.URLPARAM_TARGET, target, fetchUrl);
		fetchUrl = add_query_arg(M.URLPARAM_MODULE, M.URLPARAM_MODULE_SETTINGSDATA, fetchUrl);
		fetchUrl = add_query_arg(M.URLPARAM_OUTPUT, M.URLPARAM_OUTPUT_JSON, fetchUrl);

		// Contains the attr for the Theme
		var topLevelFeedback = t.getTopLevelFeedback(domain);
		var paramsData = $.extend({}, topLevelFeedback[M.DATALOAD_PARAMS], params[M.DATALOAD_PARAMS]);
		// Extend with params given through the options. Eg: WSL "action=authenticated, provider=facebook" params to log-in the user
		if (options.params) {
			$.extend(paramsData, options.params);
		}
		var postData = $.param(paramsData);
		var localStorageKey;

		// Check if a response is stored in local storage for that combination of URL and params
		localStorageKey = fetchUrl+'|'+postData;
		var stored = t.getStoredData(localStorageKey);
		if (stored) {

			t.prePageSectionSuccess(pageSection, stored, options);
			t.processPageSectionResponse(domain, pageSection, stored, options);
			t.triggerURLFetched(url);

			// That's it!
			return;
		}

		var status = popPageSectionManager.getPageSectionStatus(pageSection);
		var error = status.find('.pop-error');

		// Show the Disabled Layer over a block?
		if (options['disable-layer']) {
			options['disable-layer'].children('.pop-disabledlayer').removeClass('hidden');
		}

		var crossdomain = t.getCrossDomainOptions(fetchUrl);

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
				jqXHR.url = url;//settings.url;

				// Save the fetchUrl to retrieve it under 'complete'
				params.url[jqXHR.url] = url;
				params.target[jqXHR.url] = target;

				// Keep the URL being fetched for updating stale json content using Service Workers
				params['fetch-url'][jqXHR.url] = settings.url;

				// Save the url being loaded
				params.loading.push(url);
		
				t.handlePageSectionLoadingStatus(pageSection, 'add', options);
			},
			complete: function(jqXHR) {

				// Everything below can be executed even if the deferred object executed in .processPageSectionResponse
				// has not resolved yet. 
				var url = params.url[jqXHR.url];
				delete params.url[jqXHR.url];
				delete params.target[jqXHR.url];
				delete params['fetch-url'][jqXHR.url];

				params.loading.splice(params.loading.indexOf(url), 1);

				t.handlePageSectionLoadingStatus(pageSection, 'remove', options);

				// Callback when the url was fetched
				if (options['urlfetched-callbacks']) {

					var handler = 'urlfetched:'+escape(url);
					$.each(options['urlfetched-callbacks'], function(index, callback) {

						$(document).one(handler, callback);
					});
				}

				t.triggerURLFetched(url);

				// Remove the Disabled Layer over a block
				if (options['disable-layer']) {
					options['disable-layer'].children('.pop-disabledlayer').addClass('hidden');
				}
			},			
			success: function(response, textStatus, jqXHR) {

				t.prePageSectionSuccess(pageSection, response, options);

				// Hide the error message
				error.addClass('hidden');

				// If the original URL had a hashtag (eg: /add-post/#1482655583982), the returning url
				// will also have one (using is_multipleopen() => add_unique_id), but not the same one, then 
				// replace the original one with the new one in PoP:openTabs, or otherwise it will keep adding new tabs to the openTabs, 
				// which are the same tab but duplicated for having different hashtags in the URL
				var url = params.url[jqXHR.url];
				var feedbackURL = response.feedback.toplevel[M.URLPARAM_URL];
				var target = params.target[jqXHR.url];
				
				if (url != feedbackURL) {
					t.replaceOpenTab(url, feedbackURL, target);
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
				if (response.feedback.toplevel[M.URLPARAM_STORELOCAL]) {
						
					t.storeData(localStorageKey, response);
				}
				t.processPageSectionResponse(domain, pageSection, response, options);
			},
			error: function(jqXHR, textStatus, errorThrown) {

				var fetchedUrl = params.url[jqXHR.url];
				// Show an error if the fetch was not silent
				if (!options.silentDocument) {
					t.showError(pageSection, t.getError(fetchedUrl, jqXHR, textStatus, errorThrown));
				}
				pageSection.triggerHandler('fetchFailed');
			}
		}));
	},

	prePageSectionSuccess : function(pageSection, response, options) {

		var t = this;
		
		// Allow pop-cdn to incorporate the thumbprint values in the topLevelFeedback before backgroundLoad
		t.pageSectionFetchSuccess(pageSection, response);

		// The server might have requested to load extra URLs (eg: forcedserverload_fields)
		t.backgroundLoad(response.feedback.toplevel[M.URLPARAM_BACKGROUNDLOADURLS]);
	},

	getCrossDomainOptions : function(url) {

		var t = this;
		var options = {};

		// If the URL is not from this same website, but from any aggregated website, then allow the cross domain
		if(!url.startsWith(M.HOME_DOMAIN)) {

			$.each(M.ALLOWED_DOMAINS, function(index, allowed) {

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

		var t = this;

		var status = popPageSectionManager.getPageSectionStatus(pageSection);
		var error = status.find('.pop-error');
		
		error.children('div.pop-box').html(message);
		error.removeClass('hidden');
		t.scrollTop(error);
	},

	triggerURLFetched : function(url) {

		var t = this;

		// Signal that this URL was fetched. Eg: btnSetLoading
		// If not escaped, the catch doesn't work
		$(document).triggerHandler('urlfetched:'+escape(url));
		$(document).triggerHandler('urlfetched', [url]);
	},

	processPageSectionResponse : function(domain, pageSection, response, options) {

		var t = this;

		// Save all entries in the replicable, both for a new fetch, or also if retrieved from localStorage
		var target = t.getTarget(pageSection);

		// For each URL to be intercepted, save under which page URL and target its memory has been stored
		t.saveUrlPointers(response, target);
		t.addReplicableMemory(response, target);
		t.addInitialBlockMemory(response);
		
		// Integrate the DB
		t.integrateDatabases(domain, response);

		// Add to the queue of promises to execute and merge the template
		var dfd = $.Deferred();
		var lastPromise = t.mergingTemplatePromise;//[domain];
		t.mergingTemplatePromise/*[domain]*/ = dfd.promise();

		// If while processing the pageSection we get error "Mempage not available",
		// do not let it break the execution of other JS, contain it
		if (lastPromise) {
			lastPromise.done(function() {
			
				try {
					t.processPageSection(domain, pageSection, response, options);
				}
				catch(err) {
					// Do nothing
					console.error(err.message);
				}
			});
		}
		else {
			try {
				t.processPageSection(domain, pageSection, response, options);
			}
			catch(err) {
				// Do nothing
				console.error(err.message);
			}
		}

		// Resolve the deferred
		dfd.resolve();
	},

	maybeUpdateDocument : function(domain, pageSection, options) {
		
		var t = this;
		options = options || {};

		// Check if explicitly said to not update the document
		if (options.silentDocument) {
			return;
		}

		// Sometimes update (eg: main), sometimes not (eg: modal)
		var settings = t.getFetchPageSectionSettings(pageSection);
		if (settings.updateDocument) {

			if (!options.skipPushState) {
				
				var topLevelFeedback = t.getTopLevelFeedback(domain);
				popBrowserHistory.pushState(topLevelFeedback[M.URLPARAM_URL]);
			}
			t.updateDocument(domain);
		}
	},

	processPageSection : function(domain, pageSection, response, options) {
		
		var t = this;

		var target = t.getTarget(pageSection);

		// Integrate the response feedback
		t.integrateTopLevelFeedback(domain, response);
		var topLevelFeedback = t.getTopLevelFeedback(domain);

		// Show any error message from the toplevel feedback
		var errorMessage = topLevelFeedback[M.URLPARAM_ERROR];
		if (errorMessage) {
			t.showError(pageSection, errorMessage);
		}

		// If reloading the URL, then we fetched that URL from the server independently of that page already loaded in the client (ie: it will not be intercepted)
		// When the page comes back, we gotta destroy the previous one (eg: Add Highlight)
		var url = topLevelFeedback[M.URLPARAM_URL];
		if (options.reloadurl) {

			// Get the url, and destroy those pages
			t.triggerDestroyTarget(url, target);
		}

		// Set the "silent document" value return in the topLevelFeedback
		// But we still allow the value to have been set before. Eg: history.js (makes it silent when clicking back/forth on browser)
		if (topLevelFeedback[M.URLPARAM_SILENTDOCUMENT]) {
			options.silentDocument = true;
		}
		t.maybeUpdateDocument(domain, pageSection, options);

		// Do a Redirect?
		if (options.maybeRedirect) {
			if (t.maybeRedirect(topLevelFeedback)) return;
		}

		// Integrate the response		
		t.integratePageSection(domain, response);

		// First Check if the response also includes other pageSections. Eg: when fetching Projects, it will also bring its MainRelated
		// Check first so that later on it can refer javascript on these already created objects
		$.each(response.settings.configuration, function(rpssId, rpsConfiguration) {

			var psId = rpsConfiguration[M.JS_FRONTENDID];//rpsConfiguration['frontend-id'];
			var pageSection = $('#'+psId);
			t.renderPageSection(domain, pageSection, options);
		
			// Trigger
			pageSection.triggerHandler('fetched', [options, url, domain]);
			pageSection.triggerHandler('completed');
		});	
	},

	scrollToElem : function(elem, position, animate) {

		var t = this;
		popJSLibraryManager.execute('scrollToElem', {elem: elem, position: position, animate: animate});
	},
	scrollTop : function(elem, top, animate) {

		var t = this;

		// This will call functions from perfectScrollbar, bootstrap modal, and custom functions
		popJSLibraryManager.execute('scrollTop', {elem: elem, top: top, animate: animate});
	},
	getPosition : function(elem) {

		var t = this;

		// Allow to have custom-functions.js provide the implementation of this function for the main pageSection, and perfectScrollbar also
		var executed = popJSLibraryManager.execute('getPosition', {elem: elem});
		var ret = 0;
		$.each(executed, function(index, value) {
			if (value) {
				ret = value;
				return -1;
			}
		});
		
		return ret;
	},

	getSettingsId : function(objectOrId) {
		
		// target: pageSection or Block, or already pssId or bsId (when called from a .tmpl.js file)
		var t = this;

		if ($.type(objectOrId) == 'object') {
			
			var object = objectOrId;
			return object.attr('data-settings-id');
		}

		return objectOrId;
	},

	getError : function(url, jqXHR, textStatus, errorThrown) {

		var t = this;
		if (jqXHR.status === 0) { // status = 0 => user is offline
			
			return M.ERROR_OFFLINE.format(url);
		}
		else if (jqXHR.status == 404) {
			
			return M.ERROR_404;
		}
		return M.ERROR_MSG.format(url);
	},

	closeMessageFeedback : function(elem) {
		
		var t = this;

		// Message is an alert, so close it
		$(document).ready( function($) {
			elem.find('.pop-messagefeedback').removeClass('fade').alert('close');
		});
	},
	closeMessageFeedbacks : function(pageSection) {
		
		var t = this;

		// Message is an alert, so close it
		$(document).ready( function($) {
			pageSection.find('.pop-messagefeedback').removeClass('fade').alert('close');
		});
	},

	processBlock : function(domain, pageSection, block, options) {
		
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var memory = t.getMemory(domain);

		// Add 'items' from the dataset, as to be read in scroll-inner.tmpl / carousel-inner.tmpl
		options.extendContext = {
			items: memory.dataset[pssId][bsId],
			ignorePSRuntimeId: true
		};

		// Set the Block URL for popJSRuntimeManager.addTemplateId to know under what URL to place the session-ids
		popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);

		t.renderTarget(domain, pageSection, block, options);
	},

	fetchBlock : function(pageSection, block, options) {
		
		var t = this;
		options = options || {};

		if (!options['skip-stopfetching-check']) {

			// // If 'stop-fetching' is true then don't bring anymore
			// var blockQueryState = t.getBlockQueryState(pageSection, block);
			// if (blockQueryState[M.URLPARAM_STOPFETCHING]) {

			// 	return;
			// }
			if (t.stopFetchingBlock(pageSection, block)) {
				return;
			}
		}

		t.executeFetchBlock(pageSection, block, options);
	},

	getBlockPostData : function(domain, pageSection, block, options) {
		
		var t = this;
		options = options || {};
		var paramsGroup = options.paramsGroup || 'all';

		var blockQueryState = t.getBlockQueryState(pageSection, block);
		var blockDomainQueryState = t.getBlockDomainQueryState(domain, pageSection, block);
		
		// Filter all params which are empty
		var params = {};
		if (paramsGroup == 'all') {
			$.extend(params, blockQueryState[M.DATALOAD_PARAMS]);

			// Also add the params specific to that domain
			if (blockDomainQueryState[M.DATALOAD_PARAMS]) {
				$.extend(params, blockDomainQueryState[M.DATALOAD_PARAMS]);
			}
		}
		
		// Visible params: visible when using 'Open All' button
		if (blockQueryState[M.DATALOAD_VISIBLEPARAMS]) {
			$.extend(params, blockQueryState[M.DATALOAD_VISIBLEPARAMS]);
		}

		if (blockDomainQueryState[M.DATALOAD_VISIBLEPARAMS]) {
			$.extend(params, blockDomainQueryState[M.DATALOAD_VISIBLEPARAMS]);
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

		var t = this;

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
			t.scrollTop(error);
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
		
		var t = this;
		options = options || {};		

		// Comment Leo 25/07/2017: transition from single domain (queryUrl) to multidomain
		// // If the block has no query url, then nothing to do
		// // (eg: when 'lazy-initializing' the offcanvas locations map, it has no content to fetch)
		// var fetchUrl = t.getQueryUrl(pageSection, block);
		// if (!fetchUrl) {
		// 	return;
		// }

		// Default type: GET
		var type = options.type || 'GET';

		// If the params are already sent in the options, then use it
		// It's needed for loading the 'Edit Event' page, where the params are provided by the collapse in attr data-params
		// Override the post-data in the params, and then use it time and again (needed for the Navigator, it will set the filtering fields of the intercepted url into its post-data and send these again and again on waypoint scroll down - its own filter fields are empty!)
		var blockQueryState = t.getBlockQueryState(pageSection, block);
		if (options['post-data']) {
			blockQueryState['post-data'] = options['post-data'];
		}

		// var domain = popManager.getBlockTopLevelDomain(block);

		var fetch_urls = {};
		var query_urls = t.getQueryMultiDomainUrls(pageSection, block);
		$.each(query_urls, function(domain, fetchUrl) {

			// If the block has no query url, then continue to next one
			if (!fetchUrl) {
				return;
			}

			// If it must stop fetching for this one domain, then continue
			if (!options['skip-stopfetching-check']) {
				if (t.stopFetchingDomainBlock(domain, pageSection, block)) {
					return;
				}	
			}

			// Initialize the domain, if needed
			t.maybeInitializeDomain(domain);

			// Initialize the multidomain feedback, so that when requesting for the feedback from a domain different than the local one, below, it doesn't explode
			t.initMultiDomainFeedback(domain, pageSection, block);

			// Allow PoP Service Workers to modify the options, adding the Network First parameter to allow to fetch the preview straight from the server
			var args = {
				domain: domain,
				options: options, 
				url: fetchUrl,
				type: type
			};
			popJSLibraryManager.execute('modifyFetchBlockArgs', args);
			fetchUrl = args.url;

			var topLevelFeedback = t.getTopLevelFeedback(domain);
			var pageSectionFeedback = t.getPageSectionFeedback(domain, pageSection);
			var blockFeedback = t.getBlockFeedback(domain, pageSection, block);
			
			var blockQueryStateData = $.extend({}, topLevelFeedback[M.DATALOAD_PARAMS], pageSectionFeedback[M.DATALOAD_PARAMS], blockFeedback[M.DATALOAD_PARAMS]);
			
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
			var block_post_data = t.getBlockPostData(domain, pageSection, block);
			if (post_data && block_post_data) {
				post_data += '&';
			}
			post_data += block_post_data;

			// Add a param to tell the back-end we are doing ajax
			var target = t.getTarget(pageSection);
			fetchUrl = add_query_arg(M.URLPARAM_TARGET, target, fetchUrl);
			fetchUrl = add_query_arg(M.URLPARAM_MODULE, M.URLPARAM_MODULE_DATA, fetchUrl);
			fetchUrl = add_query_arg(M.URLPARAM_OUTPUT, M.URLPARAM_OUTPUT_JSON, fetchUrl);

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
				loading.removeClass('hidden');
				error.addClass('hidden');	

				// Close Message
				t.closeMessageFeedback(block);
			}

			// Hide buttons / set loading msg
			t.triggerEvent(pageSection, block, 'beforeFetch', [options]);

			// When doing refetch, and initializing the data (aka doing GET, not POST), then show the 'disabled' layer
			if (options['show-disabled-layer']) {
				block.children('.pop-disabledlayer').removeClass('hidden');
			}

			// Keep a timestamp to send in the status, to show that all 'fetchDomainCompleted' belong to the same operation
			// It is needed for the map, for removing markers after reloading, so that it is done only after the first domain fetching data, and not all of them
			var timestamp = Date.now();
			$.each(fetch_urls, function(domain, fetchInfo) {

				var fetchUrl = fetchInfo.url;
				var loadingUrl = fetchInfo.loading;
				var post_data = fetchInfo.data;
				var crossdomain = t.getCrossDomainOptions(fetchUrl);

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
						
						// the url is needed to re-identify the block, since all it's given to us on the response is the settings-id
						// which is not enough anymore since we can have different blocks with the same settings-id, so we need to find once again the id
						blockQueryState['paramsscope-url'][loadingUrl] = t.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/;
						
						// Is it a realod?
						if (options.reload) {

							blockQueryState.reload.push(loadingUrl);				
						}
		
						if (!options['skip-status']) {
							t.handleLoadingStatus(loading, 'add');
						}
					},	
					success: function(response, textStatus, jqXHR) {

						// Allow pop-cdn to incorporate the thumbprint values in the topLevelFeedback before backgroundLoad
						t.blockFetchSuccess(pageSection, block, response);

						// Start loading extra urls
						t.backgroundLoad(response.feedback.toplevel[M.URLPARAM_BACKGROUNDLOADURLS]);

						// loadLatest: when it comes back, hide the latestcount div
						if (options['hide-latestcount']) {
							block.children('.blocksection-latestcount')
								.find('.pop-latestcount').addClass('hidden')
								.find('.pop-count').text('0');
						}

						// We need to pass the status down the road, so methods can know if this is the first domain processed from a list of domains or not
						// It is needed for when doing operation REPLACE, do it only the first time, but not later, or it will replace data from previous domains
						var status = t.getMultiDomainBlockStatus(blockQueryState, fetch_urls, timestamp);

						// Comment Leo 03/12/2015: Using Deferred Object for the following reason:
						// Templates for 2 URLs cannot be merged at the same time, since they both access the same settings (unique thread)
						// So we need to make the template merging process synchronous. For that we implement a queue of deferred object,
						// Where each one of them merges only after the previous one process has finished, + mergingTemplatePromise = false for the first case and when there are no other elements in the queue
						// By the end of the success function all merging will be done, then we can proceed with following item in the queue
						var lastPromise = t.mergingTemplatePromise;//[domain];
						var dfd = $.Deferred();
						var thisPromise = dfd.promise();
						t.mergingTemplatePromise/*[domain]*/ = thisPromise;
						if (lastPromise) {
							lastPromise.done(function() {
								// Catch the "Mempage not available" exception, or the app might crash
								try {
									t.executeFetchBlockSuccess(pageSection, block, /*blockQueryState, */response, status, jqXHR);
								}
								catch(err) {
									// Do nothing
									// console.error(err.message);
									console.log(err.message);
								}
							});
						}
						else {
							// Catch the "Mempage not available" exception, or the app might crash
							try {
								t.executeFetchBlockSuccess(pageSection, block, /*blockQueryState, */response, status, jqXHR);
							}
							catch(err) {
								// Do nothing
								// console.error(err.message);
								console.log(err.message);
							}
						}

						// Resolve this promise
						dfd.resolve();
					},
					error: function(jqXHR, textStatus, errorThrown) {

						t.handleBlockError(error, jqXHR, options);
						t.triggerEvent(pageSection, block, 'fetchFailed');
					},
					complete: function(jqXHR) {

						// Following instructions can be executed immediately when calling `complete`,
						// even if the merging has yet not taken place
						var status = t.getMultiDomainBlockStatus(blockQueryState, fetch_urls, timestamp);
						// var status = {
						// 	timestamp: timestamp,
						// };

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
						// status.isFirst = (blockQueryState.timestamps[timestamp].length === Object.keys(fetch_urls).length);
						// status.isLast = (blockQueryState.timestamps[timestamp].length === 1);
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
							t.handleLoadingStatus(loading, 'remove');
						}

						// Following instructions can be executed only after the merging has finished
						var lastPromise = t.mergingTemplatePromise;//[domain];
						var dfd = $.Deferred();
						var thisPromise = dfd.promise();
						t.mergingTemplatePromise/*[domain]*/ = thisPromise;

						// If while processing the pageSection we get error "Mempage not available",
						// do not let it break the execution of other JS, contain it
						if (lastPromise) {
							lastPromise.done(function() {
								try {
									t.fetchBlockComplete(domain, pageSection, block, /*blockQueryState, */status, options);
								}
								catch(err) {
									// Do nothing
									// console.error(err.message);
									console.log(err.message);
								}
							});
						}
						else {
							try {
								t.fetchBlockComplete(domain, pageSection, block, /*blockQueryState, */status, options);
							}
							catch(err) {
								// Do nothing
								// console.error(err.message);
								console.log(err.message);
							}
						}

						// Resolve the deferred
						dfd.resolve();
					}
				}));
			});
		}
	},

	fetchBlockComplete: function(domain, pageSection, block, /*params, */status, options) {

		var t = this;

		var blockQueryState = t.getBlockQueryState(pageSection, block);

		// Remove the 'disabled' layer
		block.children('.pop-disabledlayer').addClass('hidden');

		// Display the dataset count?
		if (options['datasetcount-target']) {
			
			t.displayDatasetCount(domain, pageSection, block, $(options['datasetcount-target']), options['datasetcount-updatetitle']);
		}

		// Only if not loading other URLs still
		if (!blockQueryState.loading.length) {
			
			var loading = block.find('.pop-loading');
			loading.addClass('hidden');	
		}

		// Add/Remove class "pop-stopfetching"
		// var blockQueryState = t.getBlockQueryState(pageSection, block);
		// if (blockQueryState[M.URLPARAM_STOPFETCHING]) {
		if (t.stopFetchingBlock(pageSection, block)) {

			block.addClass('pop-stopfetching');
		}
		else {
			
			block.removeClass('pop-stopfetching');
		}

		t.triggerEvent(pageSection, block, 'fetchDomainCompleted', [status, domain]);

		// If this is the last domain fetched
		if (status.isLast) {
			
			t.triggerEvent(pageSection, block, 'fetchCompleted', [status]);
		}
	},

	stopFetchingBlock : function(pageSection, block) {

		// There are 2 interpretations to stop fetching data for the block: with domain and without domain
		// #1 - with domain: check if that specific domain can still fetch data
		// #2 - without domain: check the whole block, i.e. all domains must have the stop-fetching flag in true
		var t = this;
		var ret = true;
		var query_urls = t.getQueryMultiDomainUrls(pageSection, block);
		$.each(query_urls, function(domain, query_url) {

			var blockQueryDomainState = t.getBlockDomainQueryState(domain, pageSection, block);
			if (!blockQueryDomainState[M.URLPARAM_STOPFETCHING]) {
				ret = false;
				return -1;
			}
		});

		return ret;
	},

	stopFetchingDomainBlock : function(domain, pageSection, block) {

		var t = this;
		var blockQueryDomainState = t.getBlockDomainQueryState(domain, pageSection, block);
		return blockQueryDomainState[M.URLPARAM_STOPFETCHING];
	},

	displayDatasetCount : function(domain, pageSection, block, target, updateTitle) {
		
		var t = this;

		var dataset = t.getBlockDataset(domain/*popManager.getBlockTopLevelDomain(block)*/, pageSection, block);
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
					document.title = '('+count+') '+t.documentTitle;
				}
			}
		}
	},

	initMultiDomainFeedback : function(domain, pageSection, block) {

		var t = this;

		// Check if the domain from which we fetched the info is different than the loaded URL
		// If that's the case, then it's data aggregation from a different website, we initialize
		// all the properties from that domain
		var memory = t.getMemory(domain);
		var localDomain = t.getBlockTopLevelDomain(block);
		if (domain != localDomain) {

			var pssId = t.getSettingsId(pageSection);
			var bsId = t.getSettingsId(block);
			var localMemory = t.getMemory(localDomain);

			// Copy the properties from the local memory's domain to the operating domain?
			// This is done the first time it is accessed, eg: if memory.feedback.toplevel is empty
			// Copy using deep, so that the copy is not done by reference. Otherwise, all domains topLevels will be overriding each other!
			if ($.isEmptyObject(memory.feedback.toplevel)) {
				
				memory.feedback.toplevel = $.extend(true, {}, localMemory.feedback.toplevel);

				// Important: do NOT copy everything! In particular, do not copy the loggedin user information
				if (typeof memory.feedback.toplevel[M.DATALOAD_USER] != 'undefined') {
					delete memory.feedback.toplevel[M.DATALOAD_USER];
				}
			}
			if ($.isEmptyObject(memory.feedback.pagesection[pssId])) {
				
				memory.feedback.pagesection[pssId] = $.extend(true, {}, localMemory.feedback.pagesection[pssId]);
				memory.feedback.block[pssId] = {};
			}
			if ($.isEmptyObject(memory.feedback.block[pssId][bsId])) {
				
				memory.feedback.block[pssId][bsId] = $.extend(true, {}, localMemory.feedback.block[pssId][bsId]);
			}
		}
	},

	initMultiDomainMemory : function(domain, pageSection, block, response) {

		var t = this;

		// Check if the domain from which we fetched the info is different than the loaded URL
		// If that's the case, then it's data aggregation from a different website, we initialize
		// all the properties from that domain
		var memory = t.getMemory(domain);
		var localDomain = popManager.getBlockTopLevelDomain(block);
		if (domain != localDomain) {

			var localMemory = t.getMemory(localDomain);

			// Copy the properties from the local memory's domain to the operating domain?
			// This is done the first time it is accessed, eg: if memory.feedback.toplevel is empty
			// if ($.isEmptyObject(memory.dataset)) {
				
			// 	// Replicate it from the local domain one
			// 	memory.runtimesettings = {
			// 		'query-url': {},
			// 		'query-multidomain-urls': {},
			// 		configuration: {},
			// 		'js-settings': {},
			// 	};
			// 	memory.settings = {
			// 		'js-settings': {},
			// 		jsmethods: {
			// 			pagesection: {},
			// 			block: {},
			// 		},
			// 		'templates-cbs': {},
			// 		'templates-paths': {},
			// 		'db-keys': {},
			// 		configuration: {},
			// 		// 'template-sources': $.extend({}, localMemory.settings['template-sources']),
			// 	};	
			// }

			$.each(response['query-state'].general, function(rpssId, rpsParams) {	

				// If the memory is empty (eg: first time that we are loading a different domain), then recreate it under the domain scope
				if ($.isEmptyObject(memory['query-state'].general[rpssId])) {

					memory['query-state'].general[rpssId] = {};
					memory['query-state'].domain[rpssId] = {};
					memory.dataset[rpssId] = {};

					memory.runtimesettings.configuration[rpssId] = {};
					memory.runtimesettings['query-url'][rpssId] = {};
					memory.runtimesettings['query-multidomain-urls'][rpssId] = {};
					memory.runtimesettings.configuration[rpssId] = {}
					memory.runtimesettings['js-settings'][rpssId] = {};
					
					memory.settings['js-settings'][rpssId] = {};
					memory.settings.jsmethods.pagesection[rpssId] = $.extend({}, localMemory.settings.jsmethods.pagesection[rpssId]);
					memory.settings.jsmethods.block[rpssId] = {};
					memory.settings['templates-cbs'][rpssId] = {};
					memory.settings['templates-paths'][rpssId] = {};
					memory.settings['db-keys'][rpssId] = {};
					memory.settings.configuration[rpssId] = {};
					memory.settings.configuration[rpssId][M.JS_MODULES] = {};

					// Configuration: first copy the modules, and then the 1st level configuration => pageSection configuration
					// This is a special case because the blocks are located under 'modules', so doing $.extend will override the existing modules in 'memory', however we want to keep them
					var rpsConfiguration = memory.settings.configuration[rpssId];
					var lpsConfiguration = localMemory.settings.configuration[rpssId];
					$.each(lpsConfiguration, function(key, value) {

						// Do not process the key modules, that will be done later
						if (key == M.JS_MODULES || key == 'modules') return;

						// If it is an array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
						// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
						t.copyToConfiguration(key, value, rpsConfiguration);
						// if ($.type(value) == 'array') {
						// 	rpsConfiguration[key] = {};
						// }
						// else if ($.type(value) == 'object') {
						// 	// If it is an object, extend it. If not, just assign the value
						// 	if (!rpsConfiguration[key]) {
						// 		rpsConfiguration[key] = {};
						// 	}
						// 	// Comment Leo 10/08/2017: this comment below actually doesn't work, so I had to remove the `t.mergingTemplatePromise` keeping a promise per domain...
						// 	// // Comment Leo 10/08/2017: IMPORTANT: Using deep copy just for the configuration (explanation below)
						// 	// $.extend(true, rpsConfiguration[key], value);
						// 	$.extend(rpsConfiguration[key], value);
						// }
						// else {
						// 	rpsConfiguration[key] = value;
						// }
					});
				}

				$.each(rpsParams, function(rbsId, rbParams) {

					// Comment Leo 10/08/2017: IMPORTANT: No need to use `deep` copy when doing $.extend() below, except for the `configuration`!!!
					// Because all properties will not be modified across domains, then copy by reference will work.
					// However, for the configuration, Handlebars will modify it per domain (setting the context), so they must be copied by copy, not by reference!
					// If the memory is empty (eg: first time that we are loading a different domain), then recreate it under the domain scope
					if ($.isEmptyObject(memory['query-state'].general[rpssId][rbsId])) {

						memory['query-state'].general[rpssId][rbsId] = $.extend({}, localMemory['query-state'].general[rpssId][rbsId]);
						memory['query-state'].domain[rpssId][rbsId] = $.extend({}, localMemory['query-state'].domain[rpssId][rbsId]);
						memory.dataset[rpssId][rbsId] = $.extend({}, localMemory.dataset[rpssId][rbsId]);

						memory.runtimesettings.configuration[rpssId][rbsId] = {};
						memory.runtimesettings['query-url'][rpssId][rbsId] = $.extend({}, localMemory.runtimesettings['query-url'][rpssId][rbsId]);
						memory.runtimesettings['query-multidomain-urls'][rpssId][rbsId] = $.extend({}, localMemory.runtimesettings['query-multidomain-urls'][rpssId][rbsId]);
						memory.runtimesettings.configuration[rpssId][rbsId] = $.extend({}, localMemory.runtimesettings.configuration[rpssId][rbsId]);
						memory.runtimesettings['js-settings'][rpssId][rbsId] = $.extend({}, localMemory.runtimesettings['js-settings'][rpssId][rbsId]);
						memory.settings['js-settings'][rpssId][rbsId] = $.extend({}, localMemory.settings['js-settings'][rpssId][rbsId]);
						memory.settings.jsmethods.block[rpssId][rbsId] = $.extend({}, localMemory.settings.jsmethods.block[rpssId][rbsId]);
						memory.settings['templates-cbs'][rpssId][rbsId] = $.extend({}, localMemory.settings['templates-cbs'][rpssId][rbsId]);
						memory.settings['templates-paths'][rpssId][rbsId] = $.extend({}, localMemory.settings['templates-paths'][rpssId][rbsId]);
						memory.settings['db-keys'][rpssId][rbsId] = $.extend({}, localMemory.settings['db-keys'][rpssId][rbsId]);
						
						// Comment Leo 10/08/2017: this comment below actually doesn't work, so I had to remove the `t.mergingTemplatePromise` keeping a promise per domain...
						// // Modules under the first level configuration
						// // Comment Leo 10/08/2017: IMPORTANT: Using deep copy just for the configuration, because
						// // it will be modified by Handlebars when printing the HTML (adding the variables to the context),
						// // so then all configurations from different domains must be copies and cannot reference to the same original configuration
						// // Otherwise, we can't print the HTML for different domains concurrently, as it is done now (check that `t.mergingTemplatePromise` keeps a promise per domain,
						// // so these can be printed concurrently)
						// memory.settings.configuration[rpssId][M.JS_MODULES][rbsId] = $.extend(true, {}, localMemory.settings.configuration[rpssId][M.JS_MODULES][rbsId]);
						memory.settings.configuration[rpssId][M.JS_MODULES][rbsId] = $.extend({}, localMemory.settings.configuration[rpssId][M.JS_MODULES][rbsId]);
					}
				});
			});
		}
	},

	executeFetchBlockSuccess : function(pageSection, block, /*params, */response, status, jqXHR) {

		var t = this;

		var blockQueryState = t.getBlockQueryState(pageSection, block);

		// And finally process the block
		var loadingUrl = blockQueryState.url[jqXHR.url];
		var action = blockQueryState.action[loadingUrl];
		var runtimeOptions = {url: blockQueryState['paramsscope-url'][loadingUrl]};
		var processOptions = {operation: blockQueryState.operation[loadingUrl], action: blockQueryState.action[loadingUrl], 'fetch-status': status};

		// Comment Leo 08/08/2017: we need to keep the domain in the params, instead of extracting it from `loadingUrl`,
		// because this URL will be different when using the ContentCDN, so the domain that comes back would
		// be different to the one we used to set the properties, before executing the block data fetch
		// // Check if the domain from which we fetched the info is different than the loaded URL
		// // If that's the case, then it's data aggregation from a different website, we initialize
		// // all the properties from that domain
		var domain = blockQueryState.domain[loadingUrl];
		// var domain = getDomain(loadingUrl);
		t.initMultiDomainMemory(domain, pageSection, block, response);

		var memory = t.getMemory(domain);
		
		// Restore initial runtimeConfiguration: eg, for TPP Debate website, upon loading a single post, 
		// it will trigger to load the "After reading this" Add OpinionatedVoted with the already-submitted opinionatedvote, when it comes back
		// it must make sure to draw the original configuration, that's why restoring it below. Otherwise,
		// if clicking quick in 2 posts before the loading is finished, the configuration gets overwritten and the 1st post
		// is contaminated with configuration from the 2nd post
		var restoreinitial_actions = [M.CBACTION_LOADCONTENT, M.CBACTION_REFETCH, M.CBACTION_RESET];
		var restoreinitial = restoreinitial_actions.indexOf(action) > -1;
		if (restoreinitial) {
			var initialMemory = t.getInitialBlockMemory(runtimeOptions.url);
			$.each(response['query-state'].general, function(rpssId, rpsParams) {	
				$.each(rpsParams, function(rbsId, rbParams) {
					// Use a direct reference instead of $.extend() because this one creates mess by messing up references when loading other
					// templates, since their initialBlockMemory will be cross-referencing and overriding each other
					memory.runtimesettings.configuration[rpssId][rbsId] = initialMemory.runtimesettings.configuration[rpssId][rbsId];
				});
			});
		}

		// Integrate topLevel
		t.integrateTopLevelFeedback(domain, response);

		// Integrate the response data into the templateSettings
		t.integrateDatabases(domain, response);

		// Integrate the block
		$.each(response['query-state'].general, function(rpssId, rpsParams) {

			var rPageSection = $('#'+memory.settings.configuration[rpssId].id);
			if (!memory.feedback.block[rpssId]) {
				memory.feedback.block[rpssId] = {};
			}
			$.extend(memory.feedback.block[rpssId], response.feedback.block[rpssId]);
			if (!memory.dataset[rpssId]) {
				memory.dataset[rpssId] = {};
			}
			$.extend(memory.dataset[rpssId], response.dataset[rpssId]);
			if (!memory['query-state'].general[rpssId]) {
				memory['query-state'].general[rpssId] = {};
			}
			if (!memory['query-state'].domain[rpssId]) {
				memory['query-state'].domain[rpssId] = {};
			}
				
			$.each(rpsParams, function(rbsId, rbParams) {

				try {
					// Integrate the params
					// If the user closed the tab that originated the ajax call before the response, then the params do not exist anymore
					// and getBlockParams will throw an exception. Then catch it, and do nothing else (no need anyway!)
					var rBlock = $('#'+t.getBlockId(rpssId, rbsId, runtimeOptions));

					// Set the domain from the loadingURL
					// rBlock.data('domain', domain);

					// Comment Leo 04/12/2015: IMPORTANT: This extend below must be done always, even if `processblock-ifhasdata` condition below applies
					// and so we skip the merging, however the params must be set for later use. Eg: TPP Debate Create OpinionatedVoted block
					// skip-params: used for the loadLatest, so that the response of the params is not integrated back, messing up the paged, limit, etc
					$.extend(t.getBlockQueryState(rPageSection, rBlock, runtimeOptions), rbParams);
					// Also extend the domain query-state
					$.extend(t.getBlockDomainQueryState(domain, rPageSection, rBlock, runtimeOptions), response['query-state'].domain[rpssId][rbsId]);

					// When loading content, we can say to re-draw the block if there is data to be drawn, and do nothing instead
					// This is needed for the "Add your Thought on TPP": if the user is not logged in, and writes a Thought, and then logs in,
					// then the Thought must not be overridden
					var process_actions = [M.CBACTION_LOADCONTENT, M.CBACTION_REFETCH];
					if (process_actions.indexOf(action) > -1) {

						var jsSettings = t.getJsSettings(domain, rPageSection, rBlock);
						if (jsSettings['processblock-ifhasdata'] && !response.dataset[rpssId][rbsId].length) {
							return;	
						}
					}

					// And finally process the block
					t.processBlock(domain, rPageSection, rBlock, processOptions);
				}
				catch(err) {
					// Do nothing
					console.error(err.message);
					// console.trace();
				}
			});
		});

		block.triggerHandler('fetched', [response, action, domain]);
	},

	triggerEvent : function(pageSection, block, event, args) {

		var t = this;

		// Trigger on this block
		block.triggerHandler(event, args);
	},

	integrateTopLevelFeedback : function(domain, response) {
	
		var t = this;
		var tlFeedback = t.getMemory(domain).feedback.toplevel;

		// Integrate the response into the topLevelFeedback
		// Iterate all fields from the response topLevel. If it's an object, extend it. if not, just copy the value
		// This is done so that previously sent values (eg: lang, sent only on loading_frame()) are not overridden.
		$.each(response.feedback.toplevel, function(key, value) {

			// If it is an empty array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
			// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
			if ($.type(value) == 'array' && value.length == 0) {
				// do Nothing
			}
			else if ($.type(value) == 'object') {

				// If it is an object, extend it. If not, just assign the value
				if (!tlFeedback[key]) {
					tlFeedback[key] = {};
				}
				$.extend(tlFeedback[key], value);
			}
			else {
				tlFeedback[key] = value;
			}
		});
	},

	clearUserDatabase : function(domain) {
	
		var t = this;
		// domain = domain || M.HOME_DOMAIN;

		// Executed when the logged-in user logs out
		t.state[domain].userdatabase = {};
		// t.userdatabase = {};
	},

	integrateDatabase : function(database, responsedb) {
	
		var t = this;

		// Integrate the response Database into the database
		$.each(responsedb, function(dbKey, dbItems) {

			// Initialize DB entry
			database[dbKey] = database[dbKey] || {};

			// When there are no elements in dbItems, the object will appear not as an object but as an array
			// In that case, it will be empty, so skip
			if ($.type(dbItems) == 'array') {
				return;
			}

			// Extend with new values
			$.each(dbItems, function(key, value) {

				if (!database[dbKey][key]) {
					database[dbKey][key] = {};
				}
				$.extend(database[dbKey][key], value);
			});
		});
	},

	integrateDatabases : function(domain, response) {
	
		var t = this;

		t.integrateDatabase(t.getDatabase(domain)/*t.database*/, response.database);
		t.integrateDatabase(t.getUserDatabase(domain)/*t.userdatabase*/, response.userdatabase);
	},

	getMergeTargetContainer : function(target) {
	
		var t = this;

		if (target.data('merge-container')) {
			return $(target.data('merge-container'));
		}

		return target;
	},

	getMergeTarget : function(target, templateName, options) {
	
		var t = this;
		options = options || {};

		var selector = '.pop-merge.' + templateName;

		// Allow to set the target in the options. Eg: used in the Link Fullview feed to change the src of each iframe when Google translating
		var mergeTarget = options['merge-target'] ? $(options['merge-target']) : target.find(selector).addBack(selector);

		return t.getMergeTargetContainer(mergeTarget);
	},

	generateUniqueId : function(domain) {

		var t = this;

		// Create a new uniqueId
		var unique = Date.now();

		// assign it to the toplevel feedback
		var tlFeedback = t.getTopLevelFeedback(domain);
		tlFeedback[M.UNIQUEID] = unique;

		return unique;
	},

	getUniqueId : function(domain) {

		var t = this;

		var tlFeedback = t.getTopLevelFeedback(domain);
		return tlFeedback[M.UNIQUEID];
	},

	addUniqueId : function(url) {

		var t = this;

		var domain = getDomain(url);
		var unique = t.getUniqueId(domain);
		return url+'#'+unique;
	},

	mergeTargetTemplate : function(domain, pageSection, target, templateName, options) {
	
		var t = this;
		options = options || {};

		var rerender_actions = [M.CBACTION_LOADCONTENT, M.CBACTION_REFETCH, M.CBACTION_RESET];
		var rerender = rerender_actions.indexOf(options.action) > -1;
		if (rerender) {
			// When rerendering, create the unique-id again, since not all the components allow to re-create a new component with an already-utilized id (eg: editor.js)
			t.generateUniqueId(domain);
		}
		
		var html = t.getTemplateHtml(domain, pageSection, target, templateName, options);
		var targetContainer = t.getMergeTarget(target, templateName, options);

		// Default operation: REPLACE, unless it is multidomain and processing a 2nd domain in that block, in which data could be replacing the just added data from other domains
		// Eg: messagefeedback when there are no results from the first domain, may be overriten by a second domain
		var fetchStatus = options['fetch-status'] || {isFirst: true, isLast: true};
		var operation = options.operation || (fetchStatus.isFirst ? M.URLPARAM_OPERATION_REPLACE : M.URLPARAM_OPERATION_APPEND);

		// Delete all children before appending?
		if (operation == M.URLPARAM_OPERATION_REPLACE) {

			// Allow others to do something before this is all gone (eg: destroy LocationsMap so it can be regenerated using same id)
			target.triggerHandler('replace', [options.action]);
			
			// Needed because of template GD_TEMPLATESOURCE_FORM_INNER function get_template_cb_actions (processors/system/structures-inner.php)
			// When any of these actions gets executed, the form will actually be re-drawn (ie not just data coming from the server, but all the components inside the form will be rendered again)
			// This is needed when intercepting Edit Project and then, on the fly, loading that Project data to edit. This is currently not implemented
			if (rerender) {
			
				target.triggerHandler('rerender', [options.action]);
			}

			targetContainer.empty();
		}
		var merged = t.mergeHtml(html, targetContainer, operation);

		// Call the callback javascript functions under the templateBlock (only aggregator one for PoPs)
		target.triggerHandler('merged', [merged.newDOMs]);

		return merged;
	},

	mergeHtml : function(html, container, operation) {

		var t = this;

		// We can create the element first and then move it to the final destination, and it will be the same:
		// From https://api.jquery.com/append/:
		// "If an element selected this way is inserted into a single location elsewhere in the DOM, it will be moved into the target (not cloned)"
		var newDOMs = $(html);
		if (operation == M.URLPARAM_OPERATION_PREPEND) {
			container.prepend(newDOMs);
		}
		else if (operation == M.URLPARAM_OPERATION_REPLACEINLINE) {
			container.replaceWith(newDOMs);
			container = newDOMs;
		}
		else {
			container.append(newDOMs);
		}

		t.triggerHTMLMerged();

		return {targetContainer: container, newDOMs: newDOMs};
	},

	triggerHTMLMerged : function() {

		var t = this;

		// Needed for the calendar to know when the element is finally inserted into the DOM, to be able to operate with it
		$(document).triggerHandler('template:merged');
	},

	renderPageSection : function(domain, pageSection, options) {
	
		var t = this;
		options = options || {};

		$.extend(options, t.getFetchPageSectionSettings(pageSection));

		// If doing server-side rendering, then no need to render the view using javascript templates,
		// however we must still identify the newDOMs as to execute the JS on the elements
		var newDOMs;
		if (options['serverside-rendering']) {

			// Trigger 'template:merged' for the Events Map to add the markers
			// It must come before the next line, which will execute the JS on all elements
			// (Eg: then layout-initjs-delay.tmpl works fine)
			t.triggerHTMLMerged();

			newDOMs = t.getPageSectionDOMs(domain, pageSection);
		}
		else {
			newDOMs = t.renderTarget(domain, /*domain, */pageSection, pageSection, options);
		}

		// Sometimes no newDOMs are actually produced. Eg: when calling /userloggedin-data
		// So then do not call pageSectionRendered, or it can make mess (eg: it scrolls up when /userloggedin-data comes back)
		if (newDOMs.length) {

			t.pageSectionRendered(domain, pageSection, newDOMs, options);
		}
	},

	getPageSectionDOMs : function(domain, pageSection) {
	
		var t = this;

		var templates_cbs = t.getTemplatesCbs(domain, pageSection, pageSection);
		var targetContainers = $();
		var newDOMs = $();
		$.each(templates_cbs, function(index, templateName) {

			// The DOMs are the existing elements on the pageSection merge target container
			var targetContainer = t.getMergeTarget(pageSection, templateName);
			targetContainers.add(targetContainer);
			newDOMs = newDOMs.add(targetContainer.children());
		});

		t.triggerRendered(domain, pageSection, newDOMs, targetContainers);

		return newDOMs;
	},

	renderTarget : function(domain, pageSection, target, options) {
	
		var t = this;

		options = options || {};

		// And having set-up all the handlers, we can trigger the handler
		target.triggerHandler('beforeMerge', [options]);

		var templates_cbs = t.getTemplatesCbs(domain, pageSection, target, options.action);
		var targetContainers = $();
		var newDOMs = $();
		$.each(templates_cbs, function(index, templateName) {

			var merged = t.mergeTargetTemplate(domain, pageSection, target, templateName, options);
			targetContainers = targetContainers.add(merged.targetContainer);
			newDOMs = newDOMs.add(merged.newDOMs);
		});

		t.triggerRendered(domain, target, newDOMs, targetContainers);

		return newDOMs;
	},

	triggerRendered : function(domain, target, newDOMs, targetContainers) {
	
		var t = this;

		target.triggerHandler('rendered', [newDOMs, targetContainers, domain]);
		$(document).triggerHandler('rendered', [target, newDOMs, targetContainers, domain]);
	},

	getPageSectionConfiguration : function(domain, pageSection) {
	
		var t = this;
		
		var pssId = t.getSettingsId(pageSection);
		return t.getMemory(domain).settings.configuration[pssId];
	},

	getTargetConfiguration : function(domain, pageSection, target, template) {
	
		var t = this;
		var templatePath = t.getTemplatePath(domain, pageSection, target, template);
		var targetConfiguration = t.getPageSectionConfiguration(domain, pageSection);
		
		// Go down all levels of the configuration, until finding the level for the template-cb
		if (templatePath) {
			$.each(templatePath, function(index, pathLevel) {

				targetConfiguration = targetConfiguration[pathLevel];
			});
		}

		// We reached the target configuration. Now override with the new values
		return targetConfiguration;
	},

	overrideFromItemObject : function(itemObject, override, overrideFields) {
	
		var t = this;

		// Item Object / Single Item Object (eg: as loaded in Edit Project page)
		// If the block is lazy, then there won't be a singleItemObject, then do nothing
		if (itemObject) {
		
			$.each(overrideFields, function(index, overrideFromItemObject) {	

				// Generate object to override
				override[overrideFromItemObject.key] = itemObject[overrideFromItemObject.field];
			});
		}
	},

	replaceFromItemObject : function(domain, pssId, bsId, template, itemObject, override, strReplace) {
	
		var t = this;
		var feedback = t.getTopLevelFeedback(domain);
		var targetConfiguration = t.getTargetConfiguration(domain, pssId, bsId, template);
		$.each(strReplace, function(index, replace) {	

			var replaceWhereField = replace['replace-where-field'];
			var replaceWherePath = replace['replace-where-path'];
			var replaceFromField = replace['replace-from-field'] || replaceWhereField;
			var replacements = replace['replacements'];
			var replaceFrom = targetConfiguration[replaceFromField];
			$.each(replacements, function(index, replacement) {

				var replaceStr = replacement['replace-str'];

				// Item Object / Single Item Object (eg: as loaded in Edit Project page)
				// If the block is lazy, then there won't be a singleItemObject, then do nothing
				if (itemObject) {				
					var replaceWithField = replacement['replace-with-field'];
					if (replaceWithField && replaceStr != itemObject[replaceWithField]) {
						
						// Comment Leo: IMPORTANT: we can't use RegExp here. When using it, it fails replacing variables of the type %1$s
						// as declared for the SocialMedia Items (eg: replace %1$s in https://twitter.com/intent/tweet?original_referer=%1$s&url=%1$s&text=%2$s)
						// Comment Leo 01/04/2015: overridden again, because we changed the %1$s from the socialmedia-items into something else (the global is needed! because for Twitter the url field must be replaced twice)
						var replaceWith = itemObject[replaceWithField];
						if (replacement['encode-uri-component']) {
							replaceWith = encodeURIComponent(replaceWith);
						}
						else if (replacement['encode-uri']) {
							replaceWith = encodeURI(replaceWith);
						}
						replaceFrom = replaceFrom.replace(new RegExp(replaceStr, 'g'), replaceWith);
					}
				}

				var replaceFromFeedback = replacement['replace-from-feedback'];
				if (replaceFromFeedback && replaceStr != feedback[replaceFromFeedback]) {
					replaceFrom = replaceFrom.replace(new RegExp(replaceStr, 'g'), feedback[replaceFromFeedback]);
				}
			});

			var overrideWhere = override;
			if (replaceWherePath) {
				$.each(replaceWherePath, function(index, pathlevel) {
					overrideWhere[pathlevel] = {};
					overrideWhere = overrideWhere[pathlevel];
				});
			}

			overrideWhere[replaceWhereField] = replaceFrom;
		});
	},

	getTemplateHtml : function(domain, pageSection, target, templateName, options, itemDBKey, itemObjectId) {

		var t = this;
		var targetConfiguration = t.getTargetConfiguration(domain, pageSection, target, templateName);
		options = options || {};

		targetContext = targetConfiguration;

		// If merging a subcomponent (eg: appending data to Carousel), then we need to recreate the block Context
		var templatePath = t.getTemplatePath(domain, pageSection, target, templateName);
		if (templatePath.length) {
			var block = t.getBlock(target);
			t.initContextSettings(domain, pageSection, block, targetContext);
			t.extendContext(targetContext, domain, itemDBKey, itemObjectId);
		}

		// extendContext: don't keep the overriding in the configuration. This way, we can use the replicate without having to reset
		// the configurations to an original value (eg: for featuredImage, it copies the img on the settings)
		var extendContext = options.extendContext;
		if (extendContext) {
			targetContext = $.extend({}, targetContext, extendContext);
		}
		return t.getHtml(/*domain, */templateName, targetContext);
	},

	extendContext : function(context, domain, itemDBKey, itemObjectId, override) {

		// If merging a subcomponent (eg: appending data to Carousel), then we need to recreate the block Context
		// Also used from within function enterModules to create the context to pass to each module
		var t = this;
		override = override || {};
		$.extend(context, override);

		// Load itemObject?
		if (itemDBKey) {

			$.extend(context, {itemDBKey: itemDBKey});
			if (itemObjectId) {

				var itemObject = t.getItemObject(domain, itemDBKey, itemObjectId);
				$.extend(context, {itemObject: itemObject, itemObjectDBKey: itemDBKey});
			}
		}
	},

	initTopLevelJson : function(domain) {
	
		var t = this;

		// Initialize Settings
		var jsonHtml = $('#'+popPageSectionManager.getTopLevelSettingsId());
		var json = $.parseJSON(jsonHtml.html());

		// The template sources are located under sitemapping
		// t.sitemapping['template-sources'] = json.sitemapping['template-sources'];
		t.sitemapping = json.sitemapping;

		// t.database = json.database;
		// t.userdatabase = json.userdatabase;
		t.state[domain].database = json.database;
		t.state[domain].userdatabase = json.userdatabase;

		var memory = t.getMemory(domain);
		memory.settings = json.settings;
		memory.runtimesettings = json.runtimesettings;
		memory.dataset = json.dataset;
		memory.feedback = json.feedback;
		memory['query-state'] = json['query-state'];

		// Dataset, feedback and Params: the PHP will write empty objects as [] instead of {}, so they will be treated as arrays
		// This will create problems when doing $.extend(), so convert them back to objects
		// ---------------------------------------
		$.each(memory.dataset, function(pssId, psDataset) {
			if ($.type(psDataset) == 'array') {
				memory.dataset[pssId] = {};
			}
		});
		$.each(memory['query-state'].general, function(pssId, psParams) {
			if ($.type(psParams) == 'array') {
				memory['query-state'].general[pssId] = {};
			}
		});
		$.each(memory['query-state'].domain, function(pssId, psParams) {
			if ($.type(psParams) == 'array') {
				memory['query-state'].domain[pssId] = {};
			}
		});
		$.each(memory.feedback.pagesection, function(pssId, psFeedback) {
			if ($.type(psFeedback) == 'array') {
				memory.feedback.pagesection[pssId] = {};
			}
		});
		$.each(memory.feedback.block, function(pssId, bFeedback) {
			if ($.type(bFeedback) == 'array') {
				memory.feedback.block[pssId] = {};
			}
		});
		// ---------------------------------------

		t.saveUrlPointers(json);
		t.addReplicableMemory(json);
		t.addInitialBlockMemory(json);
	},

	getBlockDefaultParams : function() {
	
		var t = this;

		return {
			url: [],
			loading: [],
			reload: [],
			operation: {},
			action: {},
			domain: {},
			timestamps: {},
			'paramsscope-url': {},
			'post-data': '',
			// Filter params are actually initialized in setFilterParams function. 
			// That is because a filter knows its templateBlock, but a templateBlock not its filter
			// (eg: in the sidebar)
			filter: ''
		};
	},
	getBlockDefaultMultiDomainParams : function() {
	
		var t = this;
		return {};
	},
	getPageSectionDefaultParams : function() {
	
		var t = this;

		return {
			url: {},
			target: {},
			'fetch-url': {},
			loading: []
		};
	},
	
	initPageSectionRuntimeMemory : function(domain, pageSection, options) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var runtimeMempage = t.newRuntimeMemoryPage(domain, pageSection, pageSection, options);

		var pssId = t.getSettingsId(pageSection);
		runtimeMempage['query-state'] = t.getPageSectionDefaultParams();

		// Allow JS libraries to hook up and initialize their own params
		var args = {
			domain: domain,
			pageSection: pageSection,
			runtimeMempage: runtimeMempage
		};
		popJSLibraryManager.execute('initPageSectionRuntimeMemory', args);
	},
	initBlockRuntimeMemory : function(domain, pageSection, block, options) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var runtimeMempage = t.newRuntimeMemoryPage(domain, pageSection, block, options);

		var memory = t.getMemory(domain);
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		
		runtimeMempage.id = block.attr('id');
		runtimeMempage['query-url'] = t.getRuntimeSettings(domain, pageSection, block, 'query-url');
		runtimeMempage['query-multidomain-urls'] = {};

		// Params: it is slit into 2: #1. block params, and #2. dataloader-source-params (multidomain-params)
		// #1 contains params that are unique to the block, which are equally posted to all sources, eg: filter
		// #2 contains params whose value can change from source to source, eg: stop-loading
		runtimeMempage['query-state'] = {
			general: $.extend(t.getBlockDefaultParams(), memory['query-state'].general[pssId][bsId]),
			domain: {},
		};
		var multidomain_urls = t.getRuntimeSettings(domain, pageSection, block, 'query-multidomain-urls');
		$.each(multidomain_urls, function(index, query_url) {

			query_url_domain = getDomain(query_url);
			runtimeMempage['query-multidomain-urls'][query_url_domain] = query_url;
			runtimeMempage['query-state'].domain[query_url_domain] = $.extend(t.getBlockDefaultMultiDomainParams(), memory['query-state'].domain[pssId][bsId]);
		})

		// Allow JS libraries to hook up and initialize their own params
		var args = {
			domain: domain,
			pageSection: pageSection,
			block: block,
			runtimeMempage: runtimeMempage
		};
		popJSLibraryManager.execute('initBlockRuntimeMemory', args);
	},

	getViewport : function(pageSection, el) {
	
		var t = this;
		var viewport = el.closest('.pop-viewport');
		if (viewport.length) {
			return viewport;
		}

		// Default: the pageSection
		return pageSection;
	},

	getDOMContainer : function(pageSection, el) {
	
		var t = this;
		if (M.DOMCONTAINER_ID) {
			return $('#'+M.DOMCONTAINER_ID);
		}

		// Default: the viewport
		return t.getViewport(pageSection, el);
	},

	getBlock : function(el) {
	
		var t = this;
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

	getTargetParamsScopeURL : function(/*pageSection, */target) {

		var t = this;
		return target.data(M.PARAMS_PARAMSSCOPE_URL);
	},
	getBlockTopLevelURL : function(/*pageSection, */block) {

		var t = this;
		return block.data(M.PARAMS_TOPLEVEL_URL);
	},
	getBlockTopLevelDomain : function(/*pageSection, */block) {

		var t = this;
		return block.data(M.PARAMS_TOPLEVEL_DOMAIN);
	},

	isBlockGroup : function(block) {
	
		var t = this;		
		return block.hasClass('pop-blockgroup');
	},

	getBlockGroupActiveBlock : function(blockGroup) {
	
		var t = this;
		
		// Supposedly only 1 active pop-block can be inside a group of BlockGroups, so this should either
		// return 1 result or none
		return blockGroup.find('.tab-pane.active .pop-block, .collapse.in .pop-block');
	},

	getBlockGroupBlocks : function(blockGroup) {
	
		var t = this;
		return blockGroup.find('.pop-block');
	},

	// getTemplateSource : function(domain, template) {
	
	// 	// If empty, then the template is its own source
	// 	var t = this;		
	// 	return t.getMemory(domain).settings['template-sources'][template] || template;
	// },
	getTemplateSource : function(template) {
	
		// If empty, then the template is its own source
		var t = this;		
		return t.sitemapping['template-sources'][template] || template;
	},

	initPageSectionSettings : function(domain, pageSection, psConfiguration) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var tls = t.getTopLevelSettings(domain);
		$.extend(psConfiguration, {tls: tls});

		var pss = t.getPageSectionSettings(domain, pageSection);
		var pssId = t.getSettingsId(pageSection);
		var psId = psConfiguration[M.JS_FRONTENDID];//psConfiguration['frontend-id'];
		$.extend(psConfiguration, {pss: pss});	

		// Expand the JS Keys for the configuration
		t.expandJSKeys(psConfiguration);

		// Fill each block configuration with its pssId/bsId/settings
		if (psConfiguration[M.JS_MODULES]/*psConfiguration.modules*/) {
			$.each(psConfiguration[M.JS_MODULES]/*psConfiguration.modules*/, function(bsId, bConfiguration) {
				
				var bId = bConfiguration[M.JS_FRONTENDID];//bConfiguration['frontend-id'];
				// The blockTopLevelDomain is the same as teh domain when initializing the pageSection
				var bs = t.getBlockSettings(domain, domain, pssId, bsId, psId, bId);
				$.extend(bConfiguration, {tls: tls, pss: pss, bs: bs});	

				// Expand the JS Keys for the configuration
				t.expandJSKeys(bConfiguration);
			});	
		}
	},

	initContextSettings : function(domain, pageSection, block, context) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var tls = t.getTopLevelSettings(domain);
		$.extend(context, {tls: tls});

		var pss = t.getPageSectionSettings(domain, pageSection);
		$.extend(context, {pss: pss});	

		var pssId = t.getSettingsId(pageSection);
		var psId = pageSection.attr('id');
		var bsId = t.getSettingsId(block);
		var bId = block.attr('id');
		var bs = t.getBlockSettings(domain, t.getBlockTopLevelDomain(block), pssId, bsId, psId, bId);
		$.extend(context, {pss: pss, bs: bs});	

		// Expand the JS Keys for the configuration
		t.expandJSKeys(context);

		// If there's no itemDBKey, also add it
		// This is because there is a bug: first loading /log-in/, it will generate the settings adding itemDBKey when rendering
		// the template down the path. However, it then calls /loaders/initial-frames?target=main, and it will bring 
		// again the /log-in replicable settings, which will override the ones from the log-in window that is open, making it lose the itemDBKey,
		// which is needed by the content-inner template.
		if (!context.itemDBKey) {
			context.itemDBKey = bs['db-keys']['db-key'];
		}
	},

	copyToConfiguration : function(key, value, configuration) {

		var t = this;

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
			$.extend(configuration[key], value);
		}
		else {
			configuration[key] = value;
		}
	},

	integratePageSection : function(domain, response) {
	
		var t = this;

		$.extend(t.sitemapping['template-sources'], response.sitemapping['template-sources']);
		
		var memory = t.getMemory(domain);
		// $.extend(memory.settings['template-sources'], response.settings['template-sources']);
		$.each(response.settings.configuration, function(pssId, rpsConfiguration) {

			// Initialize all the pageSection keys if needed 
			// (this is only needed since adding support for multicomponent, since their response may involve a pageSection which had never been initialized)
			memory['query-state'].general[pssId] = memory['query-state'].general[pssId] || {};
			memory['query-state'].domain[pssId] = memory['query-state'].domain[pssId] || {};
			memory.dataset[pssId] = memory.dataset[pssId] || {};
			memory.feedback.pagesection[pssId] = memory.feedback.pagesection[pssId] || {};
			memory.feedback.block[pssId] = memory.feedback.block[pssId] || {};
			memory.runtimesettings['query-url'][pssId] = memory.runtimesettings['query-url'][pssId] || {};
			memory.runtimesettings['query-multidomain-urls'][pssId] = memory.runtimesettings['query-multidomain-urls'][pssId] || {};
			memory.runtimesettings.configuration[pssId] = memory.runtimesettings.configuration[pssId] || {};
			memory.runtimesettings['js-settings'][pssId] = memory.runtimesettings['js-settings'][pssId] || {};
			memory.settings['js-settings'][pssId] = memory.settings['js-settings'][pssId] || {};
			memory.settings.jsmethods.pagesection[pssId] = memory.settings.jsmethods.pagesection[pssId] || {};
			memory.settings.jsmethods.block[pssId] = memory.settings.jsmethods.block[pssId] || {};
			memory.settings['templates-cbs'][pssId] = memory.settings['templates-cbs'][pssId] || {};
			memory.settings['templates-paths'][pssId] = memory.settings['templates-paths'][pssId] || {};
			memory.settings['db-keys'][pssId] = memory.settings['db-keys'][pssId] || {};
			memory.settings.configuration[pssId] = memory.settings.configuration[pssId] || {};

			$.extend(memory['query-state'].general[pssId], response['query-state'].general[pssId]);
			$.extend(memory['query-state'].domain[pssId], response['query-state'].domain[pssId]);
			$.extend(memory.dataset[pssId], response.dataset[pssId]);
			$.extend(memory.feedback.pagesection[pssId], response.feedback.pagesection[pssId]);
			$.extend(memory.feedback.block[pssId], response.feedback.block[pssId]);

			$.extend(memory.runtimesettings['query-url'][pssId], response.runtimesettings['query-url'][pssId]);
			$.extend(memory.runtimesettings['query-multidomain-urls'][pssId], response.runtimesettings['query-multidomain-urls'][pssId]);
			$.extend(memory.runtimesettings.configuration[pssId], response.runtimesettings.configuration[pssId]);
			$.extend(memory.runtimesettings['js-settings'][pssId], response.runtimesettings['js-settings'][pssId]);
			$.extend(memory.settings['js-settings'][pssId], response.settings['js-settings'][pssId]);
			$.extend(memory.settings.jsmethods.pagesection[pssId], response.settings.jsmethods.pagesection[pssId]);
			$.extend(memory.settings.jsmethods.block[pssId], response.settings.jsmethods.block[pssId]);
			$.extend(memory.settings['templates-cbs'][pssId], response.settings['templates-cbs'][pssId]);
			$.extend(memory.settings['templates-paths'][pssId], response.settings['templates-paths'][pssId]);
			$.extend(memory.settings['db-keys'][pssId], response.settings['db-keys'][pssId]);

			// Configuration: first copy the modules, and then the 1st level configuration => pageSection configuration
			// This is a special case because the blocks are located under 'modules', so doing $.extend will override the existing modules in 'memory', however we want to keep them
			var psConfiguration = memory.settings.configuration[pssId];
			$.each(rpsConfiguration, function(key, value) {

				// If it is an array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
				// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
				t.copyToConfiguration(key, value, psConfiguration);
				// if ($.type(value) == 'array') {
				// 	psConfiguration[key] = {};
				// }
				// else if ($.type(value) == 'object') {
				// 	// If it is an object, extend it. If not, just assign the value
				// 	if (!psConfiguration[key]) {
				// 		psConfiguration[key] = {};
				// 	}
				// 	$.extend(psConfiguration[key], value);
				// }
				// else {
				// 	psConfiguration[key] = value;
				// }
			});

			var psId = rpsConfiguration[M.JS_FRONTENDID];//rpsConfiguration['frontend-id'];
			var pageSection = $('#'+psId);
			t.initPageSectionSettings(domain, pageSection, psConfiguration);
		});
	},

	getTopLevelSettings : function(domain) {
	
		var t = this;

		return {
			domain: domain,
			'domain-id': M.MULTIDOMAIN_WEBSITES[domain] ? M.MULTIDOMAIN_WEBSITES[domain].id : getDomainId(domain),
			feedback: t.getTopLevelFeedback(domain),
		};
	},

	getPageSectionSettings : function(domain, pageSection) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var psId = pageSection.attr('id');

		var pageSectionSettings = {
			feedback: t.getPageSectionFeedback(domain, pageSection),
			pssId: pssId,
			psId: psId
		};

		return pageSectionSettings;
	},

	isMultiDomain : function(blockTLDomain, pssId, bsId) {
	
		var t = this;
		// Comments Leo 27/07/2017: the query-multidomain-urls are stored under the domain from which the block was initially rendered,
		// and not that from where the data is being rendered
		var multidomain_urls = t.getRuntimeSettings(blockTLDomain, pssId, bsId, 'query-multidomain-urls');
		return (multidomain_urls && multidomain_urls.length >= 2);
	},

	getBlockSettings : function(domain, blockTLDomain, pssId, bsId, psId, bId) {
	
		var t = this;
		var blockSettings = {
			"db-keys": t.getDatabaseKeys(domain, pssId, bsId),
			dataset: t.getBlockDataset(domain, pssId, bsId),
			feedback: t.getBlockFeedback(domain, pssId, bsId),
			bsId: bsId,
			bId: bId,
			'is-multidomain': t.isMultiDomain(blockTLDomain, pssId, bsId)
		};

		t.expandBlockSettingsJSKeys(blockSettings);

		return blockSettings;
	},

	expandBlockSettingsJSKeys : function(blockSettings) {
	
		var t = this;

		if (blockSettings && M.COMPACT_JS_KEYS) {
			
			if (blockSettings['db-keys'] && blockSettings['db-keys'][M.JS_SUBCOMPONENTS]) {
				blockSettings['db-keys'].subcomponents = blockSettings['db-keys'][M.JS_SUBCOMPONENTS];
			}
		}
	},

	getBlockDataset : function(domain, pageSection, block) {
	
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		
		return t.getMemory(domain).dataset[pssId][bsId];
	},

	getBlockFeedback : function(domain, pageSection, block) {
	
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		
		return t.getMemory(domain).feedback.block[pssId][bsId];
	},

	getPageSectionFeedback : function(domain, pageSection) {
	
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		return t.getMemory(domain).feedback.pagesection[pssId];
	},

	getTopLevelFeedback : function(domain) {
	
		var t = this;
		
		return t.getMemory(domain).feedback.toplevel;
	},

	getSettings : function(domain, pageSection, target, item) {
	
		var t = this;
		
		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(target);
		var memory = t.getMemory(domain);

		return memory.settings[item][pssId][targetId];
	},
	getRuntimeSettings : function(domain, pageSection, target, item) {
	
		var t = this;
		
		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(target);
		var memory = t.getMemory(domain);
		
		return memory.runtimesettings[item][pssId][targetId];
	},

	getQueryUrl : function(pageSection, block) {
	
		var t = this;
		return t.getRuntimeMemoryPage(pageSection, block)['query-url'];
	},

	getQueryMultiDomainUrls : function(pageSection, block) {
	
		var t = this;
		return t.getRuntimeMemoryPage(pageSection, block)['query-multidomain-urls'];
	},

	getRuntimeConfiguration : function(domain, pageSection, block, el) {

		var t = this;

		// When getting the block configuration, there's no need to pass el param
		el = el || block;
		
		var elsId = t.getTemplateOrObjectSettingsId(el);
		var configuration = t.getRuntimeSettings(domain, pageSection, block, 'configuration');

		return configuration[elsId] || {};
	},

	getDatabaseKeys : function(domain, pageSection, block) {
	
		var t = this;
		return t.getSettings(domain, pageSection, block, 'db-keys');
	},

	getBlockFilteringUrl : function(domain, pageSection, block, use_pageurl) {
	
		var t = this;
		var url = t.getQueryUrl(pageSection, block);

		// If the block doesn't have a filtering url (eg: the Author Description, https://www.mesym.com/p/leo/?tab=description) then use the current browser url
		if (!url && use_pageurl) {
			// url = window.location.href;
			url = t.getTopLevelFeedback(domain/*popManager.getBlockTopLevelDomain(block)*/)[M.URLPARAM_URL];
		}

		// Add only the 'visible' params to the URL
		var post_data = t.getBlockPostData(domain, pageSection, block, {paramsGroup: 'visible'});
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
	
		var t = this;
		target = target || '';
		container = container || $(document.body);
		
		// Create a new '<a' element with the url as href, and "click" it
		// We do this instead of popManager.fetchMainPageSection(datum.url); so that it can be intercepted
		// So this is needed for the Top Quicklinks/Search
		var linkHtml = '<a href="'+url+'" target="'+target+'" class="hidden"></a>';
		var link = $(linkHtml).appendTo(container);
		link.trigger('click');
	},

	getUnembedUrl : function(url) {

		var t = this;

		// Allow to have custom-functions.js provide the implementation of this function
		var executed = popJSLibraryManager.execute('getUnembedUrl', {url: url});
		var ret = false;
		$.each(executed, function(index, value) {
			if (value) {
				url = value;
				return -1;
			}
		});
		
		return url;
	},

	getEmbedUrl : function(url) {
	
		var t = this;

		// Allow to have custom-functions.js provide the implementation of this function
		var executed = popJSLibraryManager.execute('getEmbedUrl', {url: url});
		var ret = false;
		$.each(executed, function(index, value) {
			if (value) {
				url = value;
				return -1;
			}
		});
		
		return url;
	},

	getAPIUrl : function(url) {
	
		var t = this;

		// Add the corresponding parameters, like this:
		// $url?output=json&module=data&mangled=false
		// Add mangled=false so that the developers get a consistent name, which will not change with software updates,
		// and also so that they can understand what data it is
		$.each(M.API_URLPARAMS, function(param, value) {
			url = add_query_arg(param, value, url);
		});

		return url;
	},

	getPrintUrl : function(url) {
	
		var t = this;
		
		// Allow to have custom-functions.js provide the implementation of this function
		var executed = popJSLibraryManager.execute('getPrintUrl', {url: url});
		var ret = false;
		$.each(executed, function(index, value) {
			if (value) {
				url = value;
				return -1;
			}
		});
		
		return url;
	},

	getDestroyUrl : function(url) {
	
		var t = this;

		// // Comment Leo 10/06/2016: The URL can start with other domains, for the Platform of Platforms
		// var domain = M.HOME_DOMAIN;
		// $.each(M.ALLOWED_DOMAINS, function(index, allowed) {

		// 	if(url.startsWith(allowed)) {

		// 		domain = allowed;
		// 		return -1;
		// 	}
		// });
		
		// // Comment Leo 28/10/2015: Use this URL instead of !destroy because
		// // this bit gets stripped off when doing removeParams(url) to get the interceptors, however it is still needed
		// return url.replace(domain, domain+'/destroy');

		// Comment Leo 10/06/2016: The URL can start with other domains, for the Platform of Platforms
		var domain = getDomain(url);
		// return url.replace(domain, domain+'/destroy');
		return domain+'/destroy'+url.substr(domain.length);
	},
	
	getTemplateOrObjectSettingsId : function(el) {

		var t = this;
		
		// If it's an object, return an attribute	
		if ($.type(el) == 'object') {

			return el.data('templateid');
		}

		// String was passed, return it
		return el;
	},

	getPageSectionJsSettings : function(domain, pageSection) {
	
		// This is a special case
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		
		return t.getSettings(domain, pageSection, pageSection, 'js-settings') || {};
	},
	getJsSettings : function(domain, pageSection, block, el) {

		var t = this;

		// When getting the block settings, there's no need to pass el param
		el = el || block;
		
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var jsSettingsId = t.getTemplateOrObjectSettingsId(el);

		// Combine the JS settings and the runtime JS settings together
		// var domain = popManager.getBlockTopLevelDomain(block);
		var settings = t.getSettings(domain, pageSection, block, 'js-settings');
		var runtimeSettings = t.getRuntimeSettings(domain, pageSection, block, 'js-settings');

		var jsSettings = {};
		if (settings[jsSettingsId]) {
			$.extend(jsSettings, settings[jsSettingsId]);
		}
		if (runtimeSettings[jsSettingsId]) {
			// Make it deep, because in the typeahead, the thumbprint info is saved under ['dataset']['thumbprint'], so key 'dataset' must not be overriden
			$.extend(true, jsSettings, runtimeSettings[jsSettingsId]);
		}
		return jsSettings;
	},
	getPageSectionJsMethods : function(domain, pageSection) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var memory = t.getMemory(domain);
		return memory.settings['jsmethods']['pagesection'][pssId] || {};
	},
	getBlockJsMethods : function(domain, pageSection, block) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var memory = t.getMemory(domain);

		return memory.settings['jsmethods']['block'][pssId][bsId] || {};
	},
	restoreInitialBlockMemory : function(pageSection, block, options) {

		var t = this;
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var url = t.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/;
		var initialMemory = t.getInitialBlockMemory(url);
		// var domain = getDomain(url);

		var queryState = t.getRuntimeMemoryPage(pageSection, block, options)['query-state'];
		queryState.general = $.extend(t.getBlockDefaultParams(), initialMemory['query-state'].general[pssId][bsId]);

		var dataset = initialMemory.dataset[pssId][bsId];
		var query_urls = t.getQueryMultiDomainUrls(pageSection, block);
		$.each(query_urls, function(domain, query_url) {
			
			queryState.domain[domain] = $.extend(t.getBlockDefaultMultiDomainParams(), initialMemory['query-state'].domain[pssId][bsId]);
			
			var memory = t.getMemory(domain);
			memory.runtimesettings.configuration[pssId][bsId] = $.extend({}, initialMemory.runtimesettings.configuration[pssId][bsId]);
			memory.feedback.block[pssId][bsId] = $.extend({}, initialMemory.feedback.block[pssId][bsId]);

			// If the initialMemory dataset is empty and the memory one is not, then the extend fails to override
			// So ask for that case explicitly
			if (dataset.length) {
				$.extend(memory.dataset[pssId][bsId], dataset);
			}
			else {
				memory.dataset[pssId][bsId] = [];
			}
		});
	},
	
	getBlockQueryState : function(pageSection, block, options) {
	
		var t = this;

		return t.getRuntimeMemoryPage(pageSection, block, options)['query-state'].general;
	},
	getBlockMultiDomainQueryState : function(pageSection, block, options) {
	
		var t = this;

		return t.getRuntimeMemoryPage(pageSection, block, options)['query-state'].domain;
	},
	getBlockDomainQueryState : function(domain, pageSection, block, options) {
	
		var t = this;

		return t.getBlockMultiDomainQueryState(pageSection, block, options)[domain] || {};
	},
	getPageSectionParams : function(pageSection, options) {
	
		var t = this;

		return t.getRuntimeMemoryPage(pageSection, pageSection, options)['query-state'];
	},
	
	getTarget : function(pageSection) {
	
		var t = this;
		var id = pageSection.attr('id');

		// Default case: if the target doesn't exist, use the main target
		var ret = M.URLPARAM_TARGET_MAIN;
		$.each(M.FETCHTARGET_SETTINGS, function(target, psId) {

			if (psId == id) {
				ret = target;
				return -1;
			}
		});

		return ret;
	},
	targetExists : function(target) {

		var t = this;
		return M.FETCHTARGET_SETTINGS[target];
	},
	getFetchTargetPageSection : function(target) {
	
		var t = this;

		if (!target || target == '_self' || !M.FETCHTARGET_SETTINGS[target]) {
			target = M.URLPARAM_TARGET_MAIN;
		}

		return $('#'+M.FETCHTARGET_SETTINGS[target]);
	},
	getFetchPageSectionSettings : function(pageSection) {
	
		var t = this;
		var psId = pageSection.attr('id');
		return M.FETCHPAGESECTION_SETTINGS[psId] || {};
	},
	
	getTemplatesCbs : function(domain, pageSection, target, action) {
	
		var t = this;

		action = action || 'main';

		var templatesCbs = t.getSettings(domain, pageSection, target, 'templates-cbs');
		var cbs = templatesCbs.cbs;
		var actions = templatesCbs.actions;

		// If it's an empty array, return already (ask if it's array, because only when empty is array, when full it's object)
		if ($.isArray(actions) && !actions.length) {

			return cbs;
		}
		
		// Iterate all the callbacks, check if they match the passed action
		var allowed = [];
		$.each(cbs, function(index, cb) {

			// Exclude callback if it has actions, but not this one action
			if (!actions[cb] || actions[cb].indexOf(action) > -1) {

				// The action doesn't belong, kick the callback template out
				allowed.push(cb);
			}
		});

		return allowed;
	},

	getTemplatePath : function(domain, pageSection, target, template) {
	
		var t = this;
		
		var templatePaths = t.getSettings(domain, pageSection, target, 'templates-paths');
		return templatePaths[template];
	},
	
	getScriptTemplate : function(/*domain, */templateName) {

		var t = this;
		var templateSource = t.getTemplateSource(/*domain, */templateName);
		return Handlebars.templates[templateSource];
	},

	getPageSectionGroup : function(elem) {
	
		var t = this;
		return elem.closest('.pop-pagesection-group').addBack('.pop-pagesection-group');
	},
	getPageSection : function(elem) {
	
		var t = this;
		return elem.closest('.pop-pagesection').addBack('.pop-pagesection');
	},
	getPageSectionPage : function(elem) {
	
		var t = this;		
		var page = elem.closest('.pop-pagesection-page').addBack('.pop-pagesection-page');
		if (page.length) {
			return page;
		}
		return t.getPageSection(elem);
	},
	
	getBlockId : function(pageSection, block, options) {
	
		var t = this;		

		return t.getRuntimeMemoryPage(pageSection, block, options).id;
	},
	
	getHtml : function(/*domain, */templateName, context) {

		var t = this;	
		var template = t.getScriptTemplate(/*domain, */templateName);

		// Comment Leo 29/11/2014: some browser plug-ins will not allow the template to be created
		// Eg: AdBlock Plus. So when that happens (eg: when requesting template "socialmedia-source") template is undefined
		// So if this happens, then just return nothing
		if (typeof template == 'undefined') {
			console.error('No template for ' + templateName);
			return '';
		}

		try {
			return template(context);
		}
		catch(err) {
			// Do nothing
			console.log('Error in '+templateName+': '+err.message);
			// console.trace();
		}
		return '';
	},

	getItemObject : function(domain, itemDBKey, itemObjectId) {

		var t = this;
		var userItem = {}, item = {};
		var userdatabase = t.getUserDatabase(domain);
		var database = t.getDatabase(domain);
		if (userdatabase[itemDBKey] && userdatabase[itemDBKey][itemObjectId]) {
			userItem = userdatabase[itemDBKey][itemObjectId];
		}
		if (database[itemDBKey] && database[itemDBKey][itemObjectId]) {
			item = database[itemDBKey][itemObjectId];
		}
		// if (t.userdatabase[itemDBKey] && t.userdatabase[itemDBKey][itemObjectId]) {
		// 	userItem = t.userdatabase[itemDBKey][itemObjectId];
		// }
		// if (t.database[itemDBKey] && t.database[itemDBKey][itemObjectId]) {
		// 	item = t.database[itemDBKey][itemObjectId];
		// }
		return $.extend({}, userItem, item);
	},

	getFrameTarget : function(pageSection) {

		var t = this;
		return pageSection.data('frametarget') || M.URLPARAM_TARGET_MAIN;
	},

	getClickFrameTarget : function(pageSection) {

		var t = this;
		return pageSection.data('clickframetarget') || t.getFrameTarget(pageSection);
	},

	getOriginalLink : function(link) {

		var t = this;

		// If the link is an interceptor, then return the data from the original intercepted element
		// Otherwise, it is the link itself, retrieve from there
		var intercepted = link.data('interceptedTarget');
		if (intercepted) {
			return t.getOriginalLink(intercepted);
		}

		return link;
	},
};
})(jQuery);