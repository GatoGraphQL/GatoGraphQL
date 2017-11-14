(function($){
window.popTypeahead = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	typeaheadSearchBtn : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.click(function() {

			var control = $(this);
			var typeahead = control.closest('.pop-typeahead');
			that.executeTypeaheadSearch(pageSection, block, typeahead);
		});
	},
	typeaheadSearchInput : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.keypress(function(e) {

			// Pressed Enter key
			if (event.which == 13) {

				event.preventDefault();
				var searchbox = $(this);

				var typeahead = searchbox.closest('.pop-typeahead');
				
				// Close the typeahead, not needed anymore
				var input = typeahead.find('input[type="text"].tt-input');
				input.typeahead('close');

				that.executeTypeaheadSearch(pageSection, block, typeahead);
			}
		});
	},

	fillTypeahead : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		// var domain = getDomain(block.data('toplevel-url'));
		targets.each(function() {

			var typeahead = $(this);

			var urlparam = typeahead.data('urlparam');
			if (urlparam) {

				var url = popManager.getBlockTopLevelURL(block);//popManager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/;
				var values = getParam(urlparam, url);
				if (values) {

					var database_key = typeahead.data('database-key');
					$.each(values, function(key, value) {

						var datum = popManager.getItemObject(domain, database_key, value);

						// Trigger the select
						that.selectTypeahead(domain, pageSection, block, typeahead, datum);
					});

					// If we are inside a collapsed widget (eg: in the Add Post addons pageSection), then open the collapse
					popBootstrap.execOpenParentCollapse(typeahead);
				}
			}
		});
	},

	selectDatum : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			
			var link = $(this);
			var typeahead = $(link.data('target'));
			var value = link.data('objectid');
			var database_key = link.data('db-key');
			// var domain = getDomain(block.data('toplevel-url'));
			var datum = popManager.getItemObject(domain, database_key, value);

			// Trigger the select
			that.selectTypeahead(domain, pageSection, block, typeahead, datum);
		});
	},

	selectableTypeahead : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		that.typeahead(domain, pageSection, block, targets);

		targets.each(function() {

			var typeahead = $(this);

			that.validateMaxSelected(pageSection, block, typeahead);
			
			// Process the "selected" trigger
			typeahead.bind('typeahead:selected', function(obj, datum, name) {	  

				var typeahead = $(this);
				that.selectTypeahead(domain, pageSection, block, typeahead, datum);
			});
		});
	},

	fetchlinkTypeahead : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;
		
		that.typeahead(domain, pageSection, /*pageSectionPage, */block, targets);
		
		targets.each(function() {

			var typeahead = $(this);	

			// Process the "selected" trigger
			typeahead.bind('typeahead:selected', function(obj, datum, name) {	  
				
				var selected = $(this);
				var typeahead = selected.closest('.pop-typeahead');
				popManager.click(datum.url, '', typeahead);
			});
		});
	},

	selectableTypeaheadTrigger : function(args) {

		var that = this;
			
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Trigger close event
		targets.on('close.bs.alert', function() {
			
			var alert = $(this);
			var typeahead = alert.closest('.pop-typeahead');

			// Re-enable the Typeahead (if disabled because of max-selected limit reached)
			var disable = that.getElementsToDisable(typeahead);
			disable.attr('disabled', false);
			disable.removeClass('disabled');

			// Trigger the value for other components (eg: close the map marker in typeahead-map.js)
			var val = alert.children('input[type="hidden"]').val();
			typeahead.triggerHandler('close', [alert, val]);
		});
	},

	removeLocalStorageItem : function(args) {

		var that = this;
		var path = args.path, key = args.key;

		// Delete the localStorage entries for the typeahead
		args.result = args.result || key.startsWith('__'+path);
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	selectTypeahead : function(domain, pageSection, block, typeahead, datum) {
	
		var that = this;

		var input = typeahead.find('input[type="text"].tt-input');

		that.triggerSelect(domain, pageSection, block, typeahead, datum);
			
		// Delete the string
		input.typeahead('val', '');
	},

	executeTypeaheadSearch : function(pageSection, block, typeahead) {
	
		var that = this;
		var input = typeahead.find('input[type="text"].tt-input');

		// Trigger the search: get the url from the block dataload-source, and replace %QUERY with the value of the typeahead
		var url = popManager.getQueryUrl(pageSection, block);
		var val = input.typeahead('val');
		if (val) {
			url = url.replace(M.JSPLACEHOLDER_QUERY/*'%QUERY'*/, input.typeahead('val'));
			popManager.click(url, '', typeahead);
		}
	},

	getElementsToDisable : function(typeahead) {

		var that = this;
		return typeahead.children('div.pop-operation').find('a, button, input');
	},

	typeahead : function(domain, pageSection, block, targets) {
	
		var that = this;
		
		var pageSectionPage = popManager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {
			that.destroy(targets);
		});
		block.one('rerender', function(action) {
			
			that.destroy(targets);
		});

		targets.each(function() {

			var typeahead = $(this);
			var input = typeahead.find('input[type="text"]');
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, typeahead);		

			// Retrieve the dataset
			var dataset = [];
			$.each(jsSettings['dataset-components'], function(index, component) {
				
				var datasetJsSettings = popManager.getJsSettings(domain, pageSection, block, component);
				dataset.push(datasetJsSettings['dataset']);
			});

			// Retrieve the compiled template from "template-name"
			$.each(dataset, function(index, datasetunit) {
				var layout = datasetunit['layout'];
				datasetunit.template = popManager.getScriptTemplate(/*domain, */layout);

				// Allow plug-ins to modify the url: allow pop-cdn to hook in the CDN
				var args = {};
				if (datasetunit.prefetch) {
					args.url = datasetunit.prefetch;
					popJSLibraryManager.execute('typeaheadPrefetchURL', args);
					datasetunit.prefetch = args.url;
				}
				if (datasetunit.remote) {
					args.url = datasetunit.remote;
					popJSLibraryManager.execute('typeaheadRemoteURL', args);
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
					// limit: limit
				};
				if (datasetunit.local) {
					options.local = datasetunit.local;
				}
				if (datasetunit.prefetch) {
					options.prefetch = {
						url: datasetunit.prefetch,
						thumbprint: datasetunit[M.KEYS_THUMBPRINT]/*.thumbprint*/
					};
				}
				if (datasetunit.remote) {
					options.remote = {
						url: datasetunit.remote,
						wildcard: M.JSPLACEHOLDER_QUERY/*'%QUERY'*/
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
									value = value.replace(M.JSPLACEHOLDER_QUERY/*'%QUERY'*/, query);
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
						suggestion: datasetunit.template,
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

	triggerSelect : function(domain, pageSection, block, typeahead, datum) {
	
		var that = this;

		// Comment Leo 30/10/2017: when doing serverside-rendering, and first loading the website (eg: https://www.mesym.com/en/add-post/?related[0]=22199),
		// we will already have the item rendered. And since the database is not sent anymore, datum.id will be empty
		// so we must check for this condition too.

		// Check this value has not been added before
		if (datum && datum.id && typeahead.find('input[type="hidden"][value="'+datum.id+'"]').length == 0) {
		
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, typeahead);
			var template = jsSettings['trigger-layout'];
			var pssId = popManager.getSettingsId(pageSection);
			var bsId = popManager.getSettingsId(block);
			var context = $.extend({itemObject: datum}, jsSettings['datum-context']);

			// Generate the code and append
			var options = {extendContext: context}
			popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
			// var domain = block.data('domain') || getDomain(block.data('toplevel-url'));
			var html = popManager.getTemplateHtml(domain, pageSection, block, template, options);
			popManager.mergeHtml(html, typeahead.find('.pop-box'));
			popManager.runJSMethods(domain, pageSection, block, template, 'last');

			// Delete the session ids before starting a new session
			popJSRuntimeManager.deleteBlockLastSessionIds(domain, pageSection, block, template);

			that.validateMaxSelected(pageSection, block, typeahead);
			
			typeahead.triggerHandler('selected', [datum]);
		}
	},

	validateMaxSelected : function(pageSection, block, typeahead) {
	
		var that = this;

		// Count how many items, and if we reach the max, disable the typeahead
		if (typeahead.data('max-selected')) {

			var numSelected = typeahead.find('input[type="hidden"]').length;
			if (numSelected == typeahead.data('max-selected')) {

				// Disable the typeahead
				var disable = that.getElementsToDisable(typeahead);
				disable.attr('disabled', 'disabled');
				disable.addClass('disabled');
			}	
		}
	},

	removeSelected : function(typeahead, id) {
	
		var that = this;

		var selected = typeahead.find('input[type="hidden"][value="'+id+'"]');
		var alert = selected.closest('.alert');
		alert.alert('close');
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
popJSLibraryManager.register(popTypeahead, ['selectableTypeahead', 'fillTypeahead', 'selectDatum', 'fetchlinkTypeahead', 'selectableTypeaheadTrigger', 'typeaheadSearchBtn', 'typeaheadSearchInput', 'removeLocalStorageItem']);