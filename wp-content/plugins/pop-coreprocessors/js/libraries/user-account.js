(function($){
popUserAccount = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	sessions: {},
	// initialuserdata: false,
	// loggedin: false,
	// id: null,
	// name: '',
	// avatar: '',
	// url: '',
	// roles: [],
	// userattributes: [],

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	
	initDocument : function(args) {
	
		var t = this;
		var domain = args.domain;

		// Log in the user immediately, before rendering the HTML. This way, conditional wrappers can access the state of the user
		// being logged in, such as for "isUserIdSameAsLoggedInUser"
		t.initialLogin(domain);

		// // Create the vars for all the domain
		// $.each(M.USERLOGGEDIN_DATA_PAGEURLS, function(index, url) {

		// 	var domain = getDomain(url);
		// 	t.initDomain(domain);
		// });
	},

	initDomain : function(args) {
	
		var t = this;
		var domain = args.domain;

		// Initialize the session for that domain
		t.sessions[domain] = {
			initialuserdata: true,
			loggedin: false,
			id: null,
			name: '',
			avatar: '',
			url: '',
			roles: [],
			userattributes: [],
		}
	},

	documentInitialized : function(args) {
	
		var t = this;
		var domain = args.domain;

		// Important: this function below must be executed before gdUserLoginout.initDocument(elem); so it can catch the login/logout events
		t.checkpointInvalidateBlocks();

		// Fetch the user logged in data, for each non-initialized domain
		t.fetchAllDomainsLoggedInUserData();
	},
	pageSectionInitialized : function(args) {
	
		var t = this;
		var pageSection = args.pageSection;

		pageSection.on('fetched', function(e, options, url, domain) {

			t.feedbackLoginOut(domain, false);
		});
	},
	blockInitialized : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, block = args.block;

		block.on('fetched', function(e, response, action, domain) {
			
			// var domain = getDomain(block.data('toplevel-url'));
			t.feedbackLoginOut(domain, false);
		});
	},
	loadAvatar : function(args) {
	
		var t = this;
		var domain = args.domain, targets = args.targets;

		targets.attr('src', t.sessions[domain].avatar);
	},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	initialLogin : function(domain) {

		var t = this;

		// Comment Leo 07/03/2-16: function "initialLogin" is called by popManager inmediately
		// after initializing the JSON. This way, logging the user in comes before rendering the html, which is needed for
		// the condition wrappers which depend on the user logged in data, such as "isUserIdSameAsLoggedInUser"
		// Log-in from the feedback values
		// It is valid only for the local domain, not for external domains!
		feedbackAvailable = t.feedbackLoginOut(domain, true);

		// If there is not feedback available, then we gotta send the "/loggedinuser-data" request.
		// Var t.initialuserdata acts as a flag to trigger the request on documentInitialized()
		t.sessions[domain].initialuserdata = !feedbackAvailable;
	},

	fetchAllDomainsLoggedInUserData : function(options) {
	
		var t = this;

		// Can fetch many pages, from different domains
		$.each(M.USERLOGGEDIN_DATA_PAGEURLS, function(index, url) {

			// Comment Leo 07/09/2015: Do not log in from the cookie, since their value might be stale
			// Instead, fetch the "Logged-in User Data" page, it will retrieve all info for the logged in user, if there is such
			// Do it only if the there is no userInfo sent through the feedback, so must query explicitly the server about this info
			// Fetch if either is another domain, or it is this domain (initDomain called on it) and has specified that needs the initialuserdata
			var domain = getDomain(url);
			if (t.sessions[domain].initialuserdata) {

				// t.initialuserdata is a flag so we identify the first log in. Needed for not reloading data initially that must not be reloaded. Eg: TPP Debate Create OpinionatedVoted block. (It reloads its own data initially)
				t.fetchLoggedInUserData(url, options);
			}
		});
	},

	fetchLoggedInUserData : function(url, options) {
	
		var t = this;
		options = options || {};
		
		// This will change the loading message target or, if it doesn't exist, show no loading message
		options['loadingmsg-target'] = M.USERLOGGEDIN_LOADINGMSG_TARGET;
		options.silentDocument = true;

		popManager.fetch(url, options);
	},

	isLoggedInAnyDomain : function() {

		var t = this;

		var ret = false;
		$.each(t.sessions, function(domain, state) {

			if (state.loggedin) {
				ret = true;
				return -1;
			}
		});
		return ret;
	},

	isLoggedIn : function(domain) {

		var t = this;
		return t.sessions[domain].loggedin;
	},

	updateUserInfo : function(domain, id, name, avatar, url, roles, userattributes) {
	
		var t = this;

		var updates = {};
		var domainId = getDomainId(domain);

		// Update name/avatar/url (just in case it was modified, or the cookie was lost)
		if (t.sessions[domain].name != name) {
			
			t.sessions[domain].name = name;
			$('.pop-user-name.'+domainId).text(name);

			updates.name = name; 
		}
		// For the avatar, also ask for the id:
		// if the user logged in/out, already change the avatar. This is because the default avatar
		// can be the same with user-not-logged-in avatar, so in that case, a user with default avatar logs in, and there would be no change. Then the change comes from a different id
		if (t.sessions[domain].id != id || t.sessions[domain].avatar != avatar) {

			t.sessions[domain].id = id;
			t.sessions[domain].avatar = avatar;
			$('.pop-user-avatar.'+domainId).attr('src', avatar);

			updates.avatar = avatar;
		}
		if (t.sessions[domain].url != url) {

			t.sessions[domain].url = url;
			$('.pop-user-url.'+domainId).attr('href', url);

			updates.url = url;
		}
		// Taken from https://stackoverflow.com/questions/1773069/using-jquery-to-compare-two-arrays-of-javascript-objects
		var eq_roles = $(t.sessions[domain].roles).not(roles).length === 0 && $(roles).not(t.sessions[domain].roles).length === 0;
		if (!eq_roles) {

			t.sessions[domain].roles = roles;

			// Add all role classes to the body (remove all other ones first)
			$.each(M.ROLES, function(index, role) {

				$(document.body).removeClass(role+'-'+domainId);
			});
			$.each(t.sessions[domain].roles, function(index, role) {

				$(document.body).addClass(role+'-'+domainId);
			});

			updates.roles = roles;
		}

		var eq_userattributes = $(t.sessions[domain].userattributes).not(userattributes).length === 0 && $(userattributes).not(t.sessions[domain].userattributes).length === 0;
		if (!eq_userattributes) {

			t.sessions[domain].userattributes = userattributes;

			// Add all role classes to the body (remove all other ones first)
			$.each(M.USERATTRIBUTES, function(index, userattribute) {

				$(document.body).removeClass(userattribute+'-'+domainId);
			});
			$.each(t.sessions[domain].userattributes, function(index, userattribute) {

				$(document.body).addClass(userattribute+'-'+domainId);
			});

			updates.userattributes = userattributes;
		}

		// Trigger an event if there were changes
		if (!$.isEmptyObject(updates)) {

			updates.id = id;
			$(document).triggerHandler('user:updated', [updates, domain]);
			$(document).triggerHandler('user:updated:'+domain, [updates]);
		}
	},

	loginout : function(source, domain, loggedin, id, name, avatar, url, roles, userattributes) {
	
		var t = this;

		t.updateUserInfo(domain, id, name, avatar, url, roles, userattributes);

		// Login/out
		if (t.sessions[domain].loggedin != loggedin) {

			t.sessions[domain].loggedin = loggedin;

			// If the domain is the local domain, also add "loggedin-localdomain" class to the body
			// This is needed for when the element to be visible or not depending on the user being logged in or not on the local domain
			// Then, we can add it directly in the .css, not in the php
			// Also, they can be outside the block (where the domain is saved)
			// Eg: pagesection-top, when showing the user account dropdown menu
			var extraClasses = [];
			if (domain == M.HOME_DOMAIN) {
				extraClasses.push('loggedin-localdomain');
			}

			var name, body = $(document.body);
			var domainId = getDomainId(domain);
			if (t.sessions[domain].loggedin) {					

				extraClasses.push('loggedin-anydomain');

				// Save the user id in the DOM, so we can remove the class when the user logs out (we won't have the ID then)
				body.data('loggedinuser-id-'+domainId, id).addClass('loggedin-'+domainId+' user-'+id+'-'+domainId);
				$.each(extraClasses, function(index, classs) {
					body.addClass(classs);
				});
				name = 'user:loggedin';
			}
			else {
				
				// If I'm not logged in to any one domain, then remove the class
				if (!t.isLoggedInAnyDomain()) {
					extraClasses.push('loggedin-anydomain');
				}

				body.removeClass('loggedin-'+domainId).removeClass('user-'+body.data('loggedinuser-id-'+domainId)+'-'+domainId).data('loggedinuser-id-'+domainId, '');
				$.each(extraClasses, function(index, classs) {
					body.removeClass(classs);
				});
				name = 'user:loggedout';
			}

			// Close all "checkpoint" feedback messages
			if (source != 'initialfeedback') {
				t.closeCheckpointMessages(domain);
			}

			// Trigger handler
			$(document).triggerHandler(name, [source, domain]);
			$(document).triggerHandler(name+':'+domain, [source]);
			$(document).triggerHandler('user:loggedinout', [source, domain]);
			$(document).triggerHandler('user:loggedinout:'+domain, [source]);
		}
	},

	feedbackLoginOut : function(domain, initial) {
	
		var t = this;

		// Check the TopLevel feedback if the User information was fetched. If so,
		// check if login/logout needed based on current and new state
		var topLevelFeedback = popManager.getTopLevelFeedback(domain);
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
			else if (t.sessions[domain].initialuserdata) {
				
				// This could be either from M.USERLOGGEDIN_DATA_PAGEURL coming back, or from a block that was faster.
				// Sometimes the block response comes back before the request to M.USERLOGGEDIN_DATA_PAGEURL
				// So in that case, it's a block the one "logging in" the user
				source = 'initialuserdata';
				t.sessions[domain].initialuserdata = false;
			}

			t.loginout(source, domain, loggedin, id, name, avatar, url, roles, userattributes);

			// Return true: the feedback with user info is available
			return true;
		}		

		// Return false: not logged in because feedback not available, so user might be logged in but we don't know
		return false;
	},

	checkpointInvalidateBlocks : function() {

		var t = this;

		// When the user logs in, invalidate all the blocks which have fetched its data from previous sessions (users)
		$(document).on('user:loggedin', function(e, source, domain) {

			// Do not do it when the user was already logged in => source = 'cookie'
			// If so, it will re-fetch the initial content, however this has already been loaded, no need to re-fetch it
			// (eg: starting on /my-events)
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}

			t.closeCheckpointMessages(domain);
		});
	},

	closeCheckpointMessages : function(domain) {

		var t = this;

		// Close all 'pop-messagefeedback.checkpoint' messages (if before it said user must log in, then hide it)
		// For that specific domain only!
		jQuery(document).ready( function($) {
			var domainId = getDomainId(domain);
			$('.alert.in.pop-messagefeedback.checkpoint.'+domainId).removeClass('fade').alert('close');
		});
	},

	getLoggedInUserId : function(domain) {

		var t = this;
		if (t.sessions[domain] && t.sessions[domain].loggedin) {
			return t.sessions[domain].id;
		}

		return false;
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popUserAccount, ['initDocument', 'initDomain', 'documentInitialized', 'pageSectionInitialized', 'blockInitialized', 'loadAvatar']);