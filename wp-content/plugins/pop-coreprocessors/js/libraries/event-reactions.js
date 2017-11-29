"use strict";
(function($){
window.popEventReactions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	resetOnSuccess : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = popManager.getSettingsId(block);				
			var blockFeedback = response.feedback.block[pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// skip-restore: allow the message feedback to still show
				popManager.reset(domain, pageSection, block, {'skip-restore': true});
			}
		});
	},

	destroyPageOnSuccess : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = popManager.getSettingsId(block);				
			var blockFeedback = response.feedback.block[pssId][bsId];
			var target = popManager.getFrameTarget(pageSection);

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// get the destroyTime from the block
				var destroyTime = block.data('destroytime') || 0;
				setTimeout(function () {
					
					// Destroy this pageSectionPage
					popManager.triggerDestroyTarget(popManager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/, target);
				}, destroyTime);
			}
		});
	},

	closeMessageFeedbacksOnPageSectionOpen : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		popPageSectionManager.getGroup(pageSection).on('on.bs.pagesection-group:pagesection-'+pageSection.attr('id')+':opened', function() {

			// Needed to erase previous feedback messages. Eg: Reset password
			popManager.closeMessageFeedbacks(pageSection);
		});
	},

	closePageSectionOnSuccess : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = popManager.getSettingsId(block);				
			var blockFeedback = response.feedback.block[pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// get the time from the block
				var closeTime = block.data('closetime') || 0;
				that.timeoutClosePageSection(pageSection, closeTime);
				// setTimeout(function () {
					
				// 	popPageSectionManager.close(pageSection);

				// 	// After closing, also delete all the messageFeedbacks within. This way, after logging in, it deletes the 'Logged in successful'
				// 	// message for the next time we try to log in. And we close all, so that if a 'Log out unsuccessful' message was there, it will be gone also
				// 	popManager.closeMessageFeedbacks(pageSection);
				// }, closeTime);
			}
		});
	},

	closePageSectionOnTabpaneShown : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.each(function() {

			var block = $(this);
			var pageSectionPage = popManager.getPageSectionPage(block);

			pageSectionPage.on('shown.bs.tabpane', function() {

				popPageSectionManager.close(pageSection);
			});
			
			// Execute already since it is mostly used by the replicable blocks, so gotta close also on initialization
			popPageSectionManager.close(pageSection);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	timeoutClosePageSection : function(pageSection, closeTime) {

		var that = this;
		setTimeout(function () {
			
			popPageSectionManager.close(pageSection);

			// After closing, also delete all the messageFeedbacks within. This way, after logging in, it deletes the 'Logged in successful'
			// message for the next time we try to log in. And we close all, so that if a 'Log out unsuccessful' message was there, it will be gone also
			popManager.closeMessageFeedbacks(pageSection);
		}, closeTime);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popEventReactions, ['closePageSectionOnTabpaneShown', 'resetOnSuccess', 'closeMessageFeedbacksOnPageSectionOpen', 'closePageSectionOnSuccess', 'destroyPageOnSuccess']);
