"use strict";
(function($){
window.pop.EmbedFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

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

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	getUnembedUrl : function(url) {

		var that = this;

		// Allow to have custom-functions.js provide the implementation of this function
		var executed = pop.JSLibraryManager.execute('getUnembedUrl', {url: url});
		var ret = false;
		$.each(executed, function(index, value) {
			if (value) {
				url = value;
				return -1;
			}
		});
		
		return url;
	},

	getEmbedUrl : function(url) {
	
		var that = this;

		// Allow to have custom-functions.js provide the implementation of this function
		var executed = pop.JSLibraryManager.execute('getEmbedUrl', {url: url});
		var ret = false;
		$.each(executed, function(index, value) {
			if (value) {
				url = value;
				return -1;
			}
		});
		
		return url;
	},

	getPrintUrl : function(url) {
	
		var that = this;
		
		// Allow to have custom-functions.js provide the implementation of this function
		var executed = pop.JSLibraryManager.execute('getPrintUrl', {url: url});
		var ret = false;
		$.each(executed, function(index, value) {
			if (value) {
				url = value;
				return -1;
			}
		});
		
		return url;
	},

	embedPreviewFromInput : function(domain, pageSection, block, connector) {

		var that = this;

		var input = $(connector.data('input-target'));
		var url = input.val();
		if (url && isUrlValid(url)) {
			
			// Comment Leo 16/08/2016: not using iframe-target anymore, instead using iframe-module, and calculating the last id for that module
			// Comment Leo 16/08/2016: discarded this code, not needed, but kept for the time being
			// var moduleName = connector.data('iframe-component');
			// var iframeid = pop.JSRuntimeManager.getLastGeneratedId(pop.Manager.getSettingsId(pageSection), pop.Manager.getSettingsId(block), moduleName);
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
			operation: pop.c.URLPARAM_OPERATION_REPLACEINLINE,
		};
		var context = {
			src: url,
		}; 

		// Run again the Handlebars module to re-print the image with the new data
		var moduleName = iframe.data('modulename');
		var merged = pop.DynamicRender.render(domain, pageSection, block, moduleName, iframe, context, options);

		// Set the Block URL to indicate from where the session-ids must be retrieved
		var iframeid = pop.JSRuntimeManager.getLastGeneratedId(domain, pop.Manager.getSettingsId(pageSection), pop.Manager.getSettingsId(block), moduleName);
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
pop.JSLibraryManager.register(pop.EmbedFunctions, ['reloadEmbedPreview']);
