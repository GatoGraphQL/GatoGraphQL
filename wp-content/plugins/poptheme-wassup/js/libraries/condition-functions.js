(function($){
popConditionFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	isUserIdSameAsLoggedInUser : function(args) {
	
		var t = this;
		var domain = args.domain;

		if (popUserAccount.isLoggedIn(domain)) {

			var user_id = args.input;
			return (popUserAccount.getLoggedInUserId(domain) == user_id);
		}
		
		return false;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popConditionFunctions, ['isUserIdSameAsLoggedInUser']);
