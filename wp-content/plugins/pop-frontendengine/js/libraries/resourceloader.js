"use strict";
// This function are called as a callback to load_scripts, so they lose the context of who 't' is
// So then place them outside the popResourceLoader structure
function markResourceAsLoaded(url, state) {

	popResourceLoader.markResourceAsLoaded(url, state);
}

(function($){
window.popResourceLoader = {

	// The values here will be populated from resourceloader-config.js,
	// on a domain by domain basis
	config : {},

	// Keep a list of all loading resources
	loading : {
		js : {
			resources: [],
		},
		// css : {
		// 	resources: [],
		// 	bundles: [],
		// 	"bundle-groups": [],
		// }
	},
	'error-loading' : {
		js : {
			resources: [],
		},
	},
	'loading-urls' : {},

	// Keep a list of all loaded resources. All resources are called always the same among different domains,
	// so one list here listing all of them works
	loaded : {
		js : {
			resources: [],
		},
	},
	// Loaded bundles and bundleGroups depend on their domains, since their names change among domains
	'loaded-by-domain' : {},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	preFetchPageSection : function(args) {
	
		var that = this;
		var url = args.url, options = args.options;

		if (M.USECODESPLITTING) {

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
		}
	},

	areResourcesLoaded : function(args) {

		var that = this;

		if (M.USECODESPLITTING) {
			
			args.loaded = that.areResourcesLoadedForURL(args.fetchUrl);
		}
	},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	markResourceAsLoaded : function(url, state) {

		var that = this;

		// From the URL, get the corresponding resource
		var resource = that['loading-urls'][url];

		// Only mark it as loaded if the state is "ok" and not "error"
		if (state == 'ok') {

			that.loaded.js.resources.push(resource);
		}
		else if (state == 'error') {
			that['error-loading'].js.resources.push(resource);
		}

		// Remove from the loading list, no need anymore
		that.loading.js.resources.splice(that.loading.js.resources.indexOf(resource), 1);
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

		// If file resourceloader-config-resources.js has been loaded, i.e. all configs have been loaded, then return true
		if (config.loaded) {
			return true;
		}

		// Check if the path has already been loaded (eg: initially set for backgroundLoad or Loggedin-user-data)
		var urlProperties = that.getPropertiesForURL(url);
		var hierarchy = urlProperties.hierarchy;
		var path = urlProperties.path;

		// First check if the combination of hierarchy => path has been set
		var path_hierarchies = ['page', 'single'];
		if (path_hierarchies.indexOf(hierarchy) >= 0) {

			if (config['loaded-paths'][hierarchy] && config['loaded-paths'][hierarchy].indexOf(path) >= 0) {

				return true;
			}
		}

		// If not, check if the smaller "hierarchy-format" config file was loaded
		var configFile = that.getConfigFile(url);

		return config['loaded-files'].indexOf(configFile) >= 0;
	},

	getConfigFile : function(url) {

		var that = this;
		
		var config = that.getConfig(url);
		var urlProperties = that.getPropertiesForURL(url);
		var hierarchy = urlProperties.hierarchy;
		var format = urlProperties.format;
		return config['configfile-urlplaceholder'].format(hierarchy, format);
	},

	getPropertiesForURL : function(url) {

		var that = this;
		// if (!config) {
		// 	return;
		// }

		var path = popUtils.getPath(url);
		var hierarchy = that.getHierarchy(url);
		var format = getParam(M.URLPARAM_FORMAT, url) || M.VALUES_DEFAULT;

		return {
			"hierarchy": hierarchy,
			"format": format,
			"path": path,
		};
	},

	// isSinglePath : function(path, single_path) {

	// 	var that = this;
	// 	return path.startsWith(single_path) && path != single_path;
	// },

	getConfig : function(url) {

		var that = this;

		var domain = getDomain(url);

		// Check we have a config for this domain
		var config = that.config[domain];
		if (!config && domain != M.HOME_DOMAIN) {

			// If we don't have a config, and the domain is not local, then try the local domain
			// (This is needed for if the external resourceloader-config.js file has not been loaded yet. 
			// This may happen often, as loading this file is asynchronous, so needing to check the URL path
			// will happen before the script is loaded)
			config = that.config[M.HOME_DOMAIN];
		}
		
		return config || {};
	},

	getHierarchy : function(url) {

		var that = this;

		// Check we have a config for this domain
		var config = that.getConfig(url);
		// if (!config) {
		// 	return;
		// }

		// // Remove the domain and locale from the URL
		// // var partialURL = url.substr((M.HOMELOCALE_URL+'/').length);
		// var noParamsURL = removeParams(url);
		// // var isHome = (noParamsURL == M.HOME_DOMAIN || noParamsURL == M.HOME_DOMAIN+'/' || noParamsURL == M.HOMELOCALE_URL || noParamsURL == M.HOMELOCALE_URL+'/');

		// Comment Leo 29/09/2017: using config['path-start-pos'] doesn't work for when using the local config for an external URL,
		// so then we just use a function to calculate it
		// var path = noParamsURL.substr(config['path-start-pos']);
		var path = popUtils.getPath(url);
		
		// Home
		// if (path == M.HOME_DOMAIN || path == M.HOME_DOMAIN+'/' || path == M.HOMELOCALE_URL || path == M.HOMELOCALE_URL+'/') {
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
		if (!config || !config.resources || !config.resources.js) {

			return {};
		}

		var hierarchy = that.getHierarchy(url);		
		var path = popUtils.getPath(url);

		// The resources are placed under the hierarchy key in object config.resources.js
		var jsResourcesDB = config.resources.js[hierarchy];

		// // If we are requesting an external URL, and the config for that external domain is still not loaded, then jsResourcedDB will be null
		// // Eg: when loading: https://sukipop.com/en/external/?url=https%3A%2F%2Fwww.mesym.com%2Fen%2Fevents%2Fmindset-public-talk-maintaining-peopled-forests-by-joe-fragoso-and-kamal-s-fadzil%2F
		// if (jsResourcesDB) {

		// single and page are not flat, but their resources configuration comes under the path
		if (hierarchy == 'single') {

			// Recover which was the path that made the criteria succeed, the configuration is placed under that one
			// The code below doesn't work in UglifyJS!
			// var paths = single_paths.filter(single_path => path.startsWith(single_path) && path != single_path/*that.isSinglePath(path, single_path)*/);
			var paths = config.paths.single.filter(function(single_path) { return path.startsWith(single_path) && path != single_path /*that.isSinglePath(path, single_path)*/;});
			jsResourcesDB = jsResourcesDB[paths[0]];
		}
		else if (hierarchy == 'page') {

			jsResourcesDB = jsResourcesDB[path];
		}
		// }

		// Allow Public Post Preview to hook in when previewing a post, to change the DB from home to the single post
		var args = {
			url: url,
			path: path,
			config: config,
			jsResourcesDB: jsResourcesDB,
		};
		popJSLibraryManager.execute('loadResourcesDB', args);
		jsResourcesDB = args.jsResourcesDB;

		return jsResourcesDB || {};
	},

	getURLComponents : function(url) {

		var that = this;

		var params = [];
		// This code is replicated in function `add_resources_from_current_vars` in class `PoP_ResourceLoaderProcessorUtils`
		var target = getParam(M.URLPARAM_TARGET, url) || M.URLPARAM_TARGET_MAIN;
		var format = getParam(M.URLPARAM_FORMAT, url) || M.VALUES_DEFAULT;
		var tab = getParam(M.URLPARAM_TAB, url) || '';
		if (format) {
			params.push(M.CODESPLITTING.PREFIXES.FORMAT+format);
		}
		if (tab) {
			params.push(M.CODESPLITTING.PREFIXES.TAB+tab);
		}
		if (target) {
			params.push(M.CODESPLITTING.PREFIXES.TARGET+target);
		}
		var key = params.join(M.SEPARATOR_RESOURCELOADER);

		return {
			params: params,
			target: target,
			format: format,
			tab: tab,
			key: key,
		};
	},

	initLoadedByDomain : function(domain) {

		var that = this;
		if (!that['loaded-by-domain'][domain]) {
			
			that['loaded-by-domain'][domain] = {
				js: {
					bundles: [], 
					'bundle-groups': [],
				}
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
		
		var jsResourcesDB = that.getResourcesDB(url);
		var urlComponents = that.getURLComponents(url);

		var keyId = config.keys[urlComponents.key];

		if (keyId && jsResourcesDB[keyId]) {

			var domain = getDomain(url);
			that.initLoadedByDomain(domain);
			
			// Load all the resources using JS		
			var bundleGroupId = jsResourcesDB[keyId];

			// Check if that bundleGroup has been loaded or loading. If so, do nothing
			if (that['loaded-by-domain'][domain].js['bundle-groups'].indexOf(bundleGroupId) == -1/* || that.loading.js['bundle-groups'].indexOf(bundleGroupId) == -1*/) {

				// // Mark the bundleGroup as loading from now on
				// that.loading.js['bundle-groups'].push(bundleGroupId);

				var bundleIds = config['bundle-groups'][bundleGroupId] || [];

				// Filter out the bundleIds that have already been loaded or loading
				bundleIds = $(bundleIds).not(that['loaded-by-domain'][domain].js.bundles).get();
				// bundleIds = $(bundleIds).not(that.loading.js.bundles).get();

				$.each(bundleIds, function(index, bundleId) {

					// Check if that bundle has been loaded or loading. If so, do nothing
					if (that['loaded-by-domain'][domain].js.bundles.indexOf(bundleId) == -1/* || that.loading.js.bundles.indexOf(bundleId) == -1*/) {

						// // Mark the bundle as loaded from now on
						// that.loading.js.bundles.push(bundleId);

						var resources = config.bundles[bundleId];

						// Filter out the resources that have already been loaded or loading
						resources = $(resources).not(that.loaded.js.resources).get();
						resources = $(resources).not(that.loading.js.resources).get();

						$.each(resources, function(index, resource) {

							that.loadResource(config, resource);
						});
					}
				});
			}
		}
		// If it couldnt' find a DB for the current URL, and the target is not main,
		// then check for a configuration using the main target
		else if (urlComponents.target != M.URLPARAM_TARGET_MAIN) {

			// Remove parameter target from the URL, and try again
			url = removeQueryFields(url, [M.URLPARAM_TARGET]);
			that.loadResourcesForURL(url);
		}
	},

	loadResource : function(config, resource) {

		var that = this;		

		// Mark it as loading
		that.loading.js.resources.push(resource);

		// Remove it from the error list, if it's there
		var pos = that['error-loading'].js.resources.indexOf(resource);
		if (pos >= 0) {
			that['error-loading'].js.resources.splice(pos, 1);
		}
		
		var source = config.sources[resource];
		var ordered = config['ordered-load-resources'].indexOf(resource) >= 0;
		load_script(source, markResourceAsLoaded, ordered);

		// Add the resource/url to the waiting list
		that['loading-urls'][source] = resource;
	},

	areResourcesLoadedForURL : function(url) {

		var that = this;

		// Check we have a config for this domain
		var config = that.getConfig(url);
		// if (!config) {
		// 	return;
		// }
		
		var jsResourcesDB = that.getResourcesDB(url);
		var urlComponents = that.getURLComponents(url);

		var keyId = config.keys[urlComponents.key];

		if (keyId && jsResourcesDB[keyId]) {

			var domain = getDomain(url);
			that.initLoadedByDomain(domain);
			
			// First check if the bundleGroup is loaded
			var bundleGroupId = jsResourcesDB[keyId];

			// Check if that bundleGroup has been loaded. If so, do nothing
			if (that['loaded-by-domain'][domain].js['bundle-groups'].indexOf(bundleGroupId) >= 0) {

				return true;
			}

			// Then check if the bundles have been loaded
			var bundleIds = config['bundle-groups'][bundleGroupId] || [];

			// Filter out the bundleIds that have already been loaded
			bundleIds = $(bundleIds).not(that['loaded-by-domain'][domain].js.bundles).get();
			
			if (!bundleIds.length) {

				// Mark also the bundleGroup as loaded
				that['loaded-by-domain'][domain].js['bundle-groups'].push(bundleGroupId);

				return true;
			}

			var loadedResources = true;
			$.each(bundleIds, function(index, bundleId) {

				// Finally check if the resources have been loaded
				var resources = config.bundles[bundleId];

				// Filter out the resources that have already been loaded
				resources = $(resources).not(that.loaded.js.resources).get();
				var loadedBundleResources = true;
				$.each(resources, function(index, resource) {

					// If the resource is in the error list, it failed loading, 
					// try to load it again
					if (that['error-loading'].js.resources.indexOf(resource) >= 0) {

						that.loadResource(config, resource);
					}

					// Check if the resource has been loaded
					if (that.loaded.js.resources.indexOf(resource) == -1) {

						loadedBundleResources = false;

						// It should be loading! If for some reason it is not, there was some error, then load it again
						var source = config.sources[resource];
						if (!that['loading-urls'][source]) {

							that.loadResource(config, resource);
						}

						// Exit the loop
						return -1;
					}
				});
				if (loadedBundleResources) {

					// Mark the bundles as loaded
					that['loaded-by-domain'][domain].js['bundles'].push(bundleId);
				}
				else {

					// Not all resources have been loaded, exit
					loadedResources = false;

					// Exit the loop
					return -1;
				}
			});

			if (loadedResources) {

				// Mark also the bundleGroup and the bundles as loaded
				that['loaded-by-domain'][domain].js['bundle-groups'].push(bundleGroupId);

				return true;
			}

			// Not everything has been loaded yet
			return false;
		}
		// If it couldnt' find a DB for the current URL, and the target is not main,
		// then check for a configuration using the main target
		else if (urlComponents.target != M.URLPARAM_TARGET_MAIN) {

			// Remove parameter target from the URL, and try again
			url = removeQueryFields(url, [M.URLPARAM_TARGET]);
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
popJSLibraryManager.register(popResourceLoader, ['preFetchPageSection', 'areResourcesLoaded']);
