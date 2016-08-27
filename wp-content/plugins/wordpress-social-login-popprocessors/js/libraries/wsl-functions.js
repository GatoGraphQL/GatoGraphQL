(function($){
popWSL = {

	// What is the log in block, to enable the Disabled Layer on top of it
	loginBlock: null,
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	wslNetworkLink : function(args) {

		var t = this;
		var targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();

			// provider = $(this).data("provider");
			// popupurl = M.WSL_NETWORKPROVIDERS[provider];
			var link = $(this);
			popupurl = link.data('url');

			// Save the loginBlock
			var block = popManager.getBlock(link);
			t.loginBlock = $(block.data('loginblock'));

			window.open(
				// popupurl+"provider="+provider,
				popupurl,
				"hybridauth_social_sing_on", 
				"location=1,status=0,scrollbars=0,width=600,height=400"
				// "location=1,status=0,scrollbars=0,width=1000,height=600"
			); 
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popWSL, ['wslNetworkLink']);


//-------------------------------------------------
// Standard code
//-------------------------------------------------
function wsl_wordpress_social_login_callback() {

	// If there is a logged-in user, then close the pageSection
	if (popUserAccount.isLoggedIn()) {
		if (popWSL.loginBlock) {
			var pageSection = popManager.getPageSection(popWSL.loginBlock);
			var closeTime = M.WSL_LOGINUSER_CLOSETIME || 1500;
			popSystem.timeoutClosePageSection(pageSection, closeTime);
		}
	}
}
// Needed as replaced file wp-content/plugins/wordpress-social-login/assets/js/widget.js
window.wsl_wordpress_social_login = function(config) {

	// Send the params along the request, WSL will log-in the user from these
	// Params eg: {action: "wordpress_social_authenticated", provider: "Facebook"}
	var options = {
		params: config
	}
	if (popWSL.loginBlock) {
		// Disabled layer over the BLOCKGROUP_LOGIN
		options['disable-layer'] = popWSL.loginBlock;

		// Callback to close the pageSection
		options['urlfetched-callbacks'] = [wsl_wordpress_social_login_callback];
	}

	popUserAccount.fetchLoggedInUserData(options);
}