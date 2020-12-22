"use strict";
(function($){
window.pop.JSLibraryManager = {

	// Allows PoP Resource Loader modify the libraries on which methods will be executed, for code splitting
	libraryManagers: [],

	// All the registered JS libraries
	libraries: [],

	// All the methods that each library can handle. Each method can be served by different libraries
	// This way each library can also serve common methods, like 'destroy', 'getState', 'clearState', etc
	methods: {},

	addLibraryManager : function(libraryManager) {

		var that = this;
		that.libraryManagers.push(libraryManager);
	},

	register : function(library, methods, highPriority, override) {

		var that = this;

		// Allow PoP Resource Loader to intercept the libraries for code splitting
		$.each(that.libraryManagers, function(index, libraryManager) {

			libraryManager.register(library, methods, highPriority, override);
		});

		that.libraries.push(library);
		$.each(methods, function(index, method) {

			// override: allows for any library to override others
			if (!that.methods[method] || override) {
				that.methods[method] = [];
			}

			if (highPriority) {
				that.methods[method].unshift(library);
			}
			else {
				that.methods[method].push(library);
			}
		})
	},

	getLibraries : function(method) {

		var that = this;
		return that.methods[method] || [];
	},

	execute : function(method, args) {

		var that = this;

		var ret = {};
		var libraries = that.methods[method];

		if (libraries) {

			// Code Splitting: Allow PoP Resource Loader to filter on what libraries the execute will be applied
			// (when loading resources dynamically, function documentInitializedIndependent will be executed only on the newly loaded libraries, and not on all of them)
			$.each(that.libraryManagers, function(index, libraryManager) {

				libraries = libraryManager.filterLibraries(libraries, method, args);
			});

			// Execute the method in each library
			$.each(libraries, function(index, library) {

				ret['l'+index] = library[method](args);
			});
		}

		return ret;
	}
};
})(jQuery);
