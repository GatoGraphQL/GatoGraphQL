"use strict";
(function($){
window.pop.AddEditPost = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	formCreatePostBlock : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block;

		// When posting a new post, and then the user logs out, we gotta close that tab. But not until the post is actually created
		var pssId = pop.Manager.getSettingsId(pageSection);
		var bsId = pop.Manager.getSettingsId(block);		
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
		var pssId = pop.Manager.getSettingsId(pageSection);
		var bsId = pop.Manager.getSettingsId(block);
		block.on('fetched', function(e, response) {
	
			var blockFeedback = response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];

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

		var pssId = pop.Manager.getSettingsId(pageSection);
		var bsId = pop.Manager.getSettingsId(block);		
		var blockFeedback = response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];

		// result = true means it was successful
		if (blockFeedback.result === true) {

			// If it was successful, execute the destroyPageOnUserLoggedOut function on it
			pop.EventReactionsUserState.execDestroyPageOnUserLoggedOut(domain, block);
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
			if (input_status.val() == pop.c.POSTSTATUS.PUBLISH) {

				// Disable "Pending"
				input_status.find('option[value="'+pop.c.POSTSTATUS.PENDING+'"]').attr('disabled', 'disabled');
				input_status.find('option[value="'+pop.c.POSTSTATUS.PUBLISH+'"]').attr('disabled', false);
			}
			else {

				// Disable "Published"
				input_status.find('option[value="'+pop.c.POSTSTATUS.PUBLISH+'"]').attr('disabled', 'disabled');
				input_status.find('option[value="'+pop.c.POSTSTATUS.PENDING+'"]').attr('disabled', false);
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.AddEditPost, ['formCreatePostBlock', 'manageStatus']);