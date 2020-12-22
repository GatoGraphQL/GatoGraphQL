"use strict";
(function($){
window.pop.Controls = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	// controlOpenAll : function(args) {
	
	// 	var that = this;
	// 	var pageSection = args.pageSection, block = args.block, targets = args.targets;

	// 	targets.click(function(e) {

	// 		e.preventDefault();
	// 		var control = $(this);
	// 		var targetBlock = $(control.data('blocktarget'));
	// 		var target = control.data('target');

	// 		var url = pop.Manager.getBlockFilteringUrl(pageSection, targetBlock);
	// 		pop.Manager.fetch(url, {target: target});
	// 	});
	// },
	controlPrint : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			var control = $(this);
			var url = that.getBlockFilteringUrl(domain, pageSection, control, true);
			url = pop.EmbedFunctions.getPrintUrl(url);
			pop.Links.openPrint(url);
		});
	},
	controlSocialMedia : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			var control = $(this);

			// Allow to set a fixed URL (eg: to share the website url), if not, take it from the block
			var url = control.data('shareurl') ? control.data('shareurl') : that.getBlockFilteringUrl(domain, pageSection, control, true);
			var title = control.data('sharetitle') ? control.data('sharetitle') : pop.Manager.documentTitle;

			var provider = control.data('provider');
			var settings = pop.c.SOCIALMEDIA[provider];
			var shareUrl = settings['shareURL'].replace(new RegExp('%url%', 'g'), encodeURIComponent(url)).replace(new RegExp('%title%', 'g'), encodeURIComponent(title));

			pop.Links.openSocialMedia(shareUrl);
		});
	},
	

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------


	getBlockFilteringUrl : function(domain, pageSection, control, use_pageurl) {

		var that = this;
		var targetBlock = $(control.data('blocktarget'));
		return pop.Manager.getBlockFilteringUrl(domain, pageSection, targetBlock, use_pageurl);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Controls, [/*'controlOpenAll', */'controlPrint', 'controlSocialMedia']);