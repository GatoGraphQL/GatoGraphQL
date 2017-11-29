"use strict";
(function($){
window.popUserRole = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	filterByCommunity : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.change(function (e) {

			var checkbox = $(this);
			var user_id = checkbox.val();
			var communityTypeahead = $(checkbox.data('target'));

			if (checkbox.is(':checked')) {
				var user = checkbox.data('user-datum');
				popTypeaheadTriggerSelect.triggerSelect(domain, pageSection, block, communityTypeahead, user);
			}
			else {
				popTypeaheadTriggerSelect.removeSelected(communityTypeahead, user_id);
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popUserRole, ['filterByCommunity']);