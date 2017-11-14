(function($){
window.popEmbedFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	modalReloadEmbedPreview : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		targets.each(function() {

			var iframe = $(this);
			var block = popManager.getBlock(iframe);

			// Embed is the default urlType, but API also enabled
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, iframe);
			var urlType = jsSettings['url-type'] || 'embed';
			var modal = block.closest('.modal');
			modal.on('show.bs.modal', function(e) {

				// Important: once the modal opens, we need to find the iframe again, because the iframe
				// object will change all the time, being regenerated in function embedPreview
				// So the original iframe will be used only once, on the first execution, and never again
				if (block.data('embed-iframe')) {
					iframe = $(block.data('embed-iframe'));
				}

				var link = $(e.relatedTarget);
				var url = popFunctions.getUrl(domain, link, true);
				if (urlType == 'embed') {
					url = popManager.getEmbedUrl(url);
				}
				else if (urlType == 'api') {
					url = popManager.getAPIUrl(url);
				}
				that.embedPreview(domain, pageSection, block, iframe, url);
			});
		});
	},

	reloadEmbedPreview : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.each(function() {

			var connector = $(this);
			var input = $(connector.data('input-target'));
			input.change(function() {
				
				var input = $(this);
				that.embedPreviewFromInput(domain, pageSection, block, connector);
			});

			// If the input already has a value, then already do the embed (eg: Edit Link)
			that.embedPreviewFromInput(domain, pageSection, block, connector);
		});
	},

	replaceCode : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		targets.each(function() {

			var input = $(this);
			var block = popManager.getBlock(input);
			
			// Default: Copy Search URL (ie: do nothing else to the url)
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, input);
			var urlType = jsSettings['url-type'] || '';
			var modal = input.closest('.modal');
			modal.on('show.bs.modal', function(e) {

				var link = $(e.relatedTarget);
				var url = popFunctions.getUrl(domain, link);

				// Maybe the URL type is none, then leave the URL as it is (eg: Copy Search URL),
				// or it may need to add extra params, like embed or api
				if (urlType == 'embed') {
					url = popManager.getEmbedUrl(url);
				}
				else if (urlType == 'api') {
					url = popManager.getAPIUrl(url);
				}
				that.execReplaceCode(input, url);
			});
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	embedPreviewFromInput : function(domain, pageSection, block, connector) {

		var that = this;

		var input = $(connector.data('input-target'));
		var url = input.val();
		if (url && isUrlValid(url)) {
			
			// Comment Leo 16/08/2016: not using iframe-target anymore, instead using iframe-template, and calculating the last id for that template
			// Comment Leo 16/08/2016: discarded this code, not needed, but kept for the time being
			// var template = connector.data('iframe-template');
			// var iframeid = popJSRuntimeManager.getLastGeneratedId(popManager.getSettingsId(pageSection), popManager.getSettingsId(block), template);
			// var iframe = $('#'+iframeid);

			// // Because the iframe will be regenerated, the id to the iframe will become obsolete,
			// // so update it. Since doing REPLACE_INLINE, the new iframe will be the targetContainer
			var iframe = $(connector.data('iframe-target'));
			var merged = that.embedPreview(domain, pageSection, block, iframe, url);
			connector.data('iframe-target', '#'+merged.targetContainer.attr('id'));
		}
	},

	embedPreview : function(domain, pageSection, block, iframe, url) {

		var that = this;

		// Set the URL into the preview settings configuration, and re-draw the structure-inner
		var options = {
			operation: M.URLPARAM_OPERATION_REPLACEINLINE,
			extendContext: {
				src: url
			},
			'merge-target': '#'+iframe.attr('id') 
		}

		// Run again the Handlebars template to re-print the image with the new data
		var template = iframe.data('templateid');
		popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
		// var domain = block.data('domain') || getDomain(block.data('toplevel-url'));
		var merged = popManager.mergeTargetTemplate(domain, pageSection, block, template, options);
		popManager.runJSMethods(domain, pageSection, block, template, 'full');

		// Set the Block URL to indicate from where the session-ids must be retrieved
		var iframeid = popJSRuntimeManager.getLastGeneratedId(domain, popManager.getSettingsId(pageSection), popManager.getSettingsId(block), template);
		block.data('embed-iframe', '#'+iframeid);

		return merged;
	},

	execReplaceCode : function(input, url) {

		var that = this;

		var placeholder = input.data('code-placeholder');
		var code = placeholder.format(url);

		// Insert the code into the textarea
		input.val(code);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popEmbedFunctions, ['replaceCode', 'modalReloadEmbedPreview', 'reloadEmbedPreview']);
