(function($){
popJSLibraryManager = {

	// All the registered JS libraries
	libraries: [],

	// All the methods that each library can handle. Each method can be served by different libraries
	// This way each library can also serve common methods, like 'destroy', 'getState', 'clearState', etc
	methods: {},

	register : function(library, methods, highPriority, override) {

		var t = this;

		t.libraries.push(library);
		$.each(methods, function(index, method) {

			// override: allows for any library to override others
			if (!t.methods[method] || override) {
				t.methods[method] = [];
			}

			if (highPriority) {
				t.methods[method].unshift(library);
			}
			else {
				t.methods[method].push(library);
			}
		})
	},

	getLibraries : function(method) {

		var t = this;
		return t.methods[method] || [];
	},

	execute : function(method, args) {

		var t = this;

		// Call 'destroy' from all libraries in popJSLibraryManager
		var ret = {};
		var libraries = t.methods[method];
		if (libraries) {
			$.each(libraries, function(index, library) {

				// ret[library] = library[method](args);
				ret['l'+index] = library[method](args);
			});
		}
		// else {
		// 	console.error('No JS library associated to execute method \''+method+'\'');
		// }


		return ret;
	}
};
})(jQuery);
