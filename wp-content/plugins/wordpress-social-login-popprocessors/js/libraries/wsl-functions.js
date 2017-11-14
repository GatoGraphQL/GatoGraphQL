(function($){
window.popWSL = {

	// What is the log in block, to enable the Disabled Layer on top of it
	loginBlock: null,
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	wslNetworkLink : function(args) {

		var that = this;
		var targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();

			// provider = $(this).data("provider");
			// popupurl = M.WSL_NETWORKPROVIDERS[provider];
			var link = $(this);
			popupurl = link.data('url');

			// Save the loginBlock
			var block = popManager.getBlock(link);
			that.loginBlock = $(block.data('loginblock'));

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
// global variable wsl_login_url
var wsl_login_url = '';
function wsl_wordpress_social_login_callback() {

	// If there is a logged-in user, then close the pageSection
	if (wsl_login_url && popUserAccount.isLoggedIn(getDomain(wsl_login_url))) {

		if (popWSL.loginBlock) {
			var pageSection = popManager.getPageSection(popWSL.loginBlock);
			var closeTime = M.WSL_LOGINUSER_CLOSETIME || 1500;
			popEventReactions.timeoutClosePageSection(pageSection, closeTime);
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

	// From the browser URL, induce what URL we are calling from all the available domains
	wsl_login_url = '';
	var url = popBrowserHistory.getApplicationURL(window.location.href);
	var domain = getDomain(url);
	$.each(M.USERLOGGEDIN_DATA_PAGEURLS, function(index, url) {

		// Check if this is the LoggedIn User Data URL for the browser domain
		if (url.startsWith(domain)) {

			// We found it!
			wsl_login_url = url;
			return -1;
		}
	});

	if (wsl_login_url) {

		// Execute the log-in to bring the data, it will also call the callback function to close the hover pageSection
		popUserAccount.fetchLoggedInUserData(wsl_login_url, options);
	}
}