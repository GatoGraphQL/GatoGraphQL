"use strict";
(function($){
window.popBlockGroupDataQuery = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initBlockGroupFilter : function(args) {

		var that = this;

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
			that.filterBlockGroup(pageSection, blockGroup, filter) ;
		});
	},

	reloadBlockGroup : function(args) {

		var that = this;

		var pageSection = args.pageSection, blockGroup = args.block, targets = args.targets;
		targets.click(function(e) {
			
			e.preventDefault();

			// Copy the filter params to all contained blocks
			var activeBlock = popManager.getBlockGroupActiveBlock(blockGroup);
			popManager.reload(pageSection, activeBlock);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	filterBlockGroup : function(pageSection, blockGroup, filter) {

		var that = this;

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
popJSLibraryManager.register(popBlockGroupDataQuery, ['initBlockGroupFilter', 'reloadBlockGroup']);
