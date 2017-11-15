"use strict";
(function($){
window.popUREAAL = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	isActionUpdatedUserMembership : function(args) {
	
		var that = this;

		var action = args.input;
		return (action == M.ACTION_USER_UPDATEDUSERMEMBERSHIP);
	},

	isActionJoinedCommunity : function(args) {
	
		var that = this;

		var action = args.input;
		return (action == M.ACTION_USER_JOINEDCOMMUNITY);
	},

	isObjectTypeUser : function(args) {
	
		var that = this;

		var object_type = args.input;
		return (object_type == M.OBJECTTYPE_USER);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popUREAAL, ['isActionUpdatedUserMembership', 'isActionJoinedCommunity', 'isObjectTypeUser']);