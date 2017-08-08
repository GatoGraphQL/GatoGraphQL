(function($){
popMentions = {

	// Backup the previous results
	previousData: {},
	// domain: null,

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function(args) {
	
		var t = this;
		// t.domain = args.domain;

		// Load the Prefetch URLs
		t.prefetch();
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	prefetch : function() {
	
		var t = this;

		// Whenever starting the lookup (after typing @), the user can already see the list of all items from the prefetch,
		// and also it will set the prefetch results for the "previous" step
		$.each(M.MENTIONS.TYPES, function(delimiter, settings) {

			t.fetchData(delimiter, settings.baseline);
		});
	},

	fetchData : function(delimiter, url, process) {
	
		var t = this;

		// Have the results been stored? If so, use them
		var stored = popManager.getStoredData(url);
		if (stored) {

			t.processData(delimiter, stored, process);
		}
		else {

			$.getJSON(url, function (data) {

				// Store the results
				// Expires in 1 day
				var ttl = 86400000;
				popManager.storeData(url, data, ttl);

				t.processData(delimiter, data, process);
			});
		}
	},

	processData : function(delimiter, data, process) {
	
		var t = this;

		// Backup the results as the "previous" results
		t.previousData[delimiter] = data;

		if (process) {

			// Process the data
			process(data);
		}
	},

	source : function(query, process, delimiter) {
	
		var t = this;

		// If we have set-up a confiration for this delimiter
		if (M.MENTIONS.TYPES[delimiter]) {

			var url;

			// If there is a query, then use it with to search for results
			if (query) {

				// Add the query into the URL
				url = M.MENTIONS.TYPES[delimiter].url.replace(M.MENTIONS.QUERYWILDCARD, query);
			}
			// Otherwise, use the results from the Baseline URL, which is an initial amount of results to give the user
			else {

				// Use the baseline
				url = M.MENTIONS.TYPES[delimiter].baseline;
			}
			if (url) {

				// Allow plug-ins to modify the url: allow pop-cdn to hook in the CDN
				var args = {
					url: url,
					delimiter: delimiter,
					query: query
				};
				popJSLibraryManager.execute('mentionsSource', args);
				url = args.url;

				// Start processing with the results from the previous lookup, as to not keep the user waiting for the response to come back
				// Base case: [], for if the first load has not finished yet
				var previousData = t.previousData[delimiter] || [];
				process(previousData);

				// Fetch the data, and process it when it comes back
				t.fetchData(delimiter, url, process);
			}
		}
	},

	render: function(item) {

		var t = this;
		var delimiter = t.infereDelimiter(item);
		var html = popManager.getHtml(/*t.domain, */M.MENTIONS.TYPES[delimiter].template, item);
		return '<li><a href="javascript:;"><span>'+html+'</span></a></li>';
	},

	insert : function(item) {
	
		var t = this;

		var delimiter = t.infereDelimiter(item);
		return '<span>' + delimiter + item[M.MENTIONS.TYPES[delimiter].key] + '</span>&nbsp;';
	},

	infereDelimiter : function(item) {
	
		var t = this;

		// We do not have the delimiter, however we can guess it back from the key from all delimiter settings.
		var ret = '';
		$.each(M.MENTIONS.TYPES, function(delimiter, settings) {

			// Check if the item has attribute defined under "key"
			if (typeof item[settings.key] != 'undefined') {
				ret = delimiter;
				return -1;
			}
		});

		return ret;
	},

	// renderDropdown: function() {
	// 	//add twitter bootstrap dropdown-menu class
	// 	return '<ul class="rte-autocomplete dropdown-menu"></ul>';
	// }
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popMentions, ['documentInitialized']);


//-------------------------------------------------
// Callbacks (from the tinyMCE settings)
//-------------------------------------------------
function mentions_source(query, process, delimiter) {
	popMentions.source(query, process, delimiter);
}
function mentions_insert(item) {
	return popMentions.insert(item);	
}
function mentions_render(item) {
	return popMentions.render(item);	
}
// function mentions_renderdropdown() {
// 	return popMentions.renderDropdown();	
// }