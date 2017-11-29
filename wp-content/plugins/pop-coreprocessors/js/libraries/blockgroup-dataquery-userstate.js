"use strict";
(function($){
window.popBlockGroupDataQueryUserState = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	refetchBlockGroupOnUserLoggedIn : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		$(document).one('user:loggedin:'+domain, function(e, source) {

			// if (source == 'cookie' || source == 'initialfeedback') {
			if (source == 'initialfeedback') {
				return;
			}

			// Comment Leo 07/09/2015: Operate with the target ids, and not with the target itself
			// This is because, after calling pageSectionPage.remove(), the targets are not under the DOM,
			// but the reference still exists! So it doesn't know that it was removed
			// This happened when:
			// - User is logged out
			// - Calling a page with a onUserLoggedIn reload (eg: My Content)
			// - closing this tab
			// - Then logging in
			// producing a javascript error: Uncaught Error: Mempage not available
			targets.each(function() {
		
				// To double-check that the object still exists in the DOM and was not removed when doing popManager.destroyPageSectionPage
				var blockGroup = $('#'+$(this).attr('id'));
				if (blockGroup.length) {

					var blocks = popManager.getBlockGroupBlocks(blockGroup);
					var activeBlock = popManager.getBlockGroupActiveBlock(blockGroup);
					blocks.each(function() {

						var block = $(this);
						if (block.attr('id') == activeBlock.attr('id')) {
							popBlockDataQuery.refetchBlock(pageSection, block);
						}
						else {
							popBlockDataQuery.makeOneTimeRefetch(pageSection, block);
						}
					});
				}
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBlockGroupDataQueryUserState, ['refetchBlockGroupOnUserLoggedIn']);
