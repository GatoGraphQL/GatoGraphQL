"use strict";
(function($){
window.pop.Typeahead = {
	
	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	typeahead : function(domain, pageSection, block, targets, components) {
	
		var that = this;
		
		var pageSectionPage = pop.Manager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {
			that.destroy(targets);
		});
		block.one('rerender', function(action) {
			
			that.destroy(targets);
		});

		targets.each(function() {

			var typeahead = $(this);
			var input = typeahead.find('input[type="text"]');

			// Retrieve the dataset
			var dataset = [];
			$.each(components, function(index, component) {
				
				var datasetJsSettings = pop.Manager.getJsSettings(domain, pageSection, block, component);
				dataset.push(datasetJsSettings['dataset']);
			});

			// Retrieve the compiled executableTemplate from the template
			$.each(dataset, function(index, datasetunit) {
				var template = datasetunit.template;
				datasetunit.executableTemplate = pop.Manager.getExecutableTemplate(domain, template);

				// Allow plug-ins to modify the url: allow pop-cdn to hook in the CDN
				var args = {};
				if (datasetunit.prefetch) {
					args.url = pop.Manager.maybeModifyURL(datasetunit.prefetch);
					pop.JSLibraryManager.execute('typeaheadPrefetchURL', args);
					datasetunit.prefetch = args.url;
				}
				if (datasetunit.remote) {
					args.url = pop.Manager.maybeModifyURL(datasetunit.remote);
					pop.JSLibraryManager.execute('typeaheadRemoteURL', args);
					datasetunit.remote = args.url;
				}
			});

			// Version 0.10.1: http://twitter.github.io/typeahead.js/examples/
			var bloodhounds = [];
			var settings = [];
			var search;
			$.each(dataset, function(index, datasetunit) {

				// Comment Leo 08/04/2015: if there are 2 or more components, then limit the 'limit' to no more than 3
				var limit = datasetunit.limit;
				if (dataset.length > 1) {
					if (limit > 3) {
						limit = 3;
					}
				}
				var options = {
					datumTokenizer: function(d) { 
						var tokens = [];
						$.each(datasetunit.tokenizerKeys, function(index, tokenizerKey) {
							
							tokens = tokens.concat(Bloodhound.tokenizers.whitespace(d[tokenizerKey]));
						});
						return tokens;
					},
					queryTokenizer: Bloodhound.tokenizers.whitespace,
				};
				if (datasetunit.local) {
					options.local = datasetunit.local;
				}
				if (datasetunit.prefetch) {
					options.prefetch = {
						url: datasetunit.prefetch,
						thumbprint: datasetunit[pop.c.KEYS_THUMBPRINT]
					};
				}
				if (datasetunit.remote) {
					options.remote = {
						url: datasetunit.remote,
						wildcard: pop.c.JSPLACEHOLDER_QUERY
					};
				}
				var bloodhound = new Bloodhound(options);
				bloodhounds.push(bloodhound);

				// static Source: allow pre-defined static values: needed for the Search
				var source;
				if (datasetunit.staticSuggestions) {

					source = function(query, cb) {
						var result = [];
						$.each(datasetunit.staticSuggestions, function(index, staticSuggestion) {

							var suggestion = {};
							$.each(staticSuggestion, function(key, value) {

								if (typeof value == 'string') {
									value = value.replace(pop.c.JSPLACEHOLDER_QUERY/*'%QUERY'*/, query);
								}						
								suggestion[key] = value;
							});
							result.push(suggestion);
						});
						
						cb(result);
					};
				}
				else {
					source = bloodhound.ttAdapter();
				}
				settings.push({
					source: source,
					name: datasetunit.name,
					display: datasetunit.valueKey,
					limit: limit,
					templates: {
						suggestion: datasetunit.executableTemplate,
						header: datasetunit.header,
						pending: datasetunit.pending,
						notFound: datasetunit.notFound,
					}
				});	
			});

			// Initialize bloodhounds
			$.each(bloodhounds, function(index, bloodhound) {
				bloodhound.initialize();
			});
			// Because the settings are passed as arguments, we need to examine how many settings we have and made
			// different calls for each one
			// Currently supported up to 4 bloodhounds (for All Users, All Content, Tags, and Static search)
			if (settings.length == 1) {
				input.typeahead({highlight: true}, settings[0]);
			}
			else if (settings.length == 2) {
				input.typeahead({highlight: true}, settings[0], settings[1]);
			}
			else if (settings.length == 3) {
				input.typeahead({highlight: true}, settings[0], settings[1], settings[2]);
			}
			else if (settings.length == 4) {
				input.typeahead({highlight: true}, settings[0], settings[1], settings[2], settings[3]);
			}
		});
	},

	destroy : function(targets) {
	
		var that = this;
		targets.find('input[type="text"].tt-input').typeahead('destroy');
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.Typeahead, []);
