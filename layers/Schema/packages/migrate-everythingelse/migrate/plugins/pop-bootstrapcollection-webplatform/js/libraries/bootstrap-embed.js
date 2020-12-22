"use strict";
(function($){
window.pop.BootstrapEmbed = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	modalReloadEmbedPreview : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		targets.each(function() {

			var iframe = $(this);
			var block = pop.Manager.getBlock(iframe);

			// Embed is the default urlType, but API also enabled
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, iframe);
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
				var url = pop.Functions.getUrl(domain, link, true);
				if (urlType == 'embed') {
					url = pop.EmbedFunctions.getEmbedUrl(url);
				}
				else if (urlType == 'api') {
					url = pop.Manager.getAPIUrl(url);
				}
				pop.EmbedFunctions.embedPreview(domain, pageSection, block, iframe, url);
			});
		});
	},

	replaceCode : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		targets.each(function() {

			var input = $(this);
			var block = pop.Manager.getBlock(input);
			
			// Default: Copy Search URL (ie: do nothing else to the url)
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, input);
			var urlType = jsSettings['url-type'] || '';
			var modal = input.closest('.modal');
			modal.on('show.bs.modal', function(e) {

				var link = $(e.relatedTarget);
				var url = pop.Functions.getUrl(domain, link);

				// Maybe the URL type is none, then leave the URL as it is (eg: Copy Search URL),
				// or it may need to add extra params, like embed or api
				if (urlType == 'embed') {
					url = pop.EmbedFunctions.getEmbedUrl(url);
				}
				else if (urlType == 'api') {
					url = pop.Manager.getAPIUrl(url);
				}
				pop.EmbedFunctions.execReplaceCode(input, url);
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapEmbed, ['replaceCode', 'modalReloadEmbedPreview']);
