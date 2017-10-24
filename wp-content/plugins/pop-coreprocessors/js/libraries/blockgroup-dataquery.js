(function($){
popBlockGroupDataQuery = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initBlockGroupFilter : function(args) {

		var t = this;

		var pageSection = args.pageSection, blockGroup = args.block, targets = args.targets;
		// var blocks = popManager.getBlockGroupBlocks(blockGroup);

		//If the filter came loaded with a filtering value, already set these in the contained blocks filters
		targets.each(function(e) {
			
			// Copy the filter params to all contained blocks
			var filter = $(this);
			// Set all the blocks filters with the value of this one filter
			blocks.each(function() {

				var block = $(this);
				popManager.setFilterBlockParams(pageSection, block, filter);
			});
		});

		// Initialize Filters
		targets.submit(function(e) {
			
			e.preventDefault();

			// Copy the filter params to all contained blocks
			var filter = $(this);
			t.filterBlockGroup(pageSection, blockGroup, filter) ;
		});
	},

	reloadBlockGroup : function(args) {

		var t = this;

		var pageSection = args.pageSection, blockGroup = args.block, targets = args.targets;
		targets.click(function(e) {
			
			e.preventDefault();

			// Copy the filter params to all contained blocks
			var activeBlock = popManager.getBlockGroupActiveBlock(blockGroup);
			popManager.reload(pageSection, activeBlock);
		});
	},

	refetchBlockGroupOnUserLoggedIn : function(args) {

		var t = this;
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

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	filterBlockGroup : function(pageSection, blockGroup, filter) {

		var t = this;

		var activeBlock = popManager.getBlockGroupActiveBlock(blockGroup);
		var blocks = popManager.getBlockGroupBlocks(blockGroup);

		// Set all the blocks filters with the value of this one filter
		blocks.each(function() {

			var block = $(this);

			popManager.setFilterBlockParams(pageSection, block, filter);

			// Filter already the active block, add Refetch to all non-active blocks
			if (block.attr('id') == activeBlock.attr('id')) {
				popManager.reload(pageSection, block);
			}
			else {
				popBlockDataQuery.makeOneTimeRefetch(pageSection, block);
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBlockGroupDataQuery, ['initBlockGroupFilter', 'reloadBlockGroup', 'refetchBlockGroupOnUserLoggedIn']);
