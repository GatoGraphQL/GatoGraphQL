"use strict";
function sociallogin_user_loggedin_callback() {

	// If there is a logged-in user, then close the pageSection
	pop.SocialLogin.userLoggedInCallback();
}
(function($){
window.pop.SocialLogin = {

	// What is the log in block, to enable the Disabled Layer on top of it
	loginBlock: null,
	loginURL: null,
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	socialLoginNetworkLink : function(args) {

		var that = this;
		var targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();

			var link = $(this);
			var popupurl = link.data('url');

			// Save the loginBlock
			var block = pop.Manager.getBlock(link);
			that.loginBlock = $(block.data('loginblock'));

			window.open(
				popupurl,
				"hybridauth_social_sing_on", 
				"location=1,status=0,scrollbars=0,width=600,height=400"
			); 
		});
	},

	//-------------------------------------------------
	// PROTECTED functions
	//-------------------------------------------------

	userLoggedInCallback : function() {

		var that = this;

		if (that.loginURL && pop.UserAccount.isLoggedIn(getDomain(that.loginURL))) {

			// If there is a logged-in user, then close the pageSection
			if (that.loginBlock) {

				var pageSection = pop.Manager.getPageSection(that.loginBlock);
				var closeTime = pop.c.SOCIALLOGIN_LOGINUSER_CLOSETIME || 1500;
				pop.EventReactions.timeoutClosePageSection(pageSection, closeTime);
			}
		}
	},

	fetchLoggedInUserData : function(config) {

		var that = this;

		// Send the params along the request, WSL will log-in the user from these
		// Params eg: {action: "wordpress_social_authenticated", provider: "Facebook"}
		var options = {
			params: config
		}
		if (pop.SocialLogin.loginBlock) {

			// Disabled layer over the BLOCKGROUP_LOGIN
			options['disable-layer-for-block'] = pop.SocialLogin.loginBlock;

			// Callback to close the pageSection
			options['urlfetched-callbacks'] = [sociallogin_user_loggedin_callback];
		}

		// From the browser URL, induce what URL we are calling from all the available domains
		that.loginURL = null;
		var url = pop.BrowserHistory.getApplicationURL(window.location.href);
		var domain = getDomain(url);
		$.each(pop.c.USERLOGGEDIN_DATA_PAGEURLS, function(index, url) {

			// Check if this is the LoggedIn User Data URL for the browser domain
			if (url.startsWith(domain)) {

				// We found it!
				that.loginURL = url;
				return -1;
			}
		});

		if (that.loginURL) {

			// Execute the log-in to bring the data, it will also call the callback function to close the hover pageSection
			pop.UserAccount.fetchLoggedInUserData(that.loginURL, options);
		}
	}	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.SocialLogin, ['socialLoginNetworkLink']);
