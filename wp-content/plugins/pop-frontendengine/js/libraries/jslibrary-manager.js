"use strict";
(function($){
window.popJSLibraryManager = {

	// All the registered JS libraries
	libraries: [],

	// All the methods that each library can handle. Each method can be served by different libraries
	// This way each library can also serve common methods, like 'destroy', 'getState', 'clearState', etc
	methods: {},

	register : function(library, methods, highPriority, override) {

		var that = this;

		if (M.USECODESPLITTING) {
			popCodeSplitJSLibraryManager.register(library, methods, highPriority, override);
		}

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

	execute : function(method, args/*, removeLibraries*/) {

		var that = this;

		// Call 'destroy' from all libraries in popJSLibraryManager
		var ret = {};
		var libraries = that.methods[method];

		if (libraries) {

			// Allow to obtain only a subset of all registered libraries
			if (M.USECODESPLITTING) {
				
				// The Code Splitting Manager: allow it to filter on what libraries the execute will be applied
				// (when loading resources dynamically, function documentInitializedIndependent will be executed only on the newly loaded libraries, and not on all of them)
				var codeSplitArgs = {
					libraries: libraries,
				};
				$.extend(codeSplitArgs, args);
				popCodeSplitJSLibraryManager.maybeFilterLibraries(method, codeSplitArgs);
				libraries = codeSplitArgs.libraries;
			}

			$.each(libraries, function(index, library) {

				// ret[library] = library[method](args);
				ret['l'+index] = library[method](args);
			});
		}
		// else {
		// 	console.error('No JS library associated to execute method \''+method+'\'');
		// }

		// removeLibraries: used for method "documentInitializedIndependent" so we can initialize each library only once
		// if (removeLibraries) {

		// 	that.methods[method] = [];
		// }

		return ret;
	}
};
})(jQuery);
