"use strict";
(function($){
window.popBlockDataQueryUserState = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	refetchBlockOnUserLoggedIn : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		$(document).one('user:loggedin:'+domain, function(e, source) {

			if (source == 'initialfeedback') {
				return;
			}

			that.execRefetchBlock(pageSection, targets);
		});
	},

	nonendingRefetchBlockOnUserLoggedIn : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		that.execNonendingRefetchBlockOnUserEvent(pageSection, targets, 'user:loggedin:'+domain);
	},

	nonendingRefetchBlockOnUserLoggedInOut : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		that.execNonendingRefetchBlockOnUserEvent(pageSection, targets, 'user:loggedinout:'+domain);
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	execNonendingRefetchBlockOnUserEvent : function(pageSection, targets, handler) {

		var that = this;
		$(document).on(handler, function(e, source) {

			// Ask for 'initialuserdata' because some blocks will update themselves to load the content,
			// and will not depend on the user loggedin data. eg: Create OpinionatedVoted Block for the TPP Website
			// So if the source was that initial data, dismiss, otherwise it will trigger the URL load once again
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}
			
			that.execRefetchBlock(pageSection, targets);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBlockDataQueryUserState, ['refetchBlockOnUserLoggedIn', 'nonendingRefetchBlockOnUserLoggedIn', 'nonendingRefetchBlockOnUserLoggedInOut']);
