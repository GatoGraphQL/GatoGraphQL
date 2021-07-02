"use strict";
(function($){
window.pop.UserRolesAccount = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	
	getUserAccountInitialSessionObject : function(args) {
	
		var that = this;
		args.object.roles = [];
	},

	updateUserAccountInfo : function(args) {
	
		var that = this;
		var domain = args.domain, userInfo = args.userInfo;
		var domainId = getDomainId(domain);

		var roles = userInfo[pop.c.DATALOAD_USER_ROLES];


		// Taken from https://stackoverflow.com/questions/1773069/using-jquery-to-compare-two-arrays-of-javascript-objects
		var eq_roles = $(pop.UserAccount.sessions[domain].roles).not(roles).length === 0 && $(roles).not(pop.UserAccount.sessions[domain].roles).length === 0;
		if (!eq_roles) {

			pop.UserAccount.sessions[domain].roles = roles;

			// Add all role classes to the body (remove all other ones first)
			$.each(pop.c.ROLES, function(index, role) {

				$(document.body).removeClass(role+'-'+domainId);
			});
			$.each(pop.UserAccount.sessions[domain].roles, function(index, role) {

				$(document.body).addClass(role+'-'+domainId);
			});

			args.updates.roles = roles;
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.UserRolesAccount, ['getUserAccountInitialSessionObject', 'updateUserAccountInfo']);
