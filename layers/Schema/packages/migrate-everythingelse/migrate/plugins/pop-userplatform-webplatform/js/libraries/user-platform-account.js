"use strict";
(function($){
window.pop.UserPlatformAccount = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	
	getUserAccountInitialSessionObject : function(args) {
	
		var that = this;
		args.object.userattributes = [];
	},

	updateUserAccountInfo : function(args) {
	
		var that = this;
		var domain = args.domain, userInfo = args.userInfo;
		var domainId = getDomainId(domain);

		var userattributes = userInfo[pop.c.DATALOAD_USER_ATTRIBUTES];

		// User Attributes, customized for each user and hooked in by plugins
		// Taken from https://stackoverflow.com/questions/1773069/using-jquery-to-compare-two-arrays-of-javascript-objects
		var eq_userattributes = $(pop.UserAccount.sessions[domain].userattributes).not(userattributes).length === 0 && $(userattributes).not(pop.UserAccount.sessions[domain].userattributes).length === 0;
		if (!eq_userattributes) {

			pop.UserAccount.sessions[domain].userattributes = userattributes;

			$.each(pop.c.USERATTRIBUTES, function(index, userattribute) {

				$(document.body).removeClass(userattribute+'-'+domainId);
			});
			$.each(pop.UserAccount.sessions[domain].userattributes, function(index, userattribute) {

				$(document.body).addClass(userattribute+'-'+domainId);
			});

			args.updates.userattributes = userattributes;
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.UserPlatformAccount, ['getUserAccountInitialSessionObject', 'updateUserAccountInfo']);
