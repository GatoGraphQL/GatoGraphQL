(function($){
popUREAAL = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	isActionUpdatedUserMembership : function(args) {
	
		var t = this;

		var action = args.input;
		return (action == M.ACTION_USER_UPDATEDUSERMEMBERSHIP);
	},

	isActionJoinedCommunity : function(args) {
	
		var t = this;

		var action = args.input;
		return (action == M.ACTION_USER_JOINEDCOMMUNITY);
	},

	isObjectTypeUser : function(args) {
	
		var t = this;

		var object_type = args.input;
		return (object_type == M.OBJECTTYPE_USER);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popUREAAL, ['isActionUpdatedUserMembership', 'isActionJoinedCommunity', 'isObjectTypeUser']);