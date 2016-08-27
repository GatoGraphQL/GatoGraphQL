(function($){
popForms = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	formCreatePostBlock : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, block = args.block;

		// When posting a new post, and then the user logs out, we gotta close that tab. But not until the post is actually created
		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);		
		block.one('fetched', function(e, response) {
	
			t.execFormCreatePostBlock(pageSection, block, response);
		});
	},

	manageStatus : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Set "disabled" to Published state
		t.execManageStatus(targets);

		// Check if the user changed the Status from Published and Submitted, then "Published" it must be disabled
		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);
		block.on('fetched', function(e, response) {
	
			var blockFeedback = response.feedback.block[pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				t.execManageStatus(targets);
			}
		});
	},

	//-------------------------------------------------
	// PROTECTED functions
	//-------------------------------------------------

	execFormCreatePostBlock : function(pageSection, block, response) {
	
		var t = this;

		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);		
		var blockFeedback = response.feedback.block[pssId][bsId];

		// result = true means it was successful
		if (blockFeedback.result === true) {

			// If it was successful, execute the destroyPageOnUserLoggedOut function on it
			popSystem.execDestroyPageOnUserLoggedOut(block);
		}
		else {

			// If not, try again
			block.one('fetched', function(e, response) {
				t.execFormCreatePostBlock(pageSection, block, response);
			});
		}
	},

	execManageStatus : function(targets) {

		var t = this;

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
popJSLibraryManager.register(popForms, ['formCreatePostBlock', 'manageStatus']);