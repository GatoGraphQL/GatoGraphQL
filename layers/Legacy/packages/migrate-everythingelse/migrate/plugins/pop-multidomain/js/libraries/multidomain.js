"use strict";
(function($){
window.pop.MultiDomain = {

	config : {

		'domain-scripts' : {}, // scripts to execute when initializing a domain
	},

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	initDomain : function(args) {
	
		var that = this;

		var domain = args.domain;

		// If this is not the local site's domain, the fetch its initial data from the server
		if (/*fetchData = */domain != pop.c.HOME_DOMAIN) {

			// Fetch the corresponding data from the server to initialize the domain
			var url = pop.c.PLACEHOLDER_DOMAINURL.format(encodeURIComponent(domain));
			var entries = {};
			entries[url] = [pop.c.URLPARAM_TARGET_MAIN];
			pop.Manager.backgroundLoad(entries);

			// Execute initialization scripts
			var domainScripts = that.config['domain-scripts'][domain];
			if (typeof domainScripts != 'undefined' && domainScripts.length) {

				$.each(domainScripts, function(index, script) {

					load_script(script);
				});
			}
		}
	},

	modifyFetchArgs : function(args) {
	
		var that = this;
		var domain = args.domain, url = args.url;
		args.url = that.addOriginParameter(domain, url);
	},

	modifyFetchBlockArgs : function(args) {
	
		var that = this;
		var domain = args.domain, url = args.url;
		args.url = that.addOriginParameter(domain, url);
	},

	formatFeedbackMessage : function(args) {

		var that = this;

		var message = args.message, topLevelSettings = args.tls, blockSettings = args.bs;

		// var isMultiDomain = options.hash['is-multidomain'];
		// var domain = options.hash.domain;
		var isMultiDomain = blockSettings['is-multidomain'];
		var domain = topLevelSettings.domain;
		if (isMultiDomain && domain) {

			// If specified the domain, then add its name in the message, through a customizable format
			var name = pop.c.MULTIDOMAIN_WEBSITES[domain] ? pop.c.MULTIDOMAIN_WEBSITES[domain].name : domain;
			args.message = pop.c.FEEDBACKMSG_MULTIDOMAIN.format(name, message);
		}
	},

	clickURLParam : function(args) {

		var that = this;

		// "click" on the URL defined in parameter "url"
		var url = getParam(pop.c.URLPARAM_URL);
		if (url) {
			$(document).ready(function($) {
				pop.Manager.click(decodeURIComponent(url));
			});
		}
	},

	modifyPushStateURL : function(args) {

		var that = this;
		var url = args.url;

		// Platform of Platforms: if the URL is not from this website, then add it as a param
		if(!url.startsWith(pop.c.HOME_DOMAIN)) {
			args.url = add_query_arg(pop.c.URLPARAM_URL, encodeURIComponent(url), pop.c.EXTERNAL_URL);
		}
	},

	modifyApplicationURL : function(args) {

		var that = this;
		var url = args.url;

		// If it is the external page, then recover the actual URL
		if (url.startsWith(pop.c.EXTERNAL_URL)) {
			args.url = decodeURIComponent(getParam(pop.c.URLPARAM_URL, url));
		}
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	addOriginParameter : function(domain, url) {
	
		var that = this;
		
		// CrossDomain? Add extra parameter to avoid CDN caching the same header "Access-Control-Allow-Origin" for all domains, which will produce errors when visiting from a 2nd domain
		if (domain != pop.c.HOME_DOMAIN) {
			
			url = add_query_arg(pop.c.URLPARAM_ORIGIN, getDomainId(pop.c.HOME_DOMAIN), url);
		}

		return url;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.MultiDomain, ['initDomain'], true); // Execute before everything else, so that it sends the backgroundLoad immediately. preFetchPageSection: execute before same function in pop.ResourceLoader
pop.JSLibraryManager.register(pop.MultiDomain, ['modifyFetchArgs', 'modifyFetchBlockArgs', 'formatFeedbackMessage', 'clickURLParam', 'modifyPushStateURL', 'modifyApplicationURL']);
