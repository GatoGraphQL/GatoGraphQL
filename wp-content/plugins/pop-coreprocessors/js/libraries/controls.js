"use strict";
(function($){
window.popControls = {

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

	// 		var url = popManager.getBlockFilteringUrl(pageSection, targetBlock);
	// 		popManager.fetch(url, {target: target});
	// 	});
	// },
	controlPrint : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			var control = $(this);
			var url = that.getBlockFilteringUrl(domain, pageSection, control, true);
			url = popManager.getPrintUrl(url);
			popLinks.openPrint(url);
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
			var title = control.data('sharetitle') ? control.data('sharetitle') : popManager.documentTitle;

			var provider = control.data('provider');
			var settings = M.SOCIALMEDIA[provider];
			var shareUrl = settings['share-url'].replace(new RegExp('%url%', 'g'), encodeURIComponent(url)).replace(new RegExp('%title%', 'g'), encodeURIComponent(title));

			popLinks.openSocialMedia(shareUrl);
		});
	},
	

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------


	getBlockFilteringUrl : function(domain, pageSection, control, use_pageurl) {

		var that = this;
		var targetBlock = $(control.data('blocktarget'));
		return popManager.getBlockFilteringUrl(domain, pageSection, targetBlock, use_pageurl);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popControls, [/*'controlOpenAll', */'controlPrint', 'controlSocialMedia']);