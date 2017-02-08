(function($){
popUserAccount = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	initialuserdata: false,
	loggedin: false,
	id: null,
	name: '',
	avatar: '',
	url: '',
	roles: [],
	userattributes: [],

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function() {
	
		var t = this;

		// Important: this function below must be executed before gdUserLoginout.initDocument(elem); so it can catch the login/logout events
		t.checkpointInvalidateBlocks();

		// Comment Leo 07/09/2015: Do not log in from the cookie, since their value might be stale
		// Instead, "click" on the "Logged-in User Data" page, it will retrieve all info for the logged in user, if there is such
		// Do it only if the there is no userInfo sent through the feedback, so must query explicitly the server about this info
		if (t.initialuserdata) {

			// t.initialuserdata is a flag so we identify the first log in. Needed for not reloading data initially that must not be reloaded. Eg: TPP Debate Create OpinionatedVoted block. (It reloads its own data initially)
			t.fetchLoggedInUserData();
		}
	},
	pageSectionInitialized : function(args) {
	
		var t = this;
		var pageSection = args.pageSection;

		pageSection.on('fetched', function(e, options, url) {

			t.feedbackLoginOut(false);
		});
	},
	blockInitialized : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, block = args.block;

		block.on('fetched', function(e) {
			
			t.feedbackLoginOut(false);
		});
	},
	loadAvatar : function(args) {
	
		var t = this;
		var targets = args.targets;

		targets.attr('src', t.avatar);
	},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	initialLogin : function() {

		var t = this;

		// Comment Leo 07/03/2-16: function "initialLogin" is called by popManager inmediately
		// after initializing the JSON. This way, logging the user in comes before rendering the html, which is needed for
		// the condition wrappers which depend on the user logged in data, such as "isUserIdSameAsLoggedInUser"
		// Log-in from the feedback values
		feedbackAvailable = t.feedbackLoginOut(true);

		// If there is not feedback available, then we gotta send the "/loggedinuser-data" request.
		// Var t.initialuserdata acts as a flag to trigger the request on documentInitialized()
		t.initialuserdata = !feedbackAvailable;
	},

	fetchLoggedInUserData : function(options) {
	
		var t = this;
		options = options || {};
		
		// This will change the loading message target or, if it doesn't exist, show no loading message
		options['loadingmsg-target'] = M.USERLOGGEDIN_LOADINGMSG_TARGET;
		options.silentDocument = true;

		popManager.fetch(M.USERLOGGEDIN_DATA_PAGEURL, options);
	},

	isLoggedIn : function() {

		var t = this;
		return t.loggedin;
	},

	updateUserInfo : function(id, name, avatar, url, roles, userattributes) {
	
		var t = this;

		var updates = {};

		// Update name/avatar/url (just in case it was modified, or the cookie was lost)
		if (t.name != name) {
			
			t.name = name;
			$('.pop-user-name').text(t.name);

			updates.name = t.name; 
		}
		// For the avatar, also ask for the id:
		// if the user logged in/out, already change the avatar. This is because the default avatar
		// can be the same with user-not-logged-in avatar, so in that case, a user with default avatar logs in, and there would be no change. Then the change comes from a different id
		if (t.id != id || t.avatar != avatar) {

			t.id = id;
			t.avatar = avatar;
			$('.pop-user-avatar').attr('src', t.avatar);

			updates.avatar = t.avatar;
		}
		if (t.url != url) {

			t.url = url;
			$('.pop-user-url').attr('href', t.url);

			updates.url = t.url;
		}
		// Taken from https://stackoverflow.com/questions/1773069/using-jquery-to-compare-two-arrays-of-javascript-objects
		var eq_roles = $(t.roles).not(roles).length === 0 && $(roles).not(t.roles).length === 0;
		if (!eq_roles) {
		// if (t.roles != roles) {

			t.roles = roles;

			// Add all role classes to the body (remove all other ones first)
			$.each(M.ROLES, function(index, role) {

				$(document.body).removeClass(role);
			});
			$.each(t.roles, function(index, role) {

				$(document.body).addClass(role);
			});

			updates.roles = t.roles;
		}

		var eq_userattributes = $(t.userattributes).not(userattributes).length === 0 && $(userattributes).not(t.userattributes).length === 0;
		if (!eq_userattributes) {

			t.userattributes = userattributes;

			// Add all role classes to the body (remove all other ones first)
			$.each(M.USERATTRIBUTES, function(index, userattribute) {

				$(document.body).removeClass(userattribute);
			});
			$.each(t.userattributes, function(index, userattribute) {

				$(document.body).addClass(userattribute);
			});

			updates.userattributes = t.userattributes;
		}

		// Trigger an event if there were changes
		if (!$.isEmptyObject(updates)) {

			updates.id = id;
			$(document).triggerHandler('user:updated', [updates]);
		}
	},

	loginout : function(source, loggedin, id, name, avatar, url, roles, userattributes) {
	
		var t = this;

		t.updateUserInfo(id, name, avatar, url, roles, userattributes);

		// Login/out
		if (t.loggedin != loggedin) {

			t.loggedin = loggedin;

			var name, body = $(document.body);
			if (t.loggedin) {					

				// Save the user id in the DOM, so we can remove the class when the user logs out (we won't have the ID then)
				body.data('loggedinuser-id', id).addClass('loggedin user-'+id);
				name = 'user:loggedin';
			}
			else {
				
				body.removeClass('loggedin').removeClass('user-'+body.data('loggedinuser-id')).data('loggedinuser-id', '');
				name = 'user:loggedout';
			}

			// Close all "checkpoint" feedback messages
			if (source != 'initialfeedback') {
				t.closeCheckpointMessages();
			}

			// Trigger handler
			$(document).triggerHandler(name, [source]);
			$(document).triggerHandler('user:loggedinout', [source]);
		}
	},

	feedbackLoginOut : function(initial) {
	
		var t = this;

		// Check the TopLevel feedback if the User information was fetched. If so,
		// check if login/logout needed based on current and new state
		var topLevelFeedback = popManager.getTopLevelFeedback();
		var userInfo = topLevelFeedback[M.DATALOAD_USER];
		if (typeof userInfo != 'undefined') {

			var loggedin, id, name, avatar, url;
			loggedin = userInfo[M.DATALOAD_USER_LOGGEDIN];
			id = userInfo[M.DATALOAD_USER_ID];
			name = userInfo[M.DATALOAD_USER_NAME];
			avatar = userInfo[M.DATALOAD_USER_AVATAR];
			url = userInfo[M.DATALOAD_USER_URL];
			roles = userInfo[M.DATALOAD_USER_ROLES];
			userattributes = userInfo[M.DATALOAD_USER_ATTRIBUTES];

			var source = 'feedback';
			if (initial) {
				source = 'initialfeedback';
			}
			else if (t.initialuserdata) {
				
				// This could be either from M.USERLOGGEDIN_DATA_PAGEURL coming back, or from a block that was faster.
				// Sometimes the block response comes back before the request to M.USERLOGGEDIN_DATA_PAGEURL
				// So in that case, it's a block the one "logging in" the user
				source = 'initialuserdata';
				t.initialuserdata = false;
			}

			t.loginout(source, loggedin, id, name, avatar, url, roles, userattributes);

			// Return true: the feedback with user info is available
			return true;
		}		

		// Return false: not logged in because feedback not available, so user might be logged in but we don't know
		return false;
	},

	checkpointInvalidateBlocks : function() {

		var t = this;

		// When the user logs in, invalidate all the blocks which have fetched its data from previous sessions (users)
		$(document).on('user:loggedin', function(e, source) {

			// Do not do it when the user was already logged in => source = 'cookie'
			// If so, it will re-fetch the initial content, however this has already been loaded, no need to re-fetch it
			// (eg: starting on /my-events)
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}

			t.closeCheckpointMessages();
		});
	},

	closeCheckpointMessages : function() {

		var t = this;

		// Close all 'pop-messagefeedback.checkpoint' messages (if before it said user must log in, then hide it)
		jQuery(document).ready( function($) {
			$('.alert.in.pop-messagefeedback.checkpoint').removeClass('fade').alert('close');
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popUserAccount, ['documentInitialized', 'pageSectionInitialized', 'blockInitialized', 'loadAvatar']);