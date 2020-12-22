"use strict";
(function($){
window.pop.BlockDataQueryUserState = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	blockHandleDisabledLayer : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		block.on('fetched', function(e, response) {

			// Add/Remove the disabled layer
			var disabledLayer = pop.Manager.getBlockDisabledLayer(block);
			var jsFeedback = pop.Manager.getJsFeedback(domain, pageSection, block);
			if (jsFeedback['checkpoint-failed']) {

				disabledLayer.removeClass('hidden');
			}
			else {

				disabledLayer.addClass('hidden');
			}
		});
	},

	refetchBlockOnUserLoggedIn : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		that.execRefetchBlockOnUserEvent(domain, pageSection, targets, 'user:loggedin:'+domain, true);
	},

	// nonendingRefetchBlockOnUserLoggedIn : function(args) {

	// 	var that = this;
	// 	var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
	// 	that.execNonendingRefetchBlockOnUserEvent(pageSection, targets, 'user:loggedin:'+domain);
	// },

	refetchBlockOnUserLoggedInOut : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		that.execRefetchBlockOnUserEvent(domain, pageSection, targets, 'user:loggedinout:'+domain);
	},

	//-------------------------------------------------
	// PRIVATE FUNCTIONS
	//-------------------------------------------------

	execRefetchBlockOnUserEvent : function(domain, pageSection, block, event, once) {

		var that = this;		
		$(document).on(event, function(e, source) {

			// Ask for 'initialuserdata' because some blocks will update themselves to load the content,
			// and will not depend on the user loggedin data. eg: Create Stance Block for the TPP Website
			// So if the source was that initial data, dismiss, otherwise it will trigger the URL load once again
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}
			
			var options = {
				'show-disabled-layer': true,
			};
			pop.BlockDataQuery.execRefetchBlock(pageSection, block, options);

			// If execute only once, remove event handler. Taken from https://api.jquery.com/one/
			if (once) {
				$(this).off(e);
			}
		});
	},

	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BlockDataQueryUserState, ['blockHandleDisabledLayer', 'refetchBlockOnUserLoggedIn'/*, 'nonendingRefetchBlockOnUserLoggedIn'*/, 'refetchBlockOnUserLoggedInOut']);
