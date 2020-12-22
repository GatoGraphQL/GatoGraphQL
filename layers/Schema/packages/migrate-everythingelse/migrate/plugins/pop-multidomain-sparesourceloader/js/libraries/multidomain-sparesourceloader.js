"use strict";
(function($){
window.pop.MultiDomainSPAResourceLoader = {

	config : {

		// Configuration of the source for each domain's resourceloader-config.js file
		sources : {}
	},
	triggered : [], // Avoid scripts triggering multiple times

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	preFetchPageSection : function(args) {
	
		var that = this;

		var url = args.url;

		// Check if the domain is an external one, then load the corresponding resourceloader-config.js if needed
		var domain = getDomain(url);
		if (domain != pop.c.HOME_DOMAIN && !pop.ResourceLoader.config[domain] && that.triggered.indexOf(domain) == -1) {

			that.triggered.push(domain);

			// Check if we know from what URL we must load the file
			var sources = that.config.sources[domain] || [];
			// Comment Leo 21/10/2017: code below commented because, by default, it doesn't work, since the website-name is currently part of the URL
			// (eg: wp-content/pop-content/mesym/...)
			// A unifying solution must be found using the discoverability features, through which a website can broadcast all its information, including,
			// in this case, the location of its config file
			// if (!source) {

			// 	// If not, fallback option: guess it from replacing the home domain with the external domain on the local resourceloader-config.js URL
			// 	source = pop.c.CODESPLITTING.URLPLACEHOLDERS.CONFIG.format(domain);
			// }
			$.each(sources, function(index, source) {

				// There are 2 files per external domain: the config.js file, and the initialresources.js file
				// Important: load them in order! This is to make sure that config.js loads before initialresources.js
				load_script(source, null, true);
			});
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
if (pop.c.USECODESPLITTING) {
	pop.JSLibraryManager.register(pop.MultiDomainSPAResourceLoader, ['preFetchPageSection'], true); // Execute before everything else, so that it sends the backgroundLoad immediately. preFetchPageSection: execute before same function in pop.ResourceLoader
}
