"use strict";
(function($){
window.pop.BlockGroupDataQuery = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initBlockGroupFilter : function(args) {

		var that = this;

		var pageSection = args.pageSection, blockGroup = args.block, targets = args.targets;
		var blocks = pop.Manager.getBlockGroupBlocks(blockGroup);

		//If the filter came loaded with a filtering value, already set these in the contained blocks filters
		targets.each(function(e) {
			
			// Copy the filter params to all contained blocks
			var filter = $(this);
			// Set all the blocks filters with the value of this one filter
			blocks.each(function() {

				var block = $(this);
				pop.Manager.setFilterBlockParams(pageSection, block, filter);
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
			var activeBlock = pop.Manager.getBlockGroupActiveBlock(blockGroup);
			pop.Manager.reload(pageSection, activeBlock);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	filterBlockGroup : function(pageSection, blockGroup, filter) {

		var that = this;

		var activeBlock = pop.Manager.getBlockGroupActiveBlock(blockGroup);
		var blocks = pop.Manager.getBlockGroupBlocks(blockGroup);

		// Set all the blocks filters with the value of this one filter
		blocks.each(function() {

			var block = $(this);

			pop.Manager.setFilterBlockParams(pageSection, block, filter);

			// Filter already the active block, add Refetch to all non-active blocks
			if (block.attr('id') == activeBlock.attr('id')) {
				pop.Manager.reload(pageSection, block);

				// Scroll Top to show the "Submitting" message
				pop.Manager.blockScrollTop(pageSection, block);
			}
			else {
				pop.BlockDataQuery.makeOneTimeRefetch(pageSection, block);
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BlockGroupDataQuery, ['initBlockGroupFilter', 'reloadBlockGroup']);
