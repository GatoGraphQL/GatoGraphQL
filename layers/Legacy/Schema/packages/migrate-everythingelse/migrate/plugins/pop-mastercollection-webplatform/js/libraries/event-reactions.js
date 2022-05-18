"use strict";
(function($){
window.pop.EventReactions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	resetOnSuccess : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		var pssId = pop.Manager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = pop.Manager.getSettingsId(block);				
			var blockFeedback = response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// skip-restore: allow the message feedback to still show
				pop.Manager.reset(domain, pageSection, block/*, {'skip-restore': true}*/);
			}
		});
	},

	destroyPageOnSuccess : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = pop.Manager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = pop.Manager.getSettingsId(block);				
			var blockFeedback = response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];
			var target = pop.Manager.getFrameTarget(pageSection);

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// get the destroyTime from the block
				var destroyTime = block.data('destroytime') || 0;
				setTimeout(function () {
					
					// Destroy this pageSectionPage
					pop.Manager.triggerDestroyTarget(pop.Manager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/, target);
				}, destroyTime);
			}
		});
	},

	closeFeedbackMessagesOnPageSectionOpen : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		pop.PageSectionManager.getGroup(pageSection).on('on.bs.pagesection-group:pagesection-'+pageSection.attr('id')+':opened', function() {

			// Needed to erase previous feedback messages. Eg: Reset password
			pop.Manager.closeFeedbackMessages(pageSection);
		});
	},

	closePageSectionOnSuccess : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = pop.Manager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = pop.Manager.getSettingsId(block);				
			var blockFeedback = response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// get the time from the block
				var closeTime = block.data('closetime') || 0;
				that.timeoutClosePageSection(pageSection, closeTime);
				// setTimeout(function () {
					
				// 	pop.PageSectionManager.close(pageSection);

				// 	// After closing, also delete all the messageFeedbacks within. This way, after logging in, it deletes the 'Logged in successful'
				// 	// message for the next time we try to log in. And we close all, so that if a 'Log out unsuccessful' message was there, it will be gone also
				// 	pop.Manager.closeFeedbackMessages(pageSection);
				// }, closeTime);
			}
		});
	},

	closePageSectionOnTabpaneShown : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.each(function() {

			var block = $(this);
			var pageSectionPage = pop.Manager.getPageSectionPage(block);

			pageSectionPage.on('shown.bs.tabpane', function() {

				pop.PageSectionManager.close(pageSection);
			});
			
			// Execute already since it is mostly used by the preloaded blocks, so gotta close also on initialization
			pop.PageSectionManager.close(pageSection);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	timeoutClosePageSection : function(pageSection, closeTime) {

		var that = this;
		setTimeout(function () {
			
			pop.PageSectionManager.close(pageSection);

			// After closing, also delete all the messageFeedbacks within. This way, after logging in, it deletes the 'Logged in successful'
			// message for the next time we try to log in. And we close all, so that if a 'Log out unsuccessful' message was there, it will be gone also
			pop.Manager.closeFeedbackMessages(pageSection);
		}, closeTime);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.EventReactions, ['closePageSectionOnTabpaneShown', 'resetOnSuccess', 'closeFeedbackMessagesOnPageSectionOpen', 'closePageSectionOnSuccess', 'destroyPageOnSuccess']);
