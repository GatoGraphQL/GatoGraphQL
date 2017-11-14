(function($){
window.popAddEditPost = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	formCreatePostBlock : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block;

		// When posting a new post, and then the user logs out, we gotta close that tab. But not until the post is actually created
		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);		
		block.one('fetched', function(e, response) {
	
			that.execFormCreatePostBlock(domain, pageSection, block, response);
		});
	},

	manageStatus : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Set "disabled" to Published state
		that.execManageStatus(targets);

		// Check if the user changed the Status from Published and Submitted, then "Published" it must be disabled
		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);
		block.on('fetched', function(e, response) {
	
			var blockFeedback = response.feedback.block[pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				that.execManageStatus(targets);
			}
		});
	},

	//-------------------------------------------------
	// PROTECTED functions
	//-------------------------------------------------

	execFormCreatePostBlock : function(domain, pageSection, block, response) {
	
		var that = this;

		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);		
		var blockFeedback = response.feedback.block[pssId][bsId];

		// result = true means it was successful
		if (blockFeedback.result === true) {

			// If it was successful, execute the destroyPageOnUserLoggedOut function on it
			popEventReactions.execDestroyPageOnUserLoggedOut(domain, block);
		}
		else {

			// If not, try again
			block.one('fetched', function(e, response) {
				that.execFormCreatePostBlock(domain, pageSection, block, response);
			});
		}
	},

	execManageStatus : function(targets) {

		var that = this;

		// Set "disabled" to 'publish' state
		targets.each(function() {

			var input_status = $(this);
			if (input_status.val() == M.POSTSTATUS.PUBLISH) {

				// Disable "Pending"
				input_status.find('option[value="'+M.POSTSTATUS.PENDING+'"]').attr('disabled', 'disabled');
				input_status.find('option[value="'+M.POSTSTATUS.PUBLISH+'"]').attr('disabled', false);
			}
			else {

				// Disable "Published"
				input_status.find('option[value="'+M.POSTSTATUS.PUBLISH+'"]').attr('disabled', 'disabled');
				input_status.find('option[value="'+M.POSTSTATUS.PENDING+'"]').attr('disabled', false);
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popAddEditPost, ['formCreatePostBlock', 'manageStatus']);