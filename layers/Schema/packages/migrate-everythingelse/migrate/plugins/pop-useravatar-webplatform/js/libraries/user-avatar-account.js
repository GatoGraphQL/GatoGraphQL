"use strict";
(function($){
window.pop.UserAvatarAccount = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	
	loadLoggedInUserAvatar : function(args) {
	
		var that = this;
		var domain = args.domain, targets = args.targets;

		targets.attr('src', pop.UserAccount.sessions[domain].avatar);
	},

	getUserAccountInitialSessionObject : function(args) {
	
		var that = this;
		args.object.avatar = '';
	},

	updateUserAccountInfo : function(args) {
	
		var that = this;
		var domain = args.domain, userInfo = args.userInfo;
		var domainId = getDomainId(domain);

		var id = userInfo[pop.c.DATALOAD_USER_ID],
			avatar = userInfo[pop.c.DATALOAD_USER_AVATAR];

		// For the avatar, also ask for the id:
		// if the user logged in/out, already change the avatar. This is because the default avatar
		// can be the same with user-not-logged-in avatar, so in that case, a user with default avatar logs in, and there would be no change. Then the change comes from a different id
		if (pop.UserAccount.sessions[domain].id != id || pop.UserAccount.sessions[domain].avatar != avatar) {

			pop.UserAccount.sessions[domain].id = id;
			pop.UserAccount.sessions[domain].avatar = avatar;
			$('.pop-user-avatar.'+domainId).attr('src', avatar);

			args.updates.avatar = avatar;
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.UserAvatarAccount, ['loadLoggedInUserAvatar', 'getUserAccountInitialSessionObject', 'updateUserAccountInfo']);
