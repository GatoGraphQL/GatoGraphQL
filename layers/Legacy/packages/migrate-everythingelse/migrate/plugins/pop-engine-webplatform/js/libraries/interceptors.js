"use strict";
(function($){
window.pop.URLInterceptors = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	'fullurl': {},
	'partialurl' : {},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	pageSectionNewDOMsInitialized : function(args) {

		var that = this;
		var pageSection = args.pageSection, newDOMs = args.newDOMs;

		newDOMs.find('[data-intercept="fullurl"]').addBack('[data-intercept="fullurl"]').each(function () {

			var interceptor = $(this);
			var id = interceptor.attr('id');
			var url = that.getUrl(interceptor);
			var settings = that.getSettings(interceptor);
			var target = that.getTarget(pageSection, interceptor);

			if (!that.fullurl[target]) {
				that.fullurl[target] = {};				
			}
			if (!that.fullurl[target][url]) {
				that.fullurl[target][url] = [];
			}
			that.fullurl[target][url].push(id);
		});

		newDOMs.find('[data-intercept="partialurl"]').addBack('[data-intercept="partialurl"]').each(function () {

			var interceptor = $(this);
			var id = interceptor.attr('id');
			var url = that.getUrl(interceptor);
			var settings = that.getSettings(interceptor);
			var target = that.getTarget(pageSection, interceptor);
			
			if (!that.partialurl[target]) {
				that.partialurl[target] = {};				
			}
			that.partialurl[target][id] = url;
		});
	},

	destroyTarget : function(args) {

		var that = this;
		var pageSection = args.pageSection, destroyTarget = args.destroyTarget;

		// Destroy all Full-URL interceptors
		destroyTarget.find('[data-intercept="fullurl"]').each(function () {

			// Delete all 'document' interceptors
			var interceptor = $(this);
			var id = interceptor.attr('id');
			var url = that.getUrl(interceptor);
			var settings = that.getSettings(interceptor);
			var target = that.getTarget(pageSection, interceptor);

			// Remove all the interceptors with this id
			var interceptors = that.fullurl[target][url];
			if (interceptors) {

				interceptors = interceptors.filter(function (interceptorId, pos) {return id != interceptorId});
				if (interceptors.length) {

					that.fullurl[target][url] = interceptors;
				}
				else {

					delete that.fullurl[target][url];
				}
			}
		});

		// Destroy all Partial-URL interceptors
		destroyTarget.find('[data-intercept="partialurl"]').each(function () {

			// Delete all 'document' interceptors
			var interceptor = $(this);
			var id = interceptor.attr('id');
			var target = that.getTarget(pageSection, interceptor);
			delete that.partialurl[target][id];
		});
	},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	getSettings : function(interceptor) {

		var that = this;

		var settings = interceptor.data('intercept-settings') || '';
		settings = settings.split(pop.c.SEPARATOR);

		return settings;
	},

	getTarget : function(pageSection, interceptor) {

		var that = this;
		
		// Targets: a particular one (eg: navigator) or the document for everything
		return interceptor.attr('target') || pop.Manager.getFrameTarget(pageSection);
	},

	getUrl : function(interceptor) {

		var that = this;
		return interceptor.data('intercept-url');
	},

	getFullUrlInterceptors : function(url, options) {

		var that = this;
		var ret = [];
		options = options || {};

		var target = options.target || pop.c.URLPARAM_TARGET_MAIN;
		var interceptors = that.fullurl;

		if (interceptors[target]) {

			// 1. Check if there is a full match of the URL (eg: https://www.mesym.com/add-project/#dkjassd3545333)
			if (interceptors[target][url]) {

				ret.push($('#'+interceptors[target][url].join(', #')));
			}
			else {

				// 2. Check if there is a match of the URL without its params (eg: https://www.mesym.com/add-project/)
				// This is valid only for interceptors. Otherwise, once we've loaded https://www.mesym.com/u/mesym/, we can't call 
				// https://www.mesym.com/u/mesym/?tab=members since the first one will intercept it
				var noParamsUrl = removeParams(url);
				if (url != noParamsUrl) {

					if (interceptors[target][noParamsUrl]) {
						
						var maybeInterceptors = $('#'+interceptors[target][noParamsUrl].join(', #')).filter('.pop-interceptor');
						if (maybeInterceptors.length) {
							ret.push(maybeInterceptors);
						}
					}
				}
			}
		}

		return ret;
	},

	getPartialUrlInterceptor : function(interceptUrl, options) {

		var that = this;
		var interceptor = null;
		options = options || {};

		var target = options.target || pop.c.URLPARAM_TARGET_MAIN;
		var interceptors = that.partialurl;

		// First check if there's an interceptor for that template
		if (target) {

			// Wildcard * pageSection: search on any of them (this way, we can initially intercept for the Navigator, without being so explicit about it)
			var targetInterceptors = interceptors[target];
			if (targetInterceptors) {
				$.each(targetInterceptors, function(id, url) {
					if (interceptUrl.startsWith(url)) {

						interceptor = $('#'+id);
						return false;
					}
				});
			}
		}

		return interceptor;
	},

	getURLInterceptors : function(interceptUrl, options) {

		var that = this;
		
		// First try to intercept the full url, then the partial url
		var ret = that.getFullUrlInterceptors(interceptUrl, options);
		var partialurl = that.getPartialUrlInterceptor(interceptUrl, options);
		if (partialurl) {
			ret.push(partialurl);
		}
		return ret;
	},

	getInterceptors : function(interceptUrl, options) {

		var that = this;
		
		// First try to intercept the full url, then different alternatives, eg: url without '#'
		// This is needed for the preloading: eg: sequence:
		// click Add project -> go somewhere else -> destroy Add project -> press back btn in browser
		// then, the 1st intercept will fail (to url https://www.mesym.com/add-project/#1425467928853)
		// but the 2nd one will catch it and create a new Add Project page
		var ret = that.getURLInterceptors(interceptUrl, options);
		if (ret.length) {
			return ret;
		}

		var noMarkerUrl = removeMarker(interceptUrl);
		if (noMarkerUrl != interceptUrl) {

			ret = that.getURLInterceptors(noMarkerUrl, options);
		}

		return ret;
	},

	intercept : function(interceptUrl, options) {

		var that = this;
		var interceptor;
		options = options || {};
		
		var interceptors = that.getInterceptors(interceptUrl, options);

		// If interceptor found, use it
		if (interceptors.length) {

			// Since the interceptor will take care from now on, stopPropagation from wherever it was coming from
			if (options.event) {
				options.event.stopImmediatePropagation();
			}

			$.each(interceptors, function(index, interceptor) {

				// Check if any interceptor says to not push the URL to the browser. Eg: quickview, comments, browser history popstate
				var skip_state_update = interceptor.data('intercept-skipstateupdate') || options.skipstateupdate;
				if (!skip_state_update) {

					if (!options.skipPushState) {
						pop.BrowserHistory.pushState(interceptUrl);
					}

					// If it has a title (eg: pageTabs), then also update the browser title
					pop.Manager.updateTitle(interceptor.data('title'));
					return -1;
				}
			});

			var interceptor_atts = options['interceptor-atts'] || {};

			// Tell the component if to skip refetch
			$.each(interceptors, function(index, interceptor) {

				// Set all the atts in the interceptor
				$.each(interceptor_atts, function(key, value) {
					interceptor.data(key, value);
				});

				// Transfer atts from the clicked element to the interceptor
				var relatedTarget = options.relatedTarget;

				// Comment Leo 03/11/2015: instead of transferring atts, use function 
				// link = pop.Manager.getOriginalLink(link); to access again the original clicked element, with all its attributes
				// (no need to transfer anymore)
				// if (relatedTarget) {
				// 	$.each(pop.c.INTERCEPT_TRANSFER_ATTS, function(index, att) {
				// 		interceptor.data(att, relatedTarget.data(att));
				// 	});
				// }
				// Keep what link was clicked on the interceptor. It will be needed to retrieve
				// properties from the original link
				interceptor.data('interceptedTarget', relatedTarget);

				var settings = that.getSettings(interceptor.parent());
				interceptor.data('original-url', interceptUrl);
				interceptor.data('post-data', getParams(interceptUrl));
				interceptor.trigger('click');
			});

			return true;
		}

		// If nothing, return false
		return false;
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.URLInterceptors, ['destroyTarget', 'pageSectionNewDOMsInitialized'], true); // Make all base JS classes high priority so that they execute first

