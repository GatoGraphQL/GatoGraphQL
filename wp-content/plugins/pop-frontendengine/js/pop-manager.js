(function($){
popManager = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	
	// deferredQueue : [],
	mergingTemplatePromise : false,
	memory : {
		settings: {},
		runtimesettings: {},
		dataset: {},
		feedback: {
			block: {},
			pagesection: {},
			toplevel: {}
		},
		params: {}
	},
	// Used to override the dataset/feedback/params when resetting the block
	initialBlockMemory : {
		// dataset: {},
		// feedback: {
		// 	block: {},
		// },
		// params: {},
		// runtimesettings: {
		// 	configuration: {}
		// }
	},
	// Used to override the values before replicating
	urlPointers : {},
	replicableMemory : {},
	runtimeMemory : {
		general: {},
		url: {}
	},
	database : {},
	userdatabase : {},
	firstLoad : {},
	documentTitle : null, // We keep a copy of the document title, so we can add the notification number in it
	// progress : 0,

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	getMemory : function() {

		var t = this;
		return t.memory;
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
		// var initialMemory = t.getInitialBlockMemory();
		var url = response.feedback.toplevel[M.URLPARAM_URL];
		if (!t.initialBlockMemory[url]) {
			t.initialBlockMemory[url] = {
				dataset: {},
				feedback: {
					block: {},
				},
				params: {},
				runtimesettings: {
					configuration: {}
				}
			};
		}
		var initialMemory = t.initialBlockMemory[url];
		$.each(response.settings.configuration, function(pssId, rpsConfiguration) {

			if (!initialMemory.params[pssId]) {
				initialMemory.params[pssId] = {};
				initialMemory.runtimesettings.configuration[pssId] = {};
				initialMemory.dataset[pssId] = {};
				initialMemory.feedback.block[pssId] = {};
			}
			$.extend(initialMemory.params[pssId], response.params[pssId]);
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
			// if (psConfiguration['block-settings-ids']['blockunits-replicable'].length) {
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
				params: $.extend(true, {}, response.params)
			}
		}
	},
	getRuntimeMemory : function(pageSection, target, options) {

		var t = this;

		options = options || {};

		// To tell if it's general or url, check for data-paramscope in the pageSection
		var pageSectionPage = t.getPageSectionPage(target);
		var scope = pageSectionPage.data('paramsscope');
		if (scope == M.SETTINGS_PARAMSSCOPE_URL) {
			
			var url = options.url || t.getTopLevelFeedback()[M.URLPARAM_URL];//''+window.location.href;
			if (!t.runtimeMemory.url[url]) {
			
				// Create a new instance for this URL
				t.runtimeMemory.url[url] = {};
			}

			// Save which url the params for this target is under
			target.data('paramsscope-url', url);

			return t.runtimeMemory.url[url];
		}

		// The entry can be created either under 'general' for all pageSections who are static, ie: they don't bring any new content with the url (eg: top-frame)
		// or under 'url' for the ones who depend on a given url, eg: main
		return t.runtimeMemory.general;
	},
	newRuntimeMemoryPage : function(pageSection, target, options) {

		var t = this;
		
		// Take the URL from the topLevelFeedback and not from window.location.href when creating a new one.
		// this is so that we don't need to update the browser url, which sometimes we don't want, eg: when replicating Add Comment in the addon pageSection
		options = options || {};
		if (!options.url) {
			var tlFeedback = t.getTopLevelFeedback();
			options.url = tlFeedback[M.URLPARAM_URL];
		}
		var mempage = t.getRuntimeMemory(pageSection, target, options);

		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(target);

		if (!mempage[pssId]) {
			mempage[pssId] = {};
		}
		mempage[pssId][targetId] = {
			params: {},
			id: null
		};

		return mempage[pssId][targetId];
	},
	deleteRuntimeMemoryPage : function(pageSection, target, options) {

		var t = this;
		var mempage = t.getRuntimeMemory(pageSection, target, options);

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
			url = target.data('paramsscope-url');
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

	// setProgress : function() {

	// 	var t = this;

	// 	// setTimeout is needed to make it work. Taken from https://stackoverflow.com/questions/30285185/why-doesnt-progress-bar-dynamically-change-unlike-text
	// 	// Every 100ms check the progress and update the progress-bar
	// 	setTimeout(function () {

	// 		// document.getElementById('loadingprogress').style.width = t.progress+'%';
	// 		var width = t.progress+'%';
	// 		$('#loadingprogress')
	// 			.css('width', width)
	// 			.attr('aria-valuenow', t.progress)
	// 			.find('.percent')
	// 				.text(t.progress);

	// 		// Don't stop until reaching 100%
	// 		if (t.progress < 100) {
	// 			t.setProgress();
	// 		}
	// 	}, 100);
	// },

	init : function() {
	
		var t = this;

		// Comment Leo 22/08/2016: when is_search_engine(), there is no #toplevel, so do nothing
		if ($('#'+popPageSectionManager.getTopLevelSettingsId()).length) {

			t.initDocument();

			// Initialize Settings, Feedback and Data
			t.initTopLevelJson();

			// Make sure the localStorage has no stale entries
			t.initLocalStorage();

			// Log in the user immediately, before rendering the HTML. This way, conditional wrappers can access the state of the user
			// being logged in, such as for "isUserIdSameAsLoggedInUser"
			popUserAccount.initialLogin();

			// Obtain what pageSections to merge from the configuration
			var memory = t.getMemory();

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

				t.initPageSectionSettings(pageSection, psConfiguration);

				// Insert into the Runtime to generate the ID
				t.addPageSectionIds(pageSection, psConfiguration[M.JS_TEMPLATE]);

				// Allow plugins to catch events for the rendering
				t.initPageSection(pageSection);

				pageSections.push(pageSection);
			});


			// // Progress bar
			// var delta = 100/pageSections.length; 
			// t.setProgress();

			// Step 1: render the pageSection
			$.each(pageSections, function(index, pageSection) {

				// // Re-calculate the progress
				// t.progress = parseInt(delta*(index+1));

				t.renderPageSection(pageSection);
			});

			// Step 2: remove the "loading" screen
			$('#body').removeClass('pop-loadingframe');

			// Step 3: execute JS
			$.each(pageSections, function(index, pageSection) {

				t.pageSectionInitialized(pageSection);
			});

			// Step 4: remove the "loading" screen
			$('#body').removeClass('pop-loadingjs');

			// Comment Leo 01/12/2016: not needed anymore, since externalizing 'activeLinks' as a JS method to run on the Menu Blocks
			// $.each(memory.settings.configuration, function(pssId, psConfiguration) {

			// 	var psId = psConfiguration[M.JS_FRONTENDID];
			// 	var pageSection = $('#'+psId);

			// 	// Add "active" to current links in corresponding links
			// 	var settings = t.getFetchPageSectionSettings(pageSection);
			// 	if (settings.activeLinks) {

			// 		t.activeLinks(pageSection);
			// 	}
			// });

			// Possibly the server will bring back a different URL to the one loaded. 
			// Eg: when loading https://www.mesym.com/add-event/, it will instead bring back https://www.mesym.com/add-event/#2342354569
			// This way, we can also capture the very first add-event page when going back on browser btn, without it create yet another Add Event page
			// (which will happen when clicking on the https://www.mesym.com/add-event/ link)
			// var topLevelFeedback = t.getTopLevelFeedback();
			// if (topLevelFeedback[M.URLPARAM_URL] != window.location.href) {
			// 	popBrowserHistory.replaceState(topLevelFeedback[M.URLPARAM_URL]);
			// }

			var topLevelFeedback = t.getTopLevelFeedback();
			var url = topLevelFeedback[M.URLPARAM_URL];
			if (!topLevelFeedback[M.URLPARAM_SILENTDOCUMENT]) {
				
				// Update URL: it will remove the unwanted items, eg: mode=embed (so that if the user clicks on the newWindow btn, it opens properly)
				popBrowserHistory.replaceState(url);
				t.updateDocument();
			}

			t.documentInitialized();

			// If the server requested to extra load more URLs
			t.backgroundLoad(M.BACKGROUND_LOAD); // Initialization of modules (eg: Modals, Addons)
			t.backgroundLoad(topLevelFeedback[M.URLPARAM_BACKGROUNDLOADURLS]); // Data to be loaded from server (eg: forceserverload_fields)
		}

		// Comment Leo 01/12/2016: this logic was moved up
		// // After everything is ready, remove the initial loading screen
		// $(document).ready(function($){
		// 	$('body').removeClass('pop-loadingframe');
		// });
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
			if (context[M.JS_TITLES]) {
				context.titles = context[M.JS_TITLES];
			}

			// Allow Custom .js to add their own JS Keys (eg: Fontawesome)
			popJSLibraryManager.execute('expandJSKeys', {context: context});
		}
	},

	addPageSectionIds : function(pageSection, template) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var psId = pageSection.attr('id');

		// Insert into the Runtime to generate the ID
		popJSRuntimeManager.addPageSectionId(pssId, template, psId);

		var args = {
			pageSection: pageSection,
			template: template
		}

		popJSLibraryManager.execute('addPageSectionIds', args);
	},

	initDocument : function() {
	
		var t = this;

		// $(document).on('user:loggedinout', function(e, source) {
		$(document).on('user:loggedout', function(e, source) {

			// Clear the user database when the user logs out
			// Comment Leo 07/03/2016: this was initially loggedinout, however it deletes the userdatabase immediately, when the user is logged in and accessing a stateful page
			t.clearUserDatabase();
		});

		popJSLibraryManager.execute('initDocument');
		$(document).triggerHandler('initialize.pop.document');
	},

	documentInitialized : function() {
	
		var t = this;

		popJSLibraryManager.execute('documentInitialized');
		$(document).triggerHandler('initialized.pop.document');
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

		// pageSection.on('bufferMerged', function(e, buffer) {
		// 	var pageSection = $(this);
		// 	t.runBuffer(pageSection, buffer);
		// });
		// pageSection.on('destroy', function(e) {
		// 	var pageSection = $(this);
		// 	// t.destroyTemplate(pageSection);
		// 	t.destroyElemBranchScripts(pageSection, pageSection);
		// });

		// pageSection.on('statePopped', function(e) {

		// 	var pageSection = $(this);
		// 	// t.handleEvents('statePopped', pageSection, pageSection);
		// 	t.pageSectionNewDOMsInitialized(pageSection, pageSection);
		// });
		// pageSection.on('beforeStatePop', function(e) {

		// 	var pageSection = $(this);
		// 	t.popState(pageSection);
		// 	t.destroyPageSection(pageSection);
		// });

		t.firstLoad[t.getSettingsId(pageSection)] = true;

		// pageSection.on('rendered', function(e, newDOMs) {

		// 	var pageSection = $(this);
		// 	t.pageSectionRendered(pageSection, newDOMs);
		// });

		popJSLibraryManager.execute('initPageSection', {pageSection: pageSection});
		pageSection.triggerHandler('initialize');
		$(document).triggerHandler('initialize.pop.pagesection', [pageSection]);
	},

	pageSectionInitialized : function(pageSection) {
	
		var t = this;

		// Initialize the params for this branch
		t.initPageSectionRuntimeMemory(pageSection);

		popJSLibraryManager.execute('pageSectionInitialized', {pageSection: pageSection});
		pageSection.triggerHandler('initialized');
		pageSection.triggerHandler('completed');


		// Once the template has been initialized, then that's it, no more JS running, set firstLoad in false
		t.firstLoad[popManager.getSettingsId(pageSection)] = false;
	},

	pageSectionNewDOMsInitialized : function(pageSection, newDOMs, options) {
	
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
			// var openmode = pageSection.data('pagesection-openmode') || 'automatic';
			var openmode = popPageSectionManager.getOpenMode(pageSection);
			var firstLoad = t.isFirstLoad(pageSection);
			if (openmode == 'automatic' || (firstLoad && openmode == 'initial')) {
				popPageSectionManager.open(pageSection);
			}
		}

		var args = {
			pageSection: pageSection,
			newDOMs: newDOMs
		};
		t.extendArgs(args, options);
		
		// Execute this first, so we can switch the tabPane to the newly added one before executing the JS
		popJSLibraryManager.execute('pageSectionNewDOMsBeforeInitialize', args);

		t.initPageSectionBranches(pageSection, newDOMs, options);
			
		// Comment Leo 01/12/2016: not needed anymore, since externalizing 'activeLinks' as a JS method to run on the Menu Blocks
		// // Paint the active links in the newDOMs
		// var settings = t.getFetchPageSectionSettings(pageSection);
		// if (settings.activeLinks) {

		// 	t.activeLinks(newDOMs);
		// }

		popJSLibraryManager.execute('pageSectionNewDOMsInitialized', args);
	},

	initPageSectionBranches : function(pageSection, newDOMs, options) {
	
		var t = this;

		// Initialize the params for this branch
		// t.initPageSectionRuntimeMemory(pageSection);

		// First initialize the JS for the pageSection
		t.runPageSectionJSMethods(pageSection, options);

		// Then, Initialize all inner scripts for the blocks
		// It is fine that it uses pageSection in the 2nd params, since it's there that it stores the branches information, already selecting the proper element nodes
		var jsSettings = popManager.getPageSectionJsSettings(pageSection);
		var blockBranches = jsSettings['initjs-blockbranches'];
		if (blockBranches) {

			var branches = $(blockBranches.join(',')).not('.'+M.JS_INITIALIZED);
			t.initBlockBranches(pageSection, branches, options);
			// var pageSectionPage = newDOMs.filter('.pop-pagesection-page');
			// if (pageSectionPage.length && !t.isActive(pageSectionPage)) {

			// 	// Initialize the blocks only when the pageSectionPage becomes active
			// 	if (pageSectionPage.hasClass('tab-pane')) {

			// 		pageSectionPage.one('shown.bs.tabpane', function() {
			// 			t.initBlockBranches(pageSection, branches, options);
			// 		});
			// 	}
			// }
			// else {
			// 	t.initBlockBranches(pageSection, branches, options);				
			// }
		}

		// Delete the session ids at the end of the rendering
		popJSRuntimeManager.deletePageSectionLastSessionIds(pageSection);
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

	destroyTarget : function(pageSection, target) {

		var t = this;

		// Call 'destroy' from all libraries in popJSLibraryManager
		var args = {
			pageSection: pageSection,
			destroyTarget: target
		}
		popJSLibraryManager.execute('destroyTarget', args);
		target.triggerHandler('destroy');

		// Eliminate the params for each destroyed block
		var blocks = target.find('.pop-block.'+M.JS_INITIALIZED).addBack('.pop-block.'+M.JS_INITIALIZED);
		blocks.each(function() {
			
			var block = $(this);
			t.deleteRuntimeMemoryPage(pageSection, block, {url: block.data('paramsscope-url')});
		})

		// Remove from the DOM
		target.remove();
	},

	destroyPageSectionPage : function(pageSection, pageSectionPage) {

		var t = this;

		var target = pageSectionPage || pageSection;

		// When it's closed, if there's no other pageSectionPage around, then close the whole pageSection
		if (!pageSectionPage.siblings('.pop-pagesection-page').length) {
			popPageSectionManager.close(pageSection);
			popPageSectionManager.close(pageSection, 'xs');
		}

		t.destroyTarget(pageSection, target);
	},

	pageSectionRendered : function(pageSection, newDOMs, options) {

		var t = this;		

		t.pageSectionNewDOMsInitialized(pageSection, newDOMs, options);

		var args = {
			pageSection: pageSection,
			newDOMs: newDOMs
		}
		t.extendArgs(args, options);

		popJSLibraryManager.execute('pageSectionRendered', args);
		pageSection.triggerHandler('pageSectionRendered');
	},

	// popState : function(pageSection) {

	// 	var t = this;		

	// 	t.destroyPageSection(pageSection);

	// 	popJSLibraryManager.execute('popState', {pageSection: pageSection});
	// 	pageSection.triggerHandler('popState');
	// },

	// statePopped : function(pageSection) {

	// 	var t = this;
		
	// 	t.pageSectionNewDOMsInitialized(pageSection, pageSection);	
		
	// 	popJSLibraryManager.execute('statePopped', {pageSection: pageSection});
	// 	pageSection.triggerHandler('statePopped');
	// },

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

	initBlockBranches : function(pageSection, blocks, options) {

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
				t.initBlock(pageSection, block, options);

				var jsSettings = popManager.getJsSettings(pageSection, block);
				var blockBranches = jsSettings['initjs-blockbranches'];
				if (blockBranches) {
					t.initBlockBranches(pageSection, $(blockBranches.join(', ')).not('.'+M.JS_INITIALIZED), options);
				}
				var blockChildren = jsSettings['initjs-blockchildren'];
				if (blockChildren) {

					var target = block;
					$.each(blockChildren, function(index, selectors) {
						$.each(selectors, function(index, selector) {

							target = target.children(selector).not('.'+M.JS_INITIALIZED);
						});
					});
					t.initBlockBranches(pageSection, target, options);
				}
			}
		});
	},

	// destroyBlockBranches : function(pageSection, blocks, options) {

	// 	var t = this;

	// 	// When being called from the parent node, the branch might still not exist
	// 	// (eg: before it gets activated: #frame-main_blockgroup-tabpanel-main-blockgroup-tabpanel-sections.tab-pane.active > #frame-main_blockgroup-tabpanel-main-blockgroup-tabpanel-sections-body")
	// 	if (!blocks.length) {
	// 		return;
	// 	}

	// 	blocks.each(function() {

	// 		var block = $(this);
	// 		// Only destroy initialized blocks (of course!)
	// 		if (t.jsInitialized(block)) {

	// 			t.destroyBlock(pageSection, block, options)

	// 			var jsSettings = popManager.getJsSettings(pageSection, block);
	// 			var blockBranches = jsSettings['destroyjs-blockbranches'];
	// 			if (blockBranches) {

	// 				t.destroyBlockBranches(pageSection, $(blockBranches.join(', ')), options);
	// 			}
	// 		}
	// 	});
	// },

	// destroyBlock : function(pageSection, block, options) {

	// 	var t = this;

	// 	block.triggerHandler('destroy');		
	// },

	initBlock : function(pageSection, block, options) {

		var t = this;
		options = options || {};
		
		// Do an extend of $options, so that the same object is not used for initializing 2 different blocks.
		// Eg: we don't to change the options.url on the same object for newRuntimePage. That could lead to 2 different blocks using the same URL,
		// it happens when doing openTabs with more than 2 tabs, it does it so quick that the calendar on the right all point to the same URL
		t.initBlockRuntimeMemory(pageSection, block, $.extend({}, options));

		// Allow scripts and others to perform certain action after the runtimeMemory was generated
		t.initializeBlock(pageSection, block, options);

		t.runScriptsBefore(pageSection, block);
		t.runBlockJSMethods(pageSection, block, options);
		
		// Important: place these handlers only at the end, so that handlers specified in popManagerMethods are executed first
		// and follow the same order as above
		// This needs to be 'merged' instead of 'rendered' so that it works also when calling mergeTargetTemplate alone, eg: for the featuredImage
		// block.on('merged', function(e, newDOMs) {
		block.on('rendered', function(e, newDOMs) {
	
			var block = $(this);
			
			t.runScriptsBefore(pageSection, newDOMs);
			
			// This won't execute again the JS on the block when adding newDOMs, because by then
			// the block ID will have disappeared from the lastSessionIds. The only ids will be the new ones,
			// contained in newDOMs
			t.runBlockJSMethods(pageSection, block, null, options);
		});

		// When resetting the block (eg: editing post in offcanvas) run the blockScripts once again
		// block.on('reset', function(e) {
		
		// 	var block = $(this);
		// 	// t.runBlockScripts(pageSection, block);
		// 	t.runJSMethods(pageSection, block);
		// });

		t.blockInitialized(pageSection, /*pageSectionPage, */block, options);

		// And only now, finally, load the block Content (all JS has already been initialized)
		// (eg: function setFilterBlockParams needs to set the filtering params, but does it on js:initialized event. Only after this, load content)
		t.loadBlockContent(pageSection, block);
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

	blockInitialized : function(pageSection, block, options) {
	
		var t = this;

		var args = {
			pageSection: pageSection,
			// pageSectionPage: pageSectionPage,
			block: block
		};
		t.extendArgs(args, options);

		popJSLibraryManager.execute('blockInitialized', args);

		// Trigger event
		block.triggerHandler('js:initialized');
	},

	loadBlockContent : function(pageSection, block) {

		var t = this;

		if (!t.isContentLoaded(pageSection, block)) {

			// Set the content as loaded
			t.setContentLoaded(pageSection, block);

			// Send trigger to initialize others once content is loaded
			// block.one('fetchCompleted', function() {

			// 	block.triggerHandler('content:loaded');
			// });
			var options = {
				action: M.CBACTION_LOADCONTENT,
				'post-data': block.data('post-data')
			};

			// Show disabled layer?
			var jsSettings = t.getJsSettings(pageSection, block);
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

	runPageSectionJSMethods : function(pageSection, options) {
	
		var t = this;

		// console.log('runPageSectionJSMethods', pageSection);
		var sessionIds = popJSRuntimeManager.getPageSectionSessionIds(pageSection);
		// console.log(sessionIds);
		if (!sessionIds) return;

		// Make sure it executes the JS in each template only once.
		// Eg: Otherwise, since having MultiLayouts, it will execute 'popover' for each single 'layout-popover-user' template found, and it repeats itself inside a block many times
		var executedTemplates = [];
		var templateMethods = popManager.getPageSectionJsMethods(pageSection);
		// console.log('templateMethods', templateMethods);
		$.each(templateMethods, function(template, groupMethods) {
			// console.log('template', template, 'groupMethods', groupMethods, 'sessionIds[template]', sessionIds[template]);
			if (executedTemplates.indexOf(template) == -1) {
				executedTemplates.push(template);
				if (sessionIds[template] && groupMethods) {
					$.each(groupMethods, function(group, methods) {
						var ids = sessionIds[template][group];
						// console.log('group', group, 'methods', methods, 'ids', ids);
						if (ids) {
							var selector = '#'+ids.join(', #');
							var targets = $(selector);
							// console.log('selector', selector, 'targets', targets);
							if (targets.length) {
								$.each(methods, function(index, method) {
									// console.log('executing ' + method + ' in template ' + template + ' for group ' + group + ' and targets ', targets);
									var args = {
										pageSection: pageSection,
										targets: targets
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

	runBlockJSMethods : function(pageSection, block, options) {
	
		var t = this;
		t.runJSMethods(pageSection, block, null, null, options);
		
		// Delete the session ids after running the js methods
		popJSRuntimeManager.deleteBlockLastSessionIds(pageSection, block);
	},
	runJSMethods : function(pageSection, block, templateFrom, session, options) {
	
		var t = this;

		// Get the blockSessionIds: these contain the ids added to the block only during the last
		// 'rendered' session. This way, when appending newDOMs (eg: with waypoint on scrolling down),
		// it will execute the JS scripts only on these added elements and not the whole block
		var sessionIds = popJSRuntimeManager.getBlockSessionIds(pageSection, block, session);
		// console.log(pageSection, block, templateFrom, session, options, sessionIds);
		if (!sessionIds) return;
		
		var templateMethods = t.getTemplateJSMethods(pageSection, block, templateFrom);
		t.runJSMethodsInner(pageSection, block, templateMethods, sessionIds, [], options);
	},	
	runJSMethodsInner : function(pageSection, block, templateMethods, sessionIds, executedTemplates, options) {
	
		var t = this;
		options = options || {};

		// For each template, analyze what methods must be executed, and then continue down the line
		// doing the same for contained templates
		var template = templateMethods[M.JS_TEMPLATE];//templateMethods.template;
		var groupMethods = templateMethods[M.JS_METHODS];//templateMethods.methods;

		if (executedTemplates.indexOf(template) == -1) {
			
			executedTemplates.push(template);
			
			// console.log('template', template, 'groupMethods', groupMethods, 'sessionIds', sessionIds[template]);
			if (sessionIds[template] && groupMethods) {
				$.each(groupMethods, function(group, methods) {
					var ids = sessionIds[template][group];
					// console.log('group', group, 'methods', methods, 'ids' + ids);
					if (ids) {
						var selector = '#'+ids.join(', #');
						var targets = $(selector);
						// console.log('targets ' + targets);
						if (targets.length) {
							$.each(methods, function(index, method) {
								// console.log('executing ' + method + ' in template ' + template + ' for targets ', targets);
								var args = {
									pageSection: pageSection,
									block: block,
									targets: targets
									// selector: selector
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
				
				t.runJSMethodsInner(pageSection, /*pageSectionPage, */block, next, sessionIds, executedTemplates, options);
			})
		}
	},
	getTemplateJSMethods : function(pageSection, block, templateFrom) {
	
		var t = this;

		var templateMethods = popManager.getBlockJsMethods(pageSection, block);
		
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
				// t.fetch(feedback.redirect.url);
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

	// historyReplaceState : function(pageSection, elem, options) {
	historyReplaceState : function(elem, options) {

		var t = this;

		var url = elem.data('historystate-url');
		var title = elem.data('historystate-title');
		var thumb = elem.data('historystate-thumb');

		// popHistory.replaceState(pageSection, url, title, thumb, options);
		// popBrowserHistory.replaceState(url, title, thumb, options);
		popBrowserHistory.replaceState(url);

		// Also update the title in the browser tab
		if (title) {
			t.documentTitle = unescapeHtml(title);
			document.title = t.documentTitle;
		}
	},

	hideIfEmpty : function(pageSection, block) {

		var t = this;

		var feedback = popManager.getBlockFeedback(pageSection, block);
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
		// if (elem.hasClass('active')) return true;

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

		var blockParams = t.getBlockParams(pageSection, block);

		// The "|| false" is needed because waypoints doesn't work passing 'undefined' to its enabled, either true or false
		return blockParams[M.DATALOAD_CONTENTLOADED] || false;
	},
	setContentLoaded : function(pageSection, block) {

		var t = this;

		var blockParams = t.getBlockParams(pageSection, block);
		blockParams[M.DATALOAD_CONTENTLOADED] = true;
	},

	// isAggregator : function(block) {

	// 	var t = this;
	// 	return block.hasClass('template-aggregator');
	// },

	getSettingsTemplate : function(elem) {

		var t = this;
		if (elem.data('templateid')) {
			return elem;
		}
		return elem.closest('data-templateid');
	},

	updateDocument : function() {

		var t = this;
		// Source can be params or topLevelFeedback
		var feedback = t.getTopLevelFeedback();

		// Update the title in the page
		t.documentTitle = unescapeHtml(feedback[M.URLPARAM_TITLE]);
		document.title = t.documentTitle;
	},

	// activeLinks : function(elems) {

	// 	var t = this;
	// 	// Source can be params or topLevelFeedback
	// 	var feedback = t.getTopLevelFeedback();

	// 	// $('.menu-item').removeClass('active');
	// 	var parentPageId = feedback[M.URLPARAM_PARENTPAGEID];
	// 	if (parentPageId) {

	// 		elems.find('.menu-item-object-id-'+parentPageId).each(function() {

	// 			var menuItem = $(this);
	// 			menuItem.addClass('active');
	// 			menuItem.parents('.menu-item').addClass('active');

	// 			// If inside a collapse (with class 'pop-showactive'), open it
	// 			// Needed for the Side Sections Menu
	// 			jQuery(document).ready( function($) {
	// 				var collapse = menuItem.closest('.collapse.pop-showactive').not('.in');
	// 				collapse.collapse('show');
	// 			});
	// 		});
	// 	}
	// },

	hideMessages : function(pageSection) {

		var t = this;
		var status = popPageSectionManager.getPageSectionStatus(pageSection);
		var loading = status.find('.pop-loading');
		var error = status.find('.pop-error');
		loading.addClass('hidden');
		error.addClass('hidden');
	},

	executeSetFilterBlockParams : function(pageSection, block, filter) {
	
		var t = this;
		var blockParams = t.getBlockParams(pageSection, block);

		// Filter out inputs (input, select, textarea, etc) without value (solution found in http://stackoverflow.com/questions/16526815/jquery-remove-empty-or-white-space-values-from-url-parameters)
		blockParams.filter = filter.find('.' + M.FILTER_INPUT).filter(function () {return $.trim(this.value);}).serialize();

		// Only if filtering fields not empty (at least 1 exists) add the name of the filter
		if (blockParams.filter) {
			blockParams.filter += '&'+filter.find('.' + M.FILTER_NAME_INPUT).serialize();
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

	// setFilterParams : function(pageSection, filter) {
	
	// 	var t = this;
		
	// 	var block = t.getBlock(filter);
	// 	t.setFilterBlockParams(pageSection, block, filter);

	// 	// Copy the filter to the subscribed blocks
	// 	// var subscribedBlocksData = t.getAggregatorSubscribedBlocksData(pageSection, block);
	// 	// if (subscribedBlocksData) {

	// 	// 	$.each(subscribedBlocksData, function(index, subscribedBlockData) {

	// 	// 		var subscribedTemplateBlock = $('#'+subscribedBlockData['id']);
	// 	// 		t.setFilterBlockParams(pageSection, subscribedTemplateBlock, filter);
	// 	// 	});
	// 	// }
	// },
	
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
		var blockParams = t.getBlockParams(pageSection, block);
		
		// Delete the data saved
		// Comment Leo 03/04/2015: this is ugly and should be fixed: not all blocks have these elements (paged, stop-fetching)
		// The lists have it (eg: My Events) but an Edit Event page does not. However this one can also be reloaded (eg: first loading an edit page when user
		// is logged out, then log in, it will refetch the block), that's why I added the ifs. However a nicer way should be implemented
		if (blockParams[M.DATALOAD_PARAMS] && blockParams[M.DATALOAD_PARAMS][M.URLPARAM_PAGED]) {
			blockParams[M.DATALOAD_PARAMS][M.URLPARAM_PAGED] = '';
		}
		if (blockParams[M.URLPARAM_STOPFETCHING]) {
			blockParams[M.URLPARAM_STOPFETCHING] = false;
		}
	},	

	refetch : function(pageSection, block, options) {
	
		var t = this;
		
		options = options || {};
		options.action = M.CBACTION_REFETCH;

		block.triggerHandler('beforeRefetch');

		// Refetch
		popManager.reload(pageSection, block, options);
	},	

	reset : function(pageSection, block, options) {
	
		var t = this;
		options = options || {};

		// Sometimes there is no need to restore the initial memory, and even more, it can't be done since it has
		// unwanted consequences. Eg: when creating a user account, it will reset the form, but the messagefeedback
		// with the message "Your account was created successfully" must remain
		if (!options['skip-restore']) {

			// Reset the params. Eg: "pid", "_wpnonce"
			t.restoreInitialBlockMemory(pageSection, block);
		}

		// allow others to do something before the reset. Eg: the featuredImage needs to delete its internal data to be re-drawn
		// block.triggerHandler('beforeReset');
		t.processBlock(pageSection, block, {operation: M.URLPARAM_OPERATION_REPLACE, action: M.CBACTION_RESET, reset: true});
		// block.triggerHandler('reset');
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

		// Also for the subscribed?
		// var subscribedBlocksData = t.getAggregatorSubscribedBlocksData(pageSection, block);
		// if (subscribedBlocksData) {

		// 	$.each(subscribedBlocksData, function(index, subscribedBlockData) {

		// 		var subscribedTemplateBlock = $('#'+subscribedBlockData['id']);
		// 		t.setReloadBlockParams(pageSection, subscribedTemplateBlock);
		// 	});
		// }

		block.triggerHandler('beforeReload');

		// Load everything again
		t.fetchBlock(pageSection, block, options);
	},	

	loadLatest : function(pageSection, block, options) {
	
		var t = this;
		options = options || {};
		var blockParams = t.getBlockParams(pageSection, block);

		// Add the latest content on top of everything else
		options.operation = M.URLPARAM_OPERATION_PREPEND;

		// Do not check flag Stop Fetching, that is needed for the appended older content, not prepended newer one
		options['skip-stopfetching-check'] = true;

		// Delete the latestCount when fetch succedded
		options['hide-latestcount'] = true;

		// Add the action and the timestamp
		var post_data = {};
		post_data[M.URLPARAM_ACTION] = M.URLPARAM_ACTION_LATEST;
		post_data[M.URLPARAM_TIMESTAMP] = blockParams[M.URLPARAM_TIMESTAMP];
		options['onetime-post-data'] = $.param(post_data);

		block.triggerHandler('beforeLoadLatest');

		// Load latest content. 
		t.fetchBlock(pageSection, block, options);
	},	

	// handleLoadingStatus : function(pageSection, num, options) {
	handleLoadingStatus : function(pageSection, operation, options) {
		
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

	// maybeRestoreUniqueId : function(source) {

	// 	var t = this;

	// 	// This function makes it possible to use the cached settings: restore the uniqueId coming from the settings
	// 	// into the topLevel
	// 	// When the settings come from a cached file, then the uniqueId will be restored. If not, then the uniqueId in both
	// 	// the settings and the topLevel will be the same. When fetching block data, there will be no settings, so no need to execute this function, 
	// 	// use the topLevel uniqueId all along
	// 	if (source.settings[M.UNIQUEID]) {

	// 		t.getTopLevelFeedback()[M.UNIQUEID] = source.settings[M.UNIQUEID];
	// 	}
	// },

	initLocalStorage : function() {

		// Delete all stale entries
		if (M.USELOCALSTORAGE && Modernizr.localstorage) {
				
			var latest = localStorage['PoP:version'];
			if (!latest || (latest != M.VERSION)){

				// Delete all stale entries: all those starting with the website URL
				// Solution taken from https://stackoverflow.com/questions/7591893/html5-localstorage-jquery-delete-localstorage-keys-starting-with-a-certain-wo
				Object.keys(localStorage).forEach(function(key) { 
					if (key.startsWith(M.HOME_URL)) {
						localStorage.removeItem(key); 
					} 
				}); 

				// Save the current version
				localStorage['PoP:version'] = M.VERSION;
			}
		}
	},

	// Comment Leo 25/12/2016: cannot use .click(), because then we can't pass the options, and all of them (silentDocument, inactivePane, addOpenTab) are important
	// openTabs : function() {

	// 	var t = this;

	// 	var tabs = t.getOpenTabs();
	// 	$.each(tabs, function(target, urls) {

	// 		// If on the main pageSection...
	// 		if (target == M.URLPARAM_TARGET_MAIN) {

	// 			// Do not re-open the one URL the user opened
	// 			var pos = urls.indexOf(window.location.href);
	// 			if (pos !== -1) {
					
	// 				// Remove the entry
	// 				urls.splice(pos, 1);
	// 			}
	// 		}

	// 		// Open all tabs
	// 		$.each(urls, function(index, url) {

	// 			// Use click instead of fetch so that it can be intercepted (eg: for Add Post in the addons pageSection, no need to fetch it from the server)
	// 			t.click(url, target);
	// 		});
	// 	});
	// },
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

		var current = window.location.href;
		
		// Special case for the homepage: the link must have the final '/'
		if (current == M.HOME_URL) {
			current = M.HOME_URL+'/';
		}

		// Special case for qTranslateX: if we are loading the homepage, without the language information
		// (eg: https://kuwang.com.ar), and a tab with the language is open (eg: https://kuwang.com.ar/es/)
		// then have it removed, or the homepage will be open twice. For that, we assume the current does have
		// the language information, so it will be removed below
		if (current == M.HOME_URL+'/' && M.HOMELOCALE_URL) {
			current = M.HOMELOCALE_URL;
		}

		var tabs = t.getScreenOpenTabs();
		$.each(tabs, function(target, urls) {

			// Open the tab on the corresponding target
			options.target = target;

			// If on the main pageSection...
			if (target == M.URLPARAM_TARGET_MAIN) {

				// Do not re-open the one URL the user opened
				var pos = urls.indexOf(current);
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

	getOpenTabsKey : function() {

		var t = this;
		
		// The open tabs are saved for the combination of locale (language), theme and thememode for sure
		// var key = M.LOCALE+'|'+M.THEME+'|'+M.THEMEMODE;

		// Comment Leo 16/01/2017: we can all params set in the topLevel feedback directly
		var key = M.LOCALE;

		// Also add all the other "From Server" params if initially set (eg: themestyle, settingsformat, mangled)
		var params = t.getTopLevelFeedback()[M.DATALOAD_PARAMS];
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

	getStoredData : function(localStorageKey) {

		var t = this;
		// Check if a response is stored in local storage for that combination of URL and params
		// if (options.localStorage && Modernizr.localstorage) {
		if (M.USELOCALSTORAGE && Modernizr.localstorage) {
				
			var stored = localStorage[localStorageKey];
			if (stored) {

				// Transform the string back into JSON
				stored = $.parseJSON(stored);

				// Make sure the response was generated for the current version of the software
				// And also check if it has not expired
				if ((stored.version == M.VERSION) && (typeof stored.expires == 'undefined' || stored.expires > Date.now())){

					return stored.value;
				}

				// The entry is stale (either different version, or entry expired), so delete it
				delete localStorage[localStorageKey];
			}
		}

		return null;
	},

	storeData : function(localStorageKey, value, expires) {

		var t = this;
		if (M.USELOCALSTORAGE && Modernizr.localstorage) {
				
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

		// Silent mode: fetch URL but produce no output to the user
		// Needed for retrieving the logged in user data when first loading the website
		// var mode = options.mode;
		// var silent = (mode == 'silent');
		// params.loading.push(url);

		// Add a param to tell the back-end we are doing ajax
		// Important: do not change the order in which these attributes are added, or it can ruin other things,
		// eg: adding the get_precache_list pages for BACKGROUND_LOAD in plugins/poptheme-wassup/themes/wassup/thememodes/simple/thememode.php
		var target = t.getTarget(pageSection);

		// Remove any hashtag the url may have (eg: /add-post/#1482655583982)
		// Needed for when reopening the previous session tabs, and an Add Post page with such a hashtag was open
		// Otherwise, below it makes mess, it doesn't add the needed parameters to the URL
		var fetchUrl = url;
		if (fetchUrl.indexOf('#') > -1) {
			fetchUrl = fetchUrl.substr(0, fetchUrl.indexOf('#'));
		}
		fetchUrl = add_query_arg(M.URLPARAM_TARGET, target, fetchUrl);
		fetchUrl = add_query_arg(M.URLPARAM_MODULE, M.URLPARAM_MODULE_SETTINGSDATA, fetchUrl);
		fetchUrl = add_query_arg(M.URLPARAM_OUTPUT, M.URLPARAM_OUTPUT_JSON, fetchUrl);

		// Contains the attr for the Theme
		var topLevelFeedback = t.getTopLevelFeedback();
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
				
			t.processPageSectionResponse(pageSection, stored, options);
			t.triggerURLFetched(url);

			// That's it!
			return;
		}

		// Remove any parameter that might be included in the URL: the URL has priority (eg: source 'community/organization') can be overriden in the URL
		// var urlParams = splitParams(getParams(fetchUrl));
		// $.each(urlParams, function(key, value) {
		// 	delete paramsData[key];
		// });

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
		
				// if (!silent) {
				// t.handleLoadingStatus(pageSection, params.loading.length, options);
				t.handleLoadingStatus(pageSection, 'add', options);
				// }
			},
			complete: function(jqXHR) {

				// Everything below can be executed even if the deferred object executed in .processPageSectionResponse
				// has not resolved yet. 
				var url = params.url[jqXHR.url];
				delete params.url[jqXHR.url];
				delete params.target[jqXHR.url];
				delete params['fetch-url'][jqXHR.url];

				params.loading.splice(params.loading.indexOf(url), 1);

				t.handleLoadingStatus(pageSection, 'remove', options);

				// Callback when the url was fetched
				if (options['urlfetched-callbacks']) {

					// var callbacks = [];
					// if ($.isArray(options['urlfetched-callback'])) {
					// 	callbacks = options['urlfetched-callback'];
					// }
					// else {
					// 	callbacks.push(options['urlfetched-callback']);
					// }
					// $.each(callbacks, function(index, callback) {
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
			success: function(response, textStatus, jqXHR){

				// The server might have requested to load extra URLs (eg: forcedserverload_fields)
				t.backgroundLoad(response.feedback.toplevel[M.URLPARAM_BACKGROUNDLOADURLS]);

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
				t.processPageSectionResponse(pageSection, response, options);
			},
			error: function(jqXHR, textStatus, errorThrown) {

				// Remove the loading state (so incoming requests in this moment are not further processed)
				// params.loading = [];

				var fetchedUrl = params.url[jqXHR.url];
				t.showError(pageSection, t.getError(fetchedUrl, jqXHR, textStatus, errorThrown));
				pageSection.triggerHandler('fetchFailed');
			}
		}));
	},

	getCrossDomainOptions : function(url) {

		var t = this;
		var options = {};

		// If the URL is not from this same website, but from any aggregated website, then allow the cross domain
		if(!url.startsWith(M.HOME_URL)) {

			$.each(M.ALLOWED_URLS, function(index, allowed) {

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

	processPageSectionResponse : function(pageSection, response, options) {

		var t = this;

		// Save all entries in the replicable, both for a new fetch, or also if retrieved from localStorage
		var target = t.getTarget(pageSection);

		// For each URL to be intercepted, save under which page URL and target its memory has been stored
		t.saveUrlPointers(response, target);
		t.addReplicableMemory(response, target);
		t.addInitialBlockMemory(response);
		
		// Integrate the DB
		t.integrateDatabases(response);

		// Add to the queue of promises to execute and merge the template
		var dfd = $.Deferred();
		var lastPromise = t.mergingTemplatePromise;
		t.mergingTemplatePromise = dfd.promise();

		if (lastPromise) {
			lastPromise.done(function() {
				t.processPageSection(pageSection, response, options);
			});
		}
		else {
			t.processPageSection(pageSection, response, options);
		}

		// Resolve the deferred
		dfd.resolve();
	},

	maybeUpdateDocument : function(pageSection, options) {
		
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
				
				var topLevelFeedback = t.getTopLevelFeedback();
				popBrowserHistory.pushState(topLevelFeedback[M.URLPARAM_URL]);
			}
			t.updateDocument();
		}
	},

	processPageSection : function(pageSection, response, options) {
		
		var t = this;

		var target = t.getTarget(pageSection);

		// Integrate the response feedback
		t.integrateTopLevelFeedback(response);
		var topLevelFeedback = t.getTopLevelFeedback();

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
		t.maybeUpdateDocument(pageSection, options);

		// Do a Redirect?
		if (options.maybeRedirect) {
			if (t.maybeRedirect(topLevelFeedback)) return;
		}

		// Integrate the response		
		t.integratePageSection(response);

		// First Check if the response also includes other pageSections. Eg: when fetching Projects, it will also bring its MainRelated
		// Check first so that later on it can refer javascript on these already created objects
		$.each(response.settings.configuration, function(rpssId, rpsConfiguration) {

			var psId = rpsConfiguration[M.JS_FRONTENDID];//rpsConfiguration['frontend-id'];
			var pageSection = $('#'+psId);
			t.renderPageSection(pageSection, options);
		
			// Trigger
			pageSection.triggerHandler('fetched', [options, url]);
			pageSection.triggerHandler('completed');
		});	
	},

	scrollToElem : function(elem, position, animate) {

		var t = this;

		// In a carousel? Slide to the item containing this element
		// if (position) {
		// 	var carousel = position.closest('.carousel');
		// 	if (carousel.length) {
				
		// 		popBootstrapCarousel.showElement(carousel, position);
		// 	}
		// }

		// Try perfectScrollbar if available
		if (popPerfectScrollbar.scrollToElem(elem, position, animate)) {
			return;
		}
	},
	scrollTop : function(elem, top, animate) {

		var t = this;

		// Try perfectScrollbar if available
		if (popPerfectScrollbar.scrollTop(elem, top, animate)) {
			return;
		}

		// Try modal if available
		if (popBootstrap.scrollTop(elem)) {
			return;
		}
	},
	getPosition : function(elem) {

		var t = this;
		return popPerfectScrollbar.getPosition(elem);
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

	// getError : function(jqXHR, textStatus, errorThrown) {

	// 	var t = this;
	// 	var msg = '\n\nProblem: {0}';
	// 	msg += '\nURL: ' + jqXHR.url;
	// 	msg += '\nStatus: ' + textStatus;
	// 	msg += '\nerrorThrown: ' + errorThrown;
	// 	msg += '\nResponse: ' + jqXHR.responseText;

	// 	if (jqXHR.status == 404) {
			
	// 		msg = msg.format('404');
	// 		return M.ERROR_404.format(encodeURI(msg));
	// 	}

	// 	msg = msg.format('Standard error');
	// 	return M.ERROR_MSG.format(encodeURI(msg));
	// },
	getError : function(url, jqXHR, textStatus, errorThrown) {

		var t = this;
		if (jqXHR.status === 0) { // status = 0 => user is offline
			
			return M.ERROR_OFFLINE.format(url);
		}
		else if (jqXHR.status == 404) {
			
			return M.ERROR_404;
		}
		// else if (jqXHR.status >= 500) {
			
		// 	return M.ERROR_500PLUS;
		// }
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

	// getProxyBlockExtraClass : function(pageSection, block) {
		
	// 	var t = this;

	// 	var domain = t.getProxyDomain(pageSection, block);
	// 	var domain_class = domain.replace(/(http|\:|\/)/g, '').replace('.', '-');
	// 	return domain_class + ' ' + t.getSettingsId(block);
	// },	

	processBlock : function(pageSection, block, options) {
		
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var memory = t.getMemory();

		// Add 'items' from the dataset, as to be read in scroll-inner.tmpl / carousel-inner.tmpl
		options.extendContext = {
			items: memory.dataset[pssId][bsId]
		};

		// If it has an Aggregator, add the class of the subscribed template block
		// if (t.getAggregatorBlockData(pageSection, block)) {
			
		// 	options.extendContext['extend-class'] = t.getProxyBlockExtraClass(pageSection, block);
		// }
		t.renderTarget(pageSection, block, options);
	},

	fetchBlock : function(pageSection, block, options) {
		
		var t = this;
		options = options || {};

		if (!options['skip-stopfetching-check']) {

			// If 'stop-fetching' is true then don't bring anymore
			var blockParams = t.getBlockParams(pageSection, block);
			if (blockParams[M.URLPARAM_STOPFETCHING]) {

				return;
			}
		}

		// 2 Options: either the block is:
		// #1- an aggregator of blocks (mostly for the Platform of Platforms, commanding proxy blocks)
		// #2- a normal "standalone" block
		// If #1, then just realize a .fetchBlock in all the aggregated blocks
		// Otherwise, send an ajax request to the server for more data
		// var subscribedBlocksData = t.getAggregatorSubscribedBlocksData(pageSection, block);
		// if (subscribedBlocksData) {

		// 	// Replace the target where to append the html
		// 	// Default operation: append
		// 	if (!options.operation) {
		// 		options.operation = M.URLPARAM_OPERATION_APPEND;
		// 	}
		// 	$.each(subscribedBlocksData, function(index, subscribedBlockData) {

		// 		t.fetchBlock(pageSection, $('#'+subscribedBlockData['id']), options);
		// 	});
		// }
		// else {
		t.executeFetchBlock(pageSection, block, options);
		// }
	},

	// loadingStatus : function(pageSection, aggregatorBlock, subscribedBlock, operation) {

	// 	var t = this;

	// 	var loading = aggregatorBlock.find('.pop-loading');
	// 	var error = aggregatorBlock.find('.pop-error');

	// 	var classs = 'status-'+subscribedBlock.data('block');
	// 	var domains = loading.find('.template-status-domains');
		
	// 	if (operation == 'add') {

	// 		var domain = t.getProxyDomain(pageSection, subscribedBlock);
	// 		domains.append('<div class="'+classs+'"><em>'+domain+'</em></div>');
	// 		loading.removeClass('hidden');
	// 		error.addClass('hidden');
	// 	}
	// 	else if (operation == 'remove') {

	// 		domains.find('.'+classs).remove();
	// 		if (!domains.children().length) {
	// 			loading.addClass('hidden');
	// 		}
	// 	}
	// },

	getBlockPostData : function(pageSection, block, options) {
		
		var t = this;
		options = options || {};
		var paramsGroup = options.paramsGroup || 'all';
		// var paramsOverride = options['override-params'] || '';

		var blockParams = t.getBlockParams(pageSection, block);
		
		// Filter all params which are empty
		var params = {};
		if (paramsGroup == 'all') {
			$.extend(params, blockParams[M.DATALOAD_PARAMS]);
		}
		
		// Visible params: visible when using 'Open All' button
		var visibleparams = blockParams[M.DATALOAD_VISIBLEPARAMS];
		if (visibleparams) {
			$.extend(params, visibleparams);
		}
		
		$.each(params, function(key, value) {
			if (!value) delete params[key];
		});

		// Override params from the original URL?
		// if (options['override-params']) {
		// 	var overrideParams = splitParams(options['override-params']);
		// 	$.each(overrideParams, function(key, value) {
		// 		params[key] = value;
		// 	});
		// }

		var post_data = $.param(params);
		if (blockParams.filter) {
			if (post_data) {
				post_data += '&';
			}
			post_data += blockParams.filter;
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

	executeFetchBlock : function(pageSection, block, options) {
		
		var t = this;
		options = options || {};		

		// If the block has no query url, then nothing to do
		// (eg: when 'lazy-initializing' the offcanvas locations map, it has no content to fetch)
		var fetchUrl = t.getQueryUrl(pageSection, block);
		if (!fetchUrl) {
			return;
		}

		// Allow PoP Service Workers to modify the options, adding the Network First parameter to allow to fetch the preview straight from the server
		popJSLibraryManager.execute('modifyFetchBlockOptions', {options: options, url: fetchUrl});

		// If the params are already sent in the options, then use it
		// It's needed for loading the 'Edit Event' page, where the params are provided by the collapse in attr data-params
		// Override the post-data in the params, and then use it time and again (needed for the Navigator, it will set the filtering fields of the intercepted url into its post-data and send these again and again on waypoint scroll down - its own filter fields are empty!)
		var params = t.getBlockParams(pageSection, block);
		if (options['post-data']) {
			params['post-data'] = options['post-data'];
		}

		var topLevelFeedback = t.getTopLevelFeedback();
		var pageSectionFeedback = t.getPageSectionFeedback(pageSection);
		var blockFeedback = t.getBlockFeedback(pageSection, block);
		
		var paramsData = $.extend({}, topLevelFeedback[M.DATALOAD_PARAMS], pageSectionFeedback[M.DATALOAD_PARAMS], blockFeedback[M.DATALOAD_PARAMS]);
		// // Allow the paramsData to add the blockParams or not. Needed for the loadLatest content, where we want to get rid of pagination and other params
		// var paramsData = $.extend({}, topLevelFeedback[M.DATALOAD_PARAMS], pageSectionFeedback[M.DATALOAD_PARAMS]);
		// if (!options['skip-blockparams-data']) {
		// 	$.extend(paramsData, blockFeedback[M.DATALOAD_PARAMS]);
		// }
		
		var post_data = $.param(paramsData);

		if (params['post-data']) {

			if (post_data) {
				post_data += '&';
			}
			post_data += params['post-data'];
		}
		// onetime-post-data does not get integrated into the blockParams, so it will be used only when it is added through the options
		// needed for doing loadLatest
		if (options['onetime-post-data']) {

			if (post_data) {
				post_data += '&';
			}
			post_data += options['onetime-post-data'];
		}
		// Allow the paramsData to add the blockParams or not. Needed for the loadLatest content, where we want to get rid of pagination and other params
		// if (!options['skip-blockparams-data']) {
		var block_post_data = t.getBlockPostData(pageSection, block);
		if (post_data && block_post_data) {
			post_data += '&';
		}
		post_data += block_post_data;
		// }

		// Add a param to tell the back-end we are doing ajax
		var target = t.getTarget(pageSection);
		fetchUrl = add_query_arg(M.URLPARAM_TARGET, target, fetchUrl);
		fetchUrl = add_query_arg(M.URLPARAM_MODULE, M.URLPARAM_MODULE_DATA, fetchUrl);
		fetchUrl = add_query_arg(M.URLPARAM_OUTPUT, M.URLPARAM_OUTPUT_JSON, fetchUrl);

		var loadingUrl = fetchUrl + post_data;
		// If already loading this url (user pressed twice), then do nothing
		if (params.loading.indexOf(loadingUrl) > -1) {

			return;
		}

		// var aggregatorBlock;
		// var aggregatorData = t.getAggregatorBlockData(pageSection, block);
		// if (aggregatorData) {
		// 	aggregatorBlock = $('#'+aggregatorData['id']);
		// }

		// Show Loading / Hide Msg / Status: different for aggregator
		// if (aggregatorBlock) {

		// 	t.loadingStatus(pageSection, aggregatorBlock, block, 'add');
		// }
		// else {

		var loading = block.find('.pop-loading');
		var error = block.find('.pop-error');

		// Allow the "loading" and "error" message to not show up. Eg: for loadLatest, which is executed automatically
		if (!options['skip-status']) {
			loading.removeClass('hidden');
			error.addClass('hidden');	

			// Close Message
			t.closeMessageFeedback(block);
		}
		// }

		// Hide buttons / set loading msg
		t.triggerEvent(pageSection, block, 'beforeFetch', [options]);

		// Default type: GET
		var type = options.type || 'GET';

		// When doing refetch, and initializing the data (aka doing GET, not POST), then show the 'disabled' layer
		if (options['show-disabled-layer']) {
			block.children('.pop-disabledlayer').removeClass('hidden');
		}

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
				params.url[jqXHR.url] = loadingUrl;

				// Save the url being loaded
				params.loading.push(loadingUrl);

				// Save the Operation in the params
				params.operation[loadingUrl] = options.operation;

				// Save the Action in the params
				params.action[loadingUrl] = options.action;
				
				// the url is needed to re-identify the block, since all it's given to us on the response is the settings-id
				// which is not enough anymore since we can have different blocks with the same settings-id, so we need to find once again the id
				params['paramsscope-url'][loadingUrl] = block.data('paramsscope-url');
				
				// Is it a realod?
				if (options.reload) {

					params.reload.push(loadingUrl);				
				}
				// }
			},	
			success: function(response, textStatus, jqXHR){

				// Start loading extra urls
				t.backgroundLoad(response.feedback.toplevel[M.URLPARAM_BACKGROUNDLOADURLS]);

				// loadLatest: when it comes back, hide the latestcount div
				if (options['hide-latestcount']) {
					block.children('.blocksection-latestcount')
						.find('.pop-latestcount').addClass('hidden')
						.find('.pop-count').text('0');
				}

				// Comment Leo 03/12/2015: Using Deferred Object for the following reason:
				// Templates for 2 URLs cannot be merged at the same time, since they both access the same settings (unique thread)
				// So we need to make the template merging process synchronous. For that we implement a queue of deferred object,
				// Where each one of them merges only after the previous one process has finished, + mergingTemplatePromise = false for the first case and when there are no other elements in the queue
				// By the end of the success function all merging will be done, then we can proceed with following item in the queue
				// var mergingTemplatePromise = false;
				// if (t.deferredQueue.length) {
				// 	mergingTemplatePromise = t.deferredQueue[t.deferredQueue.length-1];
				// }
				var lastPromise = t.mergingTemplatePromise;
				var dfd = $.Deferred();
				var thisPromise = dfd.promise();
				t.mergingTemplatePromise = thisPromise;
				if (lastPromise) {
					lastPromise.done(function() {
						t.executeFetchBlockSuccess(pageSection, block, params, response, jqXHR);
					});
				}
				else {
					t.executeFetchBlockSuccess(pageSection, block, params, response, jqXHR);
				}

				// Resolve this promise
				// t.deferredQueue.shift();
				dfd.resolve();
			},
			error: function(jqXHR, textStatus, errorThrown) {

				t.handleBlockError(error, jqXHR, options);
				t.triggerEvent(pageSection, block, 'fetchFailed');
			},
			complete: function(jqXHR) {

				// Following instructions can be executed immediately when calling `complete`,
				// even if the merging has yet not taken place
				var status = {};

				var loadingUrl = params.url[jqXHR.url];
				delete params.url[jqXHR.url];

				// Remove the loading state of the tab
				params.loading.splice(params.loading.indexOf(loadingUrl), 1);
				delete params.operation[loadingUrl];
				delete params.action[loadingUrl];
				delete params['paramsscope-url'][loadingUrl];

				// Reload: remove if it exists
				var index = params.reload.indexOf(loadingUrl);
				if (index > -1) {
					status.reload = true;
					params.reload.splice(index, 1);
				}

				// Following instructions can be executed only after the merging has finished
				var lastPromise = t.mergingTemplatePromise;
				var dfd = $.Deferred();
				var thisPromise = dfd.promise();
				t.mergingTemplatePromise = thisPromise;

				if (lastPromise) {
					lastPromise.done(function() {
						t.fetchBlockComplete(pageSection, block, params, status, options);
					});
				}
				else {
					t.fetchBlockComplete(pageSection, block, params, status, options);
				}

				// Resolve the deferred
				dfd.resolve();
			}
		}));
	},

	fetchBlockComplete: function(pageSection, block, params, status, options) {

		var t = this;

		// Remove the 'disabled' layer
		block.children('.pop-disabledlayer').addClass('hidden');

		// Display the dataset count?
		if (options['datasetcount-target']) {
			
			t.displayDatasetCount(pageSection, block, $(options['datasetcount-target']), options['datasetcount-updatetitle']);
		}

		// if (aggregatorBlock) {

		// 	t.loadingStatus(pageSection, aggregatorBlock, block, 'remove');
		// }
		// else {

		// Only if not loading other URLs still
		if (!params.loading.length) {
			
			var loading = block.find('.pop-loading');
			loading.addClass('hidden');	
		}
		// }

		// Add/Remove class "pop-stopfetching"
		var blockParams = t.getBlockParams(pageSection, block);
		if (blockParams[M.URLPARAM_STOPFETCHING]) {

			block.addClass('pop-stopfetching');
		}
		else {
			
			block.removeClass('pop-stopfetching');
		}

		t.triggerEvent(pageSection, block, 'fetchCompleted', [status]);
	},

	displayDatasetCount : function(pageSection, block, target, updateTitle) {
		
		var t = this;

		var dataset = t.getBlockDataset(pageSection, block);
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

	executeFetchBlockSuccess : function(pageSection, block, params, response, jqXHR) {

		var t = this;

		// And finally process the block
		var loadingUrl = params.url[jqXHR.url];
		var action = params.action[loadingUrl];
		var runtimeOptions = {url: params['paramsscope-url'][loadingUrl]};
		var processOptions = {operation: params.operation[loadingUrl], action: params.action[loadingUrl]};

		var memory = t.getMemory();

		// Restore initial runtimeConfiguration: eg, for TPP Debate website, upon loading a single post, 
		// it will trigger to load the "After reading this" Add OpinionatedVoted with the already-submitted opinionatedvote, when it comes back
		// it must make sure to draw the original configuration, that's why restoring it below. Otherwise,
		// if clicking quick in 2 posts before the loading is finished, the configuration gets overwritten and the 1st post
		// is contaminated with configuration from the 2nd post
		var restoreinitial_actions = [M.CBACTION_LOADCONTENT, M.CBACTION_REFETCH, M.CBACTION_RESET];
		var restoreinitial = restoreinitial_actions.indexOf(action) > -1;
		if (restoreinitial) {
			var initialMemory = t.getInitialBlockMemory(runtimeOptions.url);
			$.each(response.params, function(rpssId, rpsParams) {	
				$.each(rpsParams, function(rbsId, rbParams) {
					// Use a direct reference instead of $.extend() because this one creates mess by messing up references when loading other
					// templates, since their initialBlockMemory will be cross-referencing and overriding each other
					memory.runtimesettings.configuration[rpssId][rbsId] = initialMemory.runtimesettings.configuration[rpssId][rbsId];
				});
			});
		}

		// Integrate topLevel
		t.integrateTopLevelFeedback(response);

		// Integrate the response data into the templateSettings
		t.integrateDatabases(response);

		// Integrate the block
		$.each(response.params, function(rpssId, rpsParams) {

			var rPageSection = $('#'+memory.settings.configuration[rpssId].id);
			if (!memory.feedback.block[rpssId]) {
				memory.feedback.block[rpssId] = {};
			}
			$.extend(memory.feedback.block[rpssId], response.feedback.block[rpssId]);
			if (!memory.dataset[rpssId]) {
				memory.dataset[rpssId] = {};
			}
			$.extend(memory.dataset[rpssId], response.dataset[rpssId]);
			if (!memory.params[rpssId]) {
				memory.params[rpssId] = {};
			}
			$.extend(memory.params[rpssId]);
				
			$.each(rpsParams, function(rbsId, rbParams) {

				try {
					// Integrate the params
					// If the user closed the tab that originated the ajax call before the response, then the params do not exist anymore
					// and getBlockParams will throw an exception. Then catch it, and do nothing else (no need anyway!)
					var rBlock = $('#'+t.getBlockId(rpssId, rbsId, runtimeOptions));

					// Comment Leo 04/12/2015: IMPORTANT: This extend below must be done always, even if `processblock-ifhasdata` condition below applies
					// and so we skip the merging, however the params must be set for later use. Eg: TPP Debate Create OpinionatedVoted block
					// skip-params: used for the loadLatest, so that the response of the params is not integrated back, messing up the paged, limit, etc
					$.extend(t.getBlockParams(rPageSection, rBlock, runtimeOptions), rbParams);

					// When loading content, we can say to re-draw the block if there is data to be drawn, and do nothing instead
					// This is needed for the "Add your Thought on TPP": if the user is not logged in, and writes a Thought, and then logs in,
					// then the Thought must not be overridden
					var process_actions = [M.CBACTION_LOADCONTENT, M.CBACTION_REFETCH];
					if (process_actions.indexOf(action) > -1) {

						var jsSettings = t.getJsSettings(rPageSection, rBlock);
						if (jsSettings['processblock-ifhasdata'] && !response.dataset[rpssId][rbsId].length) {
							return;	
						}
					}

					// And finally process the block
					t.processBlock(rPageSection, rBlock, processOptions);
				}
				catch(err) {
					// Do nothing
					// console.log(err.message);
					// console.trace();
				}
			});
		});

		block.triggerHandler('fetched', [response, action]);
	},

	triggerEvent : function(pageSection, block, event, args) {

		var t = this;

		// Trigger on this block
		block.triggerHandler(event, args);

		// If this block is subscribed to an Aggregator, also this one will do the trigger
		// var aggregatorData = t.getAggregatorBlockData(pageSection, block);
		// if (aggregatorData) {
		// 	target = $('#'+aggregatorData['id']);
		// 	target.triggerHandler(event);
		// }
	},

	// handleError : function(error, loadingUrl, jqXHR, textStatus, errorThrown) {

	// 	var t = this;

	// 	error.children('div.pop-box').html(t.getError(loadingUrl, jqXHR, textStatus, errorThrown));
	// 	error.removeClass('hidden');

	// 	// Scroll to the error
	// 	t.scrollTop(error);
	// },

	integrateTopLevelFeedback : function(response) {
	
		var t = this;
		var tlFeedback = t.getMemory().feedback.toplevel;

		// Integrate the response into the topLevelFeedback
		// Iterate all fields from the response topLevel. If it's an object, extend it. if not, just copy the value
		// This is done so that previously sent values (eg: lang, sent only on loading_frame()) are not overridden.
		// $.extend(t.memory.feedback.toplevel, response.feedback.toplevel);
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

	clearUserDatabase : function() {
	
		var t = this;

		// Executed when the logged-in user logs out
		t.userdatabase = {};
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

	integrateDatabases : function(response) {
	
		var t = this;

		t.integrateDatabase(t.database, response.database);
		t.integrateDatabase(t.userdatabase, response.userdatabase);
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

	generateUniqueId : function() {

		var t = this;

		// Create a new uniqueId
		var unique = Date.now();

		// assign it to the toplevel feedback
		var tlFeedback = t.getTopLevelFeedback();
		tlFeedback[M.UNIQUEID] = unique;

		return unique;
	},

	// restoreUniqueId : function() {

	// 	var t = this;

	// 	// restore the original uniqueId to the toplevel feedback
	// 	// This is needed for when replicating singlePageSections, so as to not lose the links to the fixed_id ids (which depend on the uniqueId)
	// 	var tlFeedback = t.getTopLevelFeedback();
	// 	tlFeedback[M.UNIQUEID] = tlFeedback[M.ORIGINALUNIQUEID];
	// },

	getUniqueId : function() {

		var t = this;

		var tlFeedback = t.getTopLevelFeedback();
		return tlFeedback[M.UNIQUEID];
	},

	addUniqueId : function(url) {

		var t = this;

		var unique = t.getUniqueId();
		return url+'#'+unique;
	},

	mergeTargetTemplate : function(pageSection, target, templateName, options) {
	
		var t = this;
		options = options || {};

		var rerender_actions = [M.CBACTION_LOADCONTENT, M.CBACTION_REFETCH, M.CBACTION_RESET];
		var rerender = rerender_actions.indexOf(options.action) > -1;
		if (rerender) {
			// When rerendering, create the unique-id again, since not all the components allow to re-create a new component with an already-utilized id (eg: editor.js)
			t.generateUniqueId();
		}
		
		var html = t.getTemplateHtml(pageSection, target, templateName, options);
		// If this is an Aggregator Subscribed Block, then change the target (only to embed the html content, html generated under the original templateBlock)
		// Is it a Subscribed Block? (does it have an Aggregator?)
		// var aggregatorData = t.getAggregatorBlockData(pageSection, target);
		// if (aggregatorData) {
		// 	target = $('#'+aggregatorData['id']);
		// }
		var targetContainer = t.getMergeTarget(target, templateName, options);

		// Default operation: REPLACE
		var operation = options.operation || M.URLPARAM_OPERATION_REPLACE;

		// Delete all children before appending?
		// if (operation == M.URLPARAM_OPERATION_REPLACE || operation == M.URLPARAM_OPERATION_UNIQUE) {
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

		// If unique, remove the pop-merge class, so nothing else will be appended to it
		// if (operation == M.URLPARAM_OPERATION_UNIQUE) {
		// 	targetContainer.removeClass('pop-merge pop-merge-target');
		// }

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

	renderPageSection : function(pageSection, options) {
	
		var t = this;
		options = options || {};

		$.extend(options, t.getFetchPageSectionSettings(pageSection));
		// var settings = t.getFetchPageSectionSettings(pageSection);
		// if (settings.options) {
		// 	$.extend(options, settings.options);
		// }

		var newDOMs = t.renderTarget(pageSection, pageSection, options);

		// Sometimes no newDOMs are actually produced. Eg: when calling /userloggedin-data
		// So then do not call pageSectionRendered, or it can make mess (eg: it scrolls up when /userloggedin-data comes back)
		if (newDOMs.length) {

			t.pageSectionRendered(pageSection, newDOMs, options);
		}
	},

	renderTarget : function(pageSection, target, options) {
	
		var t = this;

		options = options || {};

		// And having set-up all the handlers, we can trigger the handler
		target.triggerHandler('beforeMerge', [options]);

		var templates_cbs = t.getTemplatesCbs(pageSection, target, options.action);
		var targetContainers = $();
		var newDOMs = $();
		$.each(templates_cbs, function(index, templateName) {

			var merged = t.mergeTargetTemplate(pageSection, target, templateName, options);
			targetContainers = targetContainers.add(merged.targetContainer);
			newDOMs = newDOMs.add(merged.newDOMs);
		});

		t.triggerRendered(target, newDOMs, targetContainers);

		return newDOMs;
	},

	triggerRendered : function(target, newDOMs, targetContainers) {
	
		var t = this;

		target.triggerHandler('rendered', [newDOMs, targetContainers]);
		$(document).triggerHandler('rendered', [target, newDOMs, targetContainers]);
	},

	getPageSectionConfiguration : function(pageSection) {
	
		var t = this;
		
		var pssId = t.getSettingsId(pageSection);
		return t.getMemory().settings.configuration[pssId];
	},

	getTargetConfiguration : function(pageSection, target, template) {
	
		var t = this;
		var templatePath = t.getTemplatePath(pageSection, target, template);
		var targetConfiguration = t.getPageSectionConfiguration(pageSection);
		
		// Go down all levels of the configuration, until finding the level for the template-cb
		$.each(templatePath, function(index, pathLevel) {

			targetConfiguration = targetConfiguration[pathLevel];
		});

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

	// overrideWithValue : function(override, overrideWithValue) {
	
	// 	var t = this;
		
	// 	$.each(overrideWithValue, function(index, overrideItem) {	

	// 		override[overrideItem.key] = overrideItem.value;
	// 	});
	// },

	replaceFromItemObject : function(pssId, bsId, template, itemObject, override, strReplace) {
	
		var t = this;
		var feedback = t.getTopLevelFeedback();
		var targetConfiguration = t.getTargetConfiguration(pssId, bsId, template);
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
						// replaceFrom = replaceFrom.replace(replaceStr, itemObject[replaceWithField]);
					}
				}

				var replaceFromFeedback = replacement['replace-from-feedback'];
				if (replaceFromFeedback && replaceStr != feedback[replaceFromFeedback]) {
					replaceFrom = replaceFrom.replace(new RegExp(replaceStr, 'g'), feedback[replaceFromFeedback]);
				}

				// var replaceWithId = replacement['replace-with-id'];
				// if (replaceWithId) {
				// 	// var newId = popJSRuntimeManager.addTemplateId(pssId, bsId, template, template, id);
				// 	var newId = popJSRuntimeManager.addTemplateId(pssId, bsId, template, null, id);
				// 	replaceFrom = replaceFrom.replace(new RegExp(replaceStr, 'g'), newId);
				// }
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

	// overrideConfiguration : function(pageSection, target, templateName, newConfiguration) {
	
	// 	var t = this;
		
	// 	var targetConfiguration = t.getTargetConfiguration(pageSection, target, templateName);
	// 	$.extend(targetConfiguration, newConfiguration);
	// },

	getTemplateHtml : function(pageSection, target, templateName, options, itemDBKey, itemObjectId) {

		var t = this;
		var targetConfiguration = t.getTargetConfiguration(pageSection, target, templateName);
		options = options || {};

		targetContext = targetConfiguration;

		// If merging a subcomponent (eg: appending data to Carousel), then we need to recreate the block Context
		var templatePath = t.getTemplatePath(pageSection, target, templateName);
		if (templatePath.length) {
			var block = t.getBlock(target);
			// var pssId = t.getSettingsId(pageSection);
			// var bsId = t.getSettingsId(block);
			// var psId = pageSection.attr('id');
			// var bId = block.attr('id');
			// t.extendContext(targetContext, pssId, bsId, psId, bId, itemDBKey, itemObjectId);
			t.initContextSettings(pageSection, block, targetContext);
			t.extendContext(targetContext, itemDBKey, itemObjectId);
		}

		// $.extend(targetContext, extendContext);
		// extendContext: don't keep the overriding in the configuration. This way, we can use the replicate without having to reset
		// the configurations to an original value (eg: for featuredImage, it copies the img on the settings)
		var extendContext = options.extendContext;
		if (extendContext) {
			targetContext = $.extend({}, targetContext, extendContext);
		}
		return t.getHtml(templateName, targetContext);
	},

	extendContext : function(context, itemDBKey, itemObjectId, override) {

		// If merging a subcomponent (eg: appending data to Carousel), then we need to recreate the block Context
		// Also used from within function enterModules to create the context to pass to each module
		var t = this;
		override = override || {};
		$.extend(context, override);

		// Load itemObject?
		if (itemDBKey) {

			$.extend(context, {itemDBKey: itemDBKey});
			if (itemObjectId) {

				var itemObject = t.getItemObject(itemDBKey, itemObjectId);
				$.extend(context, {itemObject: itemObject});
			}
		}
	},

	initTopLevelJson : function() {
	
		var t = this;

		// Initialize Settings
		var jsonHtml = $('#'+popPageSectionManager.getTopLevelSettingsId());
		var json = $.parseJSON(jsonHtml.html());

		t.database = json.database;
		t.userdatabase = json.userdatabase;

		var memory = t.getMemory();
		memory.settings = json.settings;
		memory.runtimesettings = json.runtimesettings;
		memory.dataset = json.dataset;
		memory.feedback = json.feedback;
		memory.params = json.params;

		// Comment Leo 12/06/2016: Not needed, since we can't merge query-domains into M.ALLOWED_URLS because not all query-domains are initially loaded, so for those not loaded the "click" will not be intercepted
		//// Add all the external domains, taken from the settings, into M.ALLOWED_URLS
		// if (json.settings['query-domains'].length) {
		// 	M.ALLOWED_URLS = M.ALLOWED_URLS.concat(json.settings['query-domains']);
		// }

		// Dataset, feedback and Params: the PHP will write empty objects as [] instead of {}, so they will be treated as arrays
		// This will create problems when doing $.extend(), so convert them back to objects
		// ---------------------------------------
		$.each(memory.dataset, function(pssId, psDataset) {
			if ($.type(psDataset) == 'array') {
				memory.dataset[pssId] = {};
			}
		});
		$.each(memory.params, function(pssId, psParams) {
			if ($.type(psParams) == 'array') {
				memory.params[pssId] = {};
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

		// After initializing memory and the toplevel, restore the uniqueId from the settings
		// t.maybeRestoreUniqueId(memory);
	},

	getBlockDefaultParams : function() {
	
		var t = this;

		// var params = t.getDefaultParams();
		return {
			url: [],
			loading: [],
			reload: [],
			operation: {},
			action: {},
			'paramsscope-url': {},
			'post-data': '',
			// Filter params are actually initialized in setFilterParams function. 
			// That is because a filter knows its templateBlock, but a templateBlock not its filter
			// (eg: in the sidebar)
			filter: ''
		};
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
	
	initPageSectionRuntimeMemory : function(pageSection, options) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var runtimeMempage = t.newRuntimeMemoryPage(pageSection, pageSection, options);

		var pssId = t.getSettingsId(pageSection);
		runtimeMempage.params = t.getPageSectionDefaultParams();
		// runtimeMempage.id = pageSection.attr('id');

		// Allow JS libraries to hook up and initialize their own params
		var args = {
			pageSection: pageSection,
			runtimeMempage: runtimeMempage
		};
		popJSLibraryManager.execute('initPageSectionRuntimeMemory', args);
	},
	initBlockRuntimeMemory : function(pageSection, block, options) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var runtimeMempage = t.newRuntimeMemoryPage(pageSection, block, options);

		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		runtimeMempage.params = $.extend(t.getBlockDefaultParams(), t.getMemory().params[pssId][bsId]);
		runtimeMempage['query-url'] = t.getRuntimeSettings(pageSection, block, 'query-url');
		runtimeMempage.id = block.attr('id');

		// Allow JS libraries to hook up and initialize their own params
		var args = {
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

	getTemplateSource : function(template) {
	
		// If empty, then the template is its own source
		var t = this;		
		// return popMemory.settings['template-sources'][template] || template;
		return t.getMemory().settings['template-sources'][template] || template;
	},

	// expandConfigurationJSKeys : function(configuration) {
	
	// 	var t = this;

	// 	// In order to save file size, context keys where compressed, eg: modules => m. However they might be referenced with their full name
	// 	// in .tmpl files, so reconstruct the full name in the context duplicating these entries
	// 	if (M.COMPACT_JS_KEYS) {
			
	// 		configuration.template = configuration[M.JS_TEMPLATE];
	// 		if (configuration[M.JS_MODULES]) {
	// 			configuration.modules = configuration[M.JS_MODULES];
	// 		}
	// 		if (configuration[M.JS_SETTINGSIDS]) {
	// 			configuration['settings-ids'] = configuration[M.JS_SETTINGSIDS];
	// 		}
	// 		if (configuration[M.JS_BLOCKSETTINGSIDS]) {
	// 			configuration['block-settings-ids'] = configuration[M.JS_BLOCKSETTINGSIDS];
	// 		}
	// 	}	
	// }

	initPageSectionSettings : function(pageSection, psConfiguration) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var tls = t.getTopLevelSettings();
		$.extend(psConfiguration, {tls: tls});

		var pss = t.getPageSectionSettings(pageSection);
		var pssId = t.getSettingsId(pageSection);
		var psId = psConfiguration[M.JS_FRONTENDID];//psConfiguration['frontend-id'];
		// $.extend(psConfiguration, {pssId: pssId, pss: pss});		
		$.extend(psConfiguration, {pss: pss});	

		// Expand the JS Keys for the configuration
		t.expandJSKeys(psConfiguration);

		// Fill each block configuration with its pssId/bsId/settings
		if (psConfiguration[M.JS_MODULES]/*psConfiguration.modules*/) {
			$.each(psConfiguration[M.JS_MODULES]/*psConfiguration.modules*/, function(bsId, bConfiguration) {
				
				var bId = bConfiguration[M.JS_FRONTENDID];//bConfiguration['frontend-id'];
				var bs = t.getBlockSettings(pssId, bsId, psId, bId);
				$.extend(bConfiguration, {tls: tls, pss: pss, bs: bs});	

				// Expand the JS Keys for the configuration
				t.expandJSKeys(bConfiguration);
			});	
		}
	},

	initContextSettings : function(pageSection, block, context) {
	
		// Initialize TopLevel / Blocks from the info provided in the feedback
		var t = this;

		var tls = t.getTopLevelSettings();
		$.extend(context, {tls: tls});

		var pss = t.getPageSectionSettings(pageSection);
		$.extend(context, {pss: pss});	

		var pssId = t.getSettingsId(pageSection);
		var psId = pageSection.attr('id');
		var bsId = t.getSettingsId(block);
		var bId = block.attr('id');
		var bs = t.getBlockSettings(pssId, bsId, psId, bId);
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

	integratePageSection : function(response) {
	
		var t = this;

		var memory = t.getMemory();
		$.extend(memory.settings['template-sources'], response.settings['template-sources']);
		$.each(response.settings.configuration, function(pssId, rpsConfiguration) {

			$.extend(memory.params[pssId], response.params[pssId]);
			$.extend(memory.dataset[pssId], response.dataset[pssId]);
			$.extend(memory.feedback.pagesection[pssId], response.feedback.pagesection[pssId]);
			$.extend(memory.feedback.block[pssId], response.feedback.block[pssId]);

			$.extend(memory.runtimesettings['query-url'][pssId], response.runtimesettings['query-url'][pssId]);
			$.extend(memory.runtimesettings.configuration[pssId], response.runtimesettings.configuration[pssId]);
			// $.extend(memory.settings['query-url'][pssId], response.settings['query-url'][pssId]);
			$.extend(memory.settings['js-settings'][pssId], response.settings['js-settings'][pssId]);
			$.extend(memory.settings.jsmethods.pagesection[pssId], response.settings.jsmethods.pagesection[pssId]);
			$.extend(memory.settings.jsmethods.block[pssId], response.settings.jsmethods.block[pssId]);
			$.extend(memory.settings['templates-cbs'][pssId], response.settings['templates-cbs'][pssId]);
			$.extend(memory.settings['templates-paths'][pssId], response.settings['templates-paths'][pssId]);
			// $.extend(memory.settings['aggregator-subscribed-data'][pssId], response.settings['aggregator-subscribed-data'][pssId]);
			// $.extend(memory.settings['proxy-domain'][pssId], response.settings['proxy-domain'][pssId]);
			$.extend(memory.settings['db-keys'][pssId], response.settings['db-keys'][pssId]);

			// Configuration: first copy the modules, and then the 1st level configuration => pageSection configuration
			// This is a special case because the blocks are located under 'modules', so doing $.extend will override the existing modules in 'memory', however we want to keep them
			if (!memory.settings.configuration[pssId]) {
				memory.settings.configuration[pssId] = {};
			}
			var psConfiguration = memory.settings.configuration[pssId];
			$.each(rpsConfiguration, function(key, value) {

				// If it is an array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
				// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
				if ($.type(value) == 'array') {
					psConfiguration[key] = {};
				}
				else if ($.type(value) == 'object') {
					// If it is an object, extend it. If not, just assign the value
					if (!psConfiguration[key]) {
						psConfiguration[key] = {};
					}
					$.extend(psConfiguration[key], value);
				}
				else {
					psConfiguration[key] = value;
				}
			});

			var psId = rpsConfiguration[M.JS_FRONTENDID];//rpsConfiguration['frontend-id'];
			var pageSection = $('#'+psId);
			t.initPageSectionSettings(pageSection, psConfiguration);
		});
	},

	getTopLevelSettings : function() {
	
		var t = this;

		return {
			feedback: t.getTopLevelFeedback()
		};
	},

	getPageSectionSettings : function(pageSection) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var psId = pageSection.attr('id');

		var pageSectionSettings = {
			feedback: t.getPageSectionFeedback(pageSection),
			pssId: pssId,
			psId: psId
		};

		return pageSectionSettings;
	},

	getBlockSettings : function(pssId, bsId, psId, bId) {
	
		var t = this;
		var blockSettings = {
			"db-keys": t.getDatabaseKeys(pssId, bsId),
			dataset: t.getBlockDataset(pssId, bsId),
			feedback: t.getBlockFeedback(pssId, bsId),
			bsId: bsId,
			bId: bId,
		};

		// t.expandJSKeys(blockSettings);
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

	getBlockDataset : function(pageSection, block) {
	
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		
		return t.getMemory().dataset[pssId][bsId];
	},

	getBlockFeedback : function(pageSection, block) {
	
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		
		return t.getMemory().feedback.block[pssId][bsId];
	},

	getPageSectionFeedback : function(pageSection) {
	
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		return t.getMemory().feedback.pagesection[pssId];
	},

	getTopLevelFeedback : function() {
	
		var t = this;
		
		return t.getMemory().feedback.toplevel;
	},

	getSettings : function(pageSection, target, item) {
	
		var t = this;
		
		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(target);
		var memory = t.getMemory();

		return memory.settings[item][pssId][targetId];
	},
	getRuntimeSettings : function(pageSection, target, item) {
	
		var t = this;
		
		var pssId = t.getSettingsId(pageSection);
		var targetId = t.getSettingsId(target);
		var memory = t.getMemory();
		
		return memory.runtimesettings[item][pssId][targetId];
	},

	getQueryUrl : function(pageSection, block) {
	
		var t = this;
		return t.getRuntimeMemoryPage(pageSection, block)['query-url'];
	},

	getRuntimeConfiguration : function(pageSection, block, el) {

		var t = this;

		// When getting the block configuration, there's no need to pass el param
		el = el || block;
		
		var elsId = t.getTemplateOrObjectSettingsId(el);
		var configuration = t.getRuntimeSettings(pageSection, block, 'configuration');

		return configuration[elsId] || {};
	},

	getDatabaseKeys : function(pageSection, block) {
	
		var t = this;
		return t.getSettings(pageSection, block, 'db-keys');
	},

	getBlockFilteringUrl : function(pageSection, block, use_pageurl) {
	
		var t = this;
		var url = t.getQueryUrl(pageSection, block);

		// If the block doesn't have a filtering url (eg: the Author Description, https://www.mesym.com/p/leo/?tab=description) then use the current browser url
		if (!url && use_pageurl) {
			// url = window.location.href;
			url = t.getTopLevelFeedback()[M.URLPARAM_URL];
		}

		// Add only the 'visible' params to the URL
		var post_data = t.getBlockPostData(pageSection, block, {paramsGroup: 'visible'});
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
		container = container || $('body');
		
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
		// url = add_query_arg(M.URLPARAM_OUTPUT, M.URLPARAM_OUTPUT_JSON, url);
		// url = add_query_arg(M.URLPARAM_MODULE, M.URLPARAM_MODULE_DATA, url);
		// url = add_query_arg(M.URLPARAM_JSONOUTPUT, M.URLPARAM_JSONOUTPUT_ORIGINAL, url);

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

		// Comment Leo 10/06/2016: The URL can start with other domains, for the Platform of Platforms
		var domain = M.HOME_URL;
		$.each(M.ALLOWED_URLS, function(index, allowed) {

			if(url.startsWith(allowed)) {

				domain = allowed;
				return -1;
			}
		});
		
		// Comment Leo 28/10/2015: Use this URL instead of !destroy because
		// this bit gets stripped off when doing removeParams(url) to get the interceptors, however it is still needed
		return url.replace(domain, domain+'/destroy');
		// return url+'!destroy';
	},

	// getProxyDomain : function(pageSection, block) {
	
	// 	var t = this;
	// 	return t.getSettings(pageSection, block, 'proxy-domain');
	// },

	// getAggregatorSubscribedBlocksData : function(pageSection, block) {
	
	// 	var t = this;
		
	// 	var pssId = t.getSettingsId(pageSection);
	// 	var bsId = t.getSettingsId(block);
	// 	var memory = t.getMemory();

	// 	return memory.settings['aggregator-subscribed-data'][pssId]['aggregators'][bsId];
	// },

	// getAggregatorBlockData : function(pageSection, block) {
	
	// 	var t = this;

	// 	var pssId = t.getSettingsId(pageSection);
	// 	var bsId = t.getSettingsId(block);
	// 	var memory = t.getMemory();

	// 	return memory.settings['aggregator-subscribed-data'][pssId]['subscribed'][bsId];
	// },
	
	getTemplateOrObjectSettingsId : function(el) {

		var t = this;
		
		// If it's an object, return an attribute	
		if ($.type(el) == 'object') {

			return el.data('templateid');
		}

		// String was passed, return it
		return el;
	},

	getPageSectionJsSettings : function(pageSection) {
	
		// This is a special case
		var t = this;
		var pssId = t.getSettingsId(pageSection);
		
		return t.getSettings(pageSection, pageSection, 'js-settings') || {};
	},
	getJsSettings : function(pageSection, block, el) {

		var t = this;

		// When getting the block settings, there's no need to pass el param
		el = el || block;
		
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var jsSettingsId = t.getTemplateOrObjectSettingsId(el);
		var settings = t.getSettings(pageSection, block, 'js-settings');

		return settings[jsSettingsId] || {};

	},
	getPageSectionJsMethods : function(pageSection) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var memory = t.getMemory();
		return memory.settings['jsmethods']['pagesection'][pssId] || {};
	},
	getBlockJsMethods : function(pageSection, block) {
	
		var t = this;

		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var memory = t.getMemory();

		return memory.settings['jsmethods']['block'][pssId][bsId] || {};
	},
	restoreInitialBlockMemory : function(pageSection, block, options) {

		var t = this;
		var pssId = t.getSettingsId(pageSection);
		var bsId = t.getSettingsId(block);
		var url = block.data('paramsscope-url');

		var initialMemory = t.getInitialBlockMemory(url);
		t.getRuntimeMemoryPage(pageSection, block, options).params = $.extend(t.getBlockDefaultParams(), initialMemory.params[pssId][bsId]);
		t.getMemory().runtimesettings.configuration[pssId][bsId] = $.extend({}, initialMemory.runtimesettings.configuration[pssId][bsId]);
		t.getMemory().feedback.block[pssId][bsId] = $.extend({}, initialMemory.feedback.block[pssId][bsId]);

		// If the initialMemory dataset is empty and the memory one is not, then the extend fails to override
		// So ask for that case explicitly
		var dataset = initialMemory.dataset[pssId][bsId];
		if (dataset.length) {
			$.extend(t.getMemory().dataset[pssId][bsId], dataset);
		}
		else {
			t.getMemory().dataset[pssId][bsId] = [];
		}
	},
	// restoreInitialRuntimeSettings : function(pageSection, block, options) {

	// 	var t = this;
	// 	var pssId = t.getSettingsId(pageSection);
	// 	var bsId = t.getSettingsId(block);
	// 	var url = block.data('paramsscope-url');
	// 	console.log(url, t.getInitialBlockMemory()[url]);

	// 	var initialMemory = t.getInitialBlockMemory()[url];
	// 	var configuration = t.getRuntimeSettings(pageSection, block, 'configuration');
	// 	$.extend(configuration[bsId], initialMemory.runtimesettings.configuration[pssId][bsId]);
	// },
	
	getBlockParams : function(pageSection, block, options) {
	
		var t = this;

		return t.getRuntimeMemoryPage(pageSection, block, options).params;
	},
	getPageSectionParams : function(pageSection, options) {
	
		var t = this;

		return t.getRuntimeMemoryPage(pageSection, pageSection, options).params;
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
	
	getTemplatesCbs : function(pageSection, target, action) {
	
		var t = this;

		action = action || 'main';

		// var cbs = popMemory.settings['templates-cbs'][pssId][targetId].cbs;
		// var actions = popMemory.settings['templates-cbs'][pssId][targetId].actions;
		var templatesCbs = t.getSettings(pageSection, target, 'templates-cbs');
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

	getTemplatePath : function(pageSection, target, template) {
	
		var t = this;
		
		var templatePaths = t.getSettings(pageSection, target, 'templates-paths');
		return templatePaths[template];
	},
	
	getScriptTemplate : function(templateName) {

		var t = this;
		var templateSource = t.getTemplateSource(templateName);
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
	
	getHtml : function(templateName, context) {

		var t = this;	
		var template = t.getScriptTemplate(templateName);

		// Comment Leo 29/11/2014: some browser plug-ins will not allow the template to be created
		// Eg: AdBlock Plus. So when that happens (eg: when requesting template "socialmedia-source") template is undefined
		// So if this happens, then just return nothing
		if (typeof template == 'undefined') {
			console.error('No template for ' + templateName);
			return '';
		}

		return template(context);		
	},

	getItemObject : function(itemDBKey, itemObjectId) {

		var t = this;
		var userItem = {}, item = {};
		if (t.userdatabase[itemDBKey] && t.userdatabase[itemDBKey][itemObjectId]) {
			userItem = t.userdatabase[itemDBKey][itemObjectId];
		}
		if (t.database[itemDBKey] && t.database[itemDBKey][itemObjectId]) {
			item = t.database[itemDBKey][itemObjectId];
		}
		return $.extend({}, userItem, item);

		// if (!t.userdatabase[itemDBKey][itemObjectId]) {
		// 	t.userdatabase[itemDBKey][itemObjectId] = {};
		// }
		// return $.extend(t.userdatabase[itemDBKey][itemObjectId], t.database[itemDBKey][itemObjectId]);
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