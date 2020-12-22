"use strict";
// This function are called as a callback to load_scripts, so they lose the context of who 't' is
// So then place them outside the pop.SPAResourceLoader structure
function markJSResourceAsLoaded(url, state) {

	pop.SPAResourceLoader.markJSResourceAsLoaded(url, state);
}
function markResourceAsLoaded(url, state) {

	pop.SPAResourceLoader.markResourceAsLoaded(url, state);
}

(function($){
window.pop.SPAResourceLoader = {

	// The values here will be populated from sparesourceloader-config.js,
	// on a domain by domain basis
	config : {},

	// Keep a list of all loading resources
	loading : [],
	'error-loading' : [],
	'error-loading-times' : {},
	'loading-urls' : {},
	'loading-resources' : {},

	// Keep a list of all loaded resources. All resources are called always the same among different domains,
	// so one list here listing all of them works
	loaded : [],
	'loaded-in-body' : [],
	// Loaded bundles and bundleGroups depend on their domains, since their names change among domains
	'loaded-by-domain' : {},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	preFetchPageSection : function(args) {
	
		var that = this;
		var url = args.url, options = args.options;

		// Make sure we have the config files saying what resources are needed for that URL
		var loaded = that.configFilesLoaded(url, options);

		// Once we have the config files loaded, we can load the resources needed for that URL
		if (loaded) {

			that.loadResourcesForURL(url);
		}
		else {

			var configFile = that.loadURLConfigFile(url);
			$(document).one('loaded-url:'+escape(configFile), function(e) {
				that.loadResourcesForURL(url);
			});
		}
	},

	// areResourcesLoaded : function(args) {

	// 	var that = this;

	// 	if (pop.c.USECODESPLITTING) {
			
	// 		args.loaded = that.areResourcesLoadedForURL(args.fetchUrl);
	// 	}
	// },

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	loadScriptOrStyle : function(type, source) {

		var that = this;
		if (type == pop.c.SPARESOURCELOADER.TYPES.CSS) {
			
			load_style(source);
		}
		else if (type == pop.c.SPARESOURCELOADER.TYPES.JS) {

			load_script(source);
		}
	},

	onRemoveLoadResource : function(blockId, type, source) {

		var that = this;
		var block = $("#"+blockId);

		// If destroying the pageSectionPage, the corresponding 'in-body' styles will also be deleted, and other pages using those styles will be affected.
		// Then, simply load again those removed resources (scripts and styles)
		var pageSectionPage = pop.Manager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {

			that.loadScriptOrStyle(type, source);
		});

		// Similar, if the style/script was added in an element inside a list (eg: a layout inside a post list),
		// and the block content gets replaced (eg: when filtering inside the block), then the style/script must be linked to
		block.one('replace', function() {

			that.loadScriptOrStyle(type, source);
		});
	},

	includeResources : function(domain, blockId, resources, ignoreAlreadyIncluded) {

		var that = this;
		if (!resources) {

			return '';
		}

		var config = that.getConfigByDomain(domain);
		var body_resources = [];

		// Remove the resources that have been included already
		if (ignoreAlreadyIncluded) {
			
			// Comment Leo 23/11/2017: if a component is lazy-loaded, and inside has a CSS file that is printed in the body,
			// then we must check if that resource has been added to the body. (It will be already marked as "loaded" by the website,
			// but it never was actually because of the lazy-loading)
			body_resources = $(resources).not(that['loaded-in-body']).get();

			resources = $(resources).not(that.loaded).get();
		}

		// Mark the resources as already included
		that.loaded = that.loaded.concat(resources);


		that['loaded-in-body'] = that['loaded-in-body'].concat(body_resources);
		resources = body_resources;

		// Map the resources to their tags
		var tags = resources.map(function(resource) {

			// If destroying the pageSectionPage, the corresponding 'in-body' styles will also be deleted, and other pages using those styles will be affected.
			// Then, simply load again those removed resources (scripts and styles)
			var source = config.sources[resource];
			var fn = '<script type="text/javascript">jQuery(document).ready( function($) { pop.SPAResourceLoader.onRemoveLoadResource("{0}", "{1}", "{2}"); });</script>';
			if (config.types[pop.c.SPARESOURCELOADER.TYPES.CSS].indexOf(resource) >= 0) {

				var script = fn.format(blockId, pop.c.SPARESOURCELOADER.TYPES.CSS, source);
				var tag = '<link rel="stylesheet" href="{0}">'.format(source);
				return script+tag;
			}
			else if (config.types[pop.c.SPARESOURCELOADER.TYPES.JS].indexOf(resource) >= 0) {
				
				var script = fn.format(blockId, pop.c.SPARESOURCELOADER.TYPES.JS, source);
				var tag = '<script type="text/javascript" src="{0}"></script>'.format(source);
				return script+tag;
			}
			// var type = config.types[resource];
			// if (type == pop.c.SPARESOURCELOADER.TYPES.CSS) {
			// 	return '<link rel="stylesheet" href="{0}">'.format(source);
			// }
			// else if (type == pop.c.SPARESOURCELOADER.TYPES.JS) {
			// 	return '<script type="text/javascript" src="{0}"></script>'.format(source);
			// }
			return '';
		});

		return tags.join('');
	},

	markJSResourceAsLoaded : function(url, state) {

		var that = this;

		if (state == 'ok') {

			// From the URL, get the corresponding resource
			var resource = that['loading-urls'][url];

			// Assign the loaded libraries (when calling "register") to this resource
			pop.CodeSplitJSLibraryManager.assignLibrariesToResource(resource);
		}
		
		that.markResourceAsLoaded(url, state);
	},

	markResourceAsLoaded : function(url, state) {

		var that = this;

		// From the URL, get the corresponding resource
		var resource = that['loading-urls'][url];

		// Only mark it as loaded if the state is "ok" and not "error"
		if (state == 'ok') {

			that.loaded.push(resource);
			that['loaded-in-body'].push(resource);
		}
		else if (state == 'error') {
			that['error-loading'].push(resource);

			// Keep how many times it failed. When it reaches 3, quit trying
			var times = that['error-loading-times'][resource] || 0;
			that['error-loading-times'][resource] = times+1;
		}

		// Remove from the loading list, no need anymore
		that.loading.splice(that.loading.indexOf(resource), 1);
		delete that['loading-urls'][url];
	},

	loadURLConfigFile : function(url) {

		var that = this;

		var configFile = that.getConfigFile(url);
		load_script(configFile);

		return configFile;
	},

	configFilesLoaded : function(url, options) {

		var that = this;
		var config = that.getConfig(url);
		// if (!config) {
		// 	return;
		// }

		// If file sparesourceloader-config-resources.js has been loaded, i.e. all configs have been loaded, then return true
		if (config.loaded) {
			return true;
		}

		// Check if the path has already been loaded (eg: initially set for backgroundLoad or Loggedin-user-data)
		var urlProperties = that.getPropertiesForURL(url);
		var nature = urlProperties.nature;
		var path = urlProperties.path;

		// First check if the combination of nature => path has been set
		var path_natures = ['page', 'single'];
		if (path_natures.indexOf(nature) >= 0) {

			if (config['loaded-paths'][nature] && config['loaded-paths'][nature].indexOf(path) >= 0) {

				return true;
			}
		}

		// If not, check if the smaller "nature-format" config file was loaded
		var configFile = that.getConfigFile(url);

		return config['loaded-files'].indexOf(configFile) >= 0;
	},

	getConfigFile : function(url) {

		var that = this;
		
		var config = that.getConfig(url);
		var urlProperties = that.getPropertiesForURL(url);
		var nature = urlProperties.nature;
		var format = urlProperties.format;
		return config['configfile-urlplaceholder'].format(nature, format);
	},

	getPropertiesForURL : function(url) {

		var that = this;
		// if (!config) {
		// 	return;
		// }

		var path = pop.Utils.getPath(url);
		var nature = that.getHierarchy(url);
		var format = getParam(pop.c.URLPARAM_FORMAT, url) || pop.c.VALUES_DEFAULT;

		return {
			"nature": nature,
			"format": format,
			"path": path,
		};
	},

	// isSinglePath : function(path, single_path) {

	// 	var that = this;
	// 	return path.startsWith(single_path) && path != single_path;
	// },

	getConfigByDomain : function(domain) {

		var that = this;

		// Check we have a config for this domain
		var config = that.config[domain];
		if (!config && domain != pop.c.HOME_DOMAIN) {

			// If we don't have a config, and the domain is not local, then try the local domain
			// (This is needed for if the external sparesourceloader-config.js file has not been loaded yet. 
			// This may happen often, as loading this file is asynchronous, so needing to check the URL path
			// will happen before the script is loaded)
			config = that.config[pop.c.HOME_DOMAIN];
		}
		
		return config || {};
	},

	getConfig : function(url) {

		var that = this;

		var domain = getDomain(url);
		return that.getConfigByDomain(domain);
	},

	getHierarchy : function(url) {

		var that = this;

		// Check we have a config for this domain
		var config = that.getConfig(url);
		// if (!config) {
		// 	return;
		// }

		// // Remove the domain and locale from the URL
		// // var partialURL = url.substr((pop.c.HOMELOCALE_URL+'/').length);
		// var noParamsURL = removeParams(url);
		// // var isHome = (noParamsURL == pop.c.HOME_DOMAIN || noParamsURL == pop.c.HOME_DOMAIN+'/' || noParamsURL == pop.c.HOMELOCALE_URL || noParamsURL == pop.c.HOMELOCALE_URL+'/');

		// Comment Leo 29/09/2017: using config['path-start-pos'] doesn't work for when using the local config for an external URL,
		// so then we just use a function to calculate it
		// var path = noParamsURL.substr(config['path-start-pos']);
		var path = pop.Utils.getPath(url);
		
		// Home
		// if (path == pop.c.HOME_DOMAIN || path == pop.c.HOME_DOMAIN+'/' || path == pop.c.HOMELOCALE_URL || path == pop.c.HOMELOCALE_URL+'/') {
		if (!path) {

			// If there is no path, then it's either the Home or the preview (eg: https://demo.getpop.org/en/?p=23764&preview=1&_ppp=4171b1a992)
			return 'home';
		}
		// Author:
		if (path.startsWith(config.paths.author) && path != config.paths.author) {

			return 'author';
		}
		// Tag
		if (path.startsWith(config.paths.tag) && path != config.paths.tag) {

			return 'tag';
		}

		// Single Post
		// The code below doesn't work in UglifyJS!
		// if (config.paths.single.some(single_path => path.startsWith(single_path) && path != single_path/*that.isSinglePath(path, single_path)*/)) {
		// Watch out! We have pages posts/ and posts/articles/, then using the logic below, to check for page "posts/articles/" will also think it's single, because posts/ is a substring of posts/articles/!
		// return path.startsWith(single_path) && path != single_path
		// So then, we must also check that this path is, itself, not a potential page!
		if (config.paths.single.indexOf(path) === -1 && config.paths.single.some(function(single_path) { return path.startsWith(single_path) && path != single_path /*that.isSinglePath(path, single_path)*/;})) {

			return 'single';
		}
		// IMPORTANT: Place the Pages conditional only now, after Author/Tag/Single, because their paths will start with a page path (eg: /posts/this-is-a-post/ for single, /posts/ for the list)
		// If it's none of the above, then it's a page
		return 'page';
	},

	getResourcesDB : function(url) {

		var that = this;

		// Check we have a config for this domain
		var config = that.getConfig(url);

		// Check that if the config has not been initialized, then nothing to do
		if (!config || !config.resources) {

			return {};
		}

		var nature = that.getHierarchy(url);		
		var path = pop.Utils.getPath(url);

		// The resources are placed under the nature key in object config.resources.js
		var resourcesDB = config.resources[nature];

		// When initializing multidomain components, the config may not be loaded yet, so there will be no DB yet
		if (!resourcesDB) {

			return {};
		}

		// // If we are requesting an external URL, and the config for that external domain is still not loaded, then resourcesDB will be null
		// // Eg: when loading: https://sukipop.com/en/external/?url=https%3A%2F%2Fwww.mesym.com%2Fen%2Fevents%2Fmindset-public-talk-maintaining-peopled-forests-by-joe-fragoso-and-kamal-s-fadzil%2F
		// if (resourcesDB) {

		// single and page are not flat, but their resources configuration comes under the path
		if (nature == 'single') {

			// Recover which was the path that made the criteria succeed, the configuration is placed under that one
			// The code below doesn't work in UglifyJS!
			// var paths = single_paths.filter(single_path => path.startsWith(single_path) && path != single_path/*that.isSinglePath(path, single_path)*/);
			var paths = config.paths.single.filter(function(single_path) { return path.startsWith(single_path) && path != single_path /*that.isSinglePath(path, single_path)*/;});
			resourcesDB = resourcesDB[paths[0]];
		}
		else if (nature == 'page') {

			resourcesDB = resourcesDB[path];
		}
		// }

		// Allow Public Post Preview to hook in when previewing a post, to change the DB from home to the single post
		var args = {
			url: url,
			path: path,
			config: config,
			resourcesDB: resourcesDB,
		};
		pop.JSLibraryManager.execute('loadResourcesDB', args);
		resourcesDB = args.resourcesDB;

		return resourcesDB || {};
	},

	getURLComponents : function(url) {

		var that = this;

		var params = [];
		// This code is replicated in function `add_resources_from_current_vars` in class `PoP_SPAResourceLoaderProcessorUtils`
		var target = getParam(pop.c.URLPARAM_TARGET, url) || pop.c.URLPARAM_TARGET_MAIN;
		var format = getParam(pop.c.URLPARAM_FORMAT, url) || pop.c.VALUES_DEFAULT;
		var route = getParam(pop.c.URLPARAM_ROUTE, url) || '';
		if (format) {
			params.push(pop.c.CODESPLITTING.PREFIXES.FORMAT+format);
		}
		if (route) {
			params.push(pop.c.CODESPLITTING.PREFIXES.ROUTE+route);
		}
		if (target) {
			params.push(pop.c.CODESPLITTING.PREFIXES.TARGET+target);
		}
		var key = params.join(pop.c.SEPARATOR_SPARESOURCELOADER);

		return {
			params: params,
			target: target,
			format: format,
			route: route,
			key: key,
		};
	},

	initLoadedByDomain : function(domain) {

		var that = this;
		if (!that['loaded-by-domain'][domain]) {
			
			that['loaded-by-domain'][domain] = {
				bundles: [], 
				'bundle-groups': [],
			};
		}
	},

	loadResourcesForURL : function(url) {

		var that = this;

		// Check we have a config for this domain
		var config = that.getConfig(url);
		// if (!config) {
		// 	return;
		// }
		
		var resourcesDB = that.getResourcesDB(url);
		var urlComponents = that.getURLComponents(url);

		var keyId = config.keys[urlComponents.key];

		if (keyId && resourcesDB[keyId]) {

			var domain = getDomain(url);
			that.initLoadedByDomain(domain);
			
			// Load all the resources using JS		
			var bundleGroupIds = resourcesDB[keyId];

			$.each(bundleGroupIds, function(index, bundleGroupId) {

				// Check if that bundleGroup has been loaded or loading. If so, do nothing
				if (that['loaded-by-domain'][domain]['bundle-groups'].indexOf(bundleGroupId) == -1/* || that.loading['bundle-groups'].indexOf(bundleGroupId) == -1*/) {

					// // Mark the bundleGroup as loading from now on
					// that.loading['bundle-groups'].push(bundleGroupId);

					var bundleIds = config['bundle-groups'][bundleGroupId] || [];

					// Filter out the bundleIds that have already been loaded or loading
					bundleIds = $(bundleIds).not(that['loaded-by-domain'][domain].bundles).get();
					// bundleIds = $(bundleIds).not(that.loading.bundles).get();

					$.each(bundleIds, function(index, bundleId) {

						// Check if that bundle has been loaded or loading. If so, do nothing
						if (that['loaded-by-domain'][domain].bundles.indexOf(bundleId) == -1/* || that.loading.bundles.indexOf(bundleId) == -1*/) {

							// // Mark the bundle as loaded from now on
							// that.loading.bundles.push(bundleId);

							var resources = config.bundles[bundleId];

							// Filter out the resources that have already been loaded or loading
							resources = $(resources).not(that.loaded).get();
							resources = $(resources).not(that.loading).get();

							$.each(resources, function(index, resource) {

								that.loadResource(url, config, resource);
							});
						}
					});
				}
			});
		}
		// If it couldnt' find a DB for the current URL, and the target is not main,
		// then check for a configuration using the main target
		else if (urlComponents.target != pop.c.URLPARAM_TARGET_MAIN) {

			// Remove parameter target from the URL, and try again
			url = removeQueryFields(url, [pop.c.URLPARAM_TARGET]);
			that.loadResourcesForURL(url);
		}
	},

	loadResource : function(url, config, resource) {

		var that = this;		

		that['loading-resources'][url] = that['loading-resources'][url] || [];
		if (that['loading-resources'][url].indexOf(resource) == -1) {
			that['loading-resources'][url].push(resource);
		}

		// Mark it as loading
		that.loading.push(resource);

		// Remove it from the error list, if it's there
		var pos = that['error-loading'].indexOf(resource);
		if (pos >= 0) {
			that['error-loading'].splice(pos, 1);
		}
		
		var source = config.sources[resource];
		that['loading-urls'][source] = resource;
		
		// Add the resource/url to the waiting list. Do it now because `load_style` will call the callback function immediately
		// var type = config.types[resource];		
		// if (type == pop.c.SPARESOURCELOADER.TYPES.JS) {
		if (config.types[pop.c.SPARESOURCELOADER.TYPES.JS].indexOf(resource) >= 0) {
	
			var ordered = config['ordered-load-resources'].indexOf(resource) >= 0;
			load_script(source, markJSResourceAsLoaded, ordered);
		}
		// else if (type == pop.c.SPARESOURCELOADER.TYPES.CSS) {
		else if (config.types[pop.c.SPARESOURCELOADER.TYPES.CSS].indexOf(resource) >= 0) {

			load_style(source, markResourceAsLoaded);
		}
	},

	areResourcesLoadedForURL : function(url) {

		var that = this;

		// Check we have a config for this domain
		var config = that.getConfig(url);
		// if (!config) {
		// 	return;
		// }
		
		var resourcesDB = that.getResourcesDB(url);
		var urlComponents = that.getURLComponents(url);

		var keyId = config.keys[urlComponents.key];

		if (keyId && resourcesDB[keyId]) {

			var domain = getDomain(url);
			that.initLoadedByDomain(domain);
			
			var bundleGroupIds = resourcesDB[keyId];

			// Filter out the bundleGroupIds that have already been loaded
			bundleGroupIds = $(bundleGroupIds).not(that['loaded-by-domain'][domain]['bundle-groups']).get();

			var loadedBundleGroups = true;
			$.each(bundleGroupIds, function(index, bundleGroupId) {

				// Then check if the bundles have been loaded
				var bundleIds = config['bundle-groups'][bundleGroupId] || [];

				// Filter out the bundleIds that have already been loaded
				bundleIds = $(bundleIds).not(that['loaded-by-domain'][domain].bundles).get();

				var loadedBundles = true;
				$.each(bundleIds, function(index, bundleId) {

					// Finally check if the resources have been loaded
					var resources = config.bundles[bundleId];

					// Filter out the resources that have already been loaded
					resources = $(resources).not(that.loaded).get();
					var loadedResources = true;
					$.each(resources, function(index, resource) {

						// If the resource is in the error list, it failed loading, 
						// try to load it again. But only up to 3 times, then quit
						if (that['error-loading'].indexOf(resource) >= 0) {

							// 3 times => quit trying
							if (that['error-loading-times'][resource] >= 3) {
								
								that.loaded.push(resource);
							}
							else {

								that.loadResource(url, config, resource);
							}
						}

						// Check if the resource has been loaded
						if (that.loaded.indexOf(resource) == -1) {

							loadedResources = false;

							// It should be loading! If for some reason it is not, there was some error, then load it again
							var source = config.sources[resource];
							if (!that['loading-urls'][source]) {

								that.loadResource(url, config, resource);
							}
						}
					});
					if (loadedResources) {

						// Mark the bundle as loaded too
						that['loaded-by-domain'][domain]['bundles'].push(bundleId);
					}
					else {

						// Not all resources have been loaded, exit
						loadedBundles = false;
					}
				});

				if (loadedBundles) {

					// Mark also the bundleGroup and the bundles as loaded
					that['loaded-by-domain'][domain]['bundle-groups'].push(bundleGroupId);
				}
				else {

					loadedBundleGroups = false;
				}
			});

			// If all the resources have been loaded, then call documentInitializedIndependent on all of them at the same time
			// This is needed so that JS objects can register themselves in the proper execution order (eg: pop.MediaManagerCORS must execute before pop.MediaManager)
			if (loadedBundleGroups) {

				// Check it that URL needed to load resources
				var loadedURLResources = that['loading-resources'][url];
				if (loadedURLResources && loadedURLResources.length) {

					// Remove the entry
					delete that['loading-resources'][url];

					// Execute 'documentInitializedIndependent' only on those resources which have been loaded by this current load
					var codeSplitLibraries = [];
					$.each(loadedURLResources, function(index, loadedURLResource) {

						// getLibraries returns the list of JS objects which were registered by this current load
						codeSplitLibraries = codeSplitLibraries.concat(pop.CodeSplitJSLibraryManager.getLibraries(loadedURLResource));
					});

					// Only execute if there are new libraries to be initialized
					if (codeSplitLibraries.length) {

						// Initialize the resources
						var args = {
							domain: pop.c.HOME_DOMAIN,
							codeSplitLibraries: codeSplitLibraries,
						};
						pop.JSLibraryManager.execute('documentInitializedIndependent', args/*, true*/);
					}
				}
			}
			// else {

			// 	// Mark the URL as currently loading resources
			// 	if (that['loading-resources'].indexOf(url) == -1) {

			// 		that['loading-resources'].push(url);
			// 	}
			// }
				
			return loadedBundleGroups;
		}
		// If it couldnt' find a DB for the current URL, and the target is not main,
		// then check for a configuration using the main target
		else if (urlComponents.target != pop.c.URLPARAM_TARGET_MAIN) {

			// Remove parameter target from the URL, and try again
			url = removeQueryFields(url, [pop.c.URLPARAM_TARGET]);
			return that.areResourcesLoadedForURL(url);
		}

		// If we reached here, then there is no entry in the config file for this URL
		// Then simply say: yes, all resources are loaded! Otherwise, dependent scripts may be waiting forever!
		return true;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
if (pop.c.USECODESPLITTING) {

	pop.JSLibraryManager.register(pop.SPAResourceLoader, ['preFetchPageSection'/*, 'areResourcesLoaded'*/]);
}
