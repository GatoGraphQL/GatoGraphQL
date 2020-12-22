"use strict";
(function($){
window.pop.EventReactionsUserState = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	resetOnUserLogout : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		$(document).on('user:loggedout:'+domain, function(e, source) {

			targets.each(function() {
		
				// To double-check that the object still exists in the DOM and was not removed when doing pop.Manager.destroyPageSectionPage
				var block = $('#'+$(this).attr('id'));
				if (block.length) {

					// 'delete-dataset': needed to erase the personal information of the logged in user. Eg: Add Stance, where it was showing the Stance by the user.
					// pop.Manager.reset(pageSection, block, {'delete-dataset': true});
					pop.Manager.reset(domain, pageSection, block);
				}
			});
		});
	},

	destroyPageOnUserLoggedOut : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		// Do it only after the pageSection has been initialized
		// This is so that the pageSectionPage doesn't get destroyed when having loaded that one! That one will give the info the user is not logged in
		// So now, it first destroys all pageSectionPages, and then sets the handler for this one
		// This will execute after that.feedbackLoginOut in user-account.js->pageSectionInitialized
		pageSection.one('completed', function() {

			that.execDestroyPageOnUserLoggedOut(domain, targets);
		});
	},

	// destroyPageOnUserNoRole : function(args) {

	// 	var that = this;
	// 	var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

	// 	pageSection.one('completed', function() {
		
	// 		that.execDestroyPageOnUserNoRole(domain, pageSection, targets);
	// 	});
	// },

	deleteBlockFeedbackValueOnUserLoggedInOut : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block;
		$(document).on('user:loggedinout:'+domain, function(e, source) {

			// Ask for 'initialuserdata' because some blocks will update themselves to load the content,
			// and will not depend on the user loggedin data. eg: Create Stance Block for the TPP Website
			// So if the source was that initial data, dismiss, otherwise it will trigger the URL load once again
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}
			
			// Deleting values is needed for the Notifications: when creating a user account, it will create notification "Welcome!",
			// but to fetch it we gotta delete param `hist_time` with value from previous fetching of notifications
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block);
			var keys = jsSettings['user:loggedinout-deletefeedbackvalue'] || [];
			if (keys.length) {
				
				var blockFeedback = pop.Manager.getBlockFeedback(domain/*pop.Manager.getBlockTopLevelDomain(block)*/, pageSection, block);
				$.each(keys, function(index, keyLevels) {

					// each param in params is an array of levels, to go down the blockParams to delete it (eg: 1 param will be ['params', 'hist_time'] to delete blockParams['params']['hist_time'])
					// Go down to the last level
					var feedbackLevel = blockFeedback;
					for (var i = 0; i < (keyLevels.length)-1; i++) { 
						feedbackLevel = feedbackLevel[keyLevels[i]];
					}

					// Delete that last level
					delete feedbackLevel[keyLevels[(keyLevels.length)-1]];
				});
			}

		});
	},

	scrollTopOnUserLoggedInOut : function (args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		$(document).on('user:loggedinout:'+domain, function(e, source) {

			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}

			targets.each(function() {
				var target = $(this);

				// Comment Leo 15/02/2017: Make sure the target still exists, because it may have been destroyed already but the hook still stays on
				if ($('#'+target.attr('id')).length) {
					var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, target);
					var animate = jsSettings['scrolltop-animate'] || false;
					pop.Manager.scrollToElem(target, target, animate);
				}
			});
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	execDestroyPageOnUserLoggedOut : function(domain, targets) {

		var that = this;

		$(document).one('user:loggedout:'+domain, function(e, source) {

			targets.each(function() {
		
				// To double-check that the object still exists in the DOM and was not removed when doing pop.Manager.destroyPageSectionPage
				var block = $('#'+$(this).attr('id'));
				if (block.length) {
					var pageSection = pop.Manager.getPageSection(block);
					var target = pop.Manager.getFrameTarget(pageSection);
					pop.Manager.triggerDestroyTarget(pop.Manager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/, target);
				}
			});
		});
	},

	// execDestroyPageOnUserNoRole : function(domain, pageSection, block) {

	// 	var that = this;		
	// 	var neededRole = block.data('neededrole');
		
	// 	$(document).one('user:updated:'+domain, function(e, updates) {

	// 		// To double-check that the object still exists in the DOM and was not removed when doing pop.Manager.destroyPageSectionPage
	// 		block = $('#'+block.attr('id'));
	// 		if (block.length) {

	// 			// If the roles where updated, check the needed role is still there. If not, destroy the page
	// 			// Needed for when a Community sets "Do you accept members?" in false, then it's not a community anymore, then destroy "My Members" page
	// 			if (updates.roles && updates.roles.indexOf(neededRole) == -1) {
					
	// 				var target = pop.Manager.getFrameTarget(pageSection);
	// 				pop.Manager.triggerDestroyTarget(pop.Manager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/, target);
	// 			}
	// 			else {

	// 				// Re-add the event handler
	// 				that.execDestroyPageOnUserNoRole(domain, pageSection, block);
	// 			}
	// 		}
	// 	});
	// },
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.EventReactionsUserState, ['resetOnUserLogout', 'destroyPageOnUserLoggedOut'/*, 'destroyPageOnUserNoRole'*/, 'deleteBlockFeedbackValueOnUserLoggedInOut', 'scrollTopOnUserLoggedInOut']);
