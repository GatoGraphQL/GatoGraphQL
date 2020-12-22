"use strict";
(function($){
window.pop.UserAccount = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	sessions: {},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	
	initDocument : function(args) {
	
		var that = this;
		var domain = args.domain;

		// Log in the user immediately, before rendering the HTML. This way, conditional wrappers can access the state of the user
		// being logged in, such as for "isUserIdSameAsLoggedInUser"
		that.initialLogin(domain);
	},

	initDomain : function(args) {
	
		var that = this;
		var domain = args.domain;

		// Initialize the session for that domain
		that.sessions[domain] = that.getInitialSessionObject();
	},

	documentInitialized : function(args) {
	
		var that = this;
		var domain = args.domain;

		// Important: this function below must be executed before gdUserLoginout.initDocument(elem); so it can catch the login/logout events
		that.checkpointInvalidateBlocks();

		// // Fetch the user logged in data, for each non-initialized domain
		// that.fetchAllDomainsLoggedInUserData();
	},
	documentLoaded : function() {
	
		var that = this;
		// Fetch the user logged in data, for each non-initialized domain
		that.fetchAllDomainsLoggedInUserData();
	},
	pageSectionInitialized : function(args) {
	
		var that = this;
		var pageSection = args.pageSection;

		pageSection.on('fetched', function(e, options, url, domain) {

			that.feedbackLoginOut(domain, false);
		});
	},
	blockInitialized : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block;

		block.on('fetched', function(e, response, action, domain) {
			
			// var domain = getDomain(block.data('toplevel-url'));
			that.feedbackLoginOut(domain, false);
		});
	},

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------,

	getInitialSessionObject : function() {
	
		var that = this;

		// Initialize the session for that domain
		var object = {
			initialuserdata: true,
			loggedin: false,
			id: null,
			name: '',
			url: '',
		};

		// Allow PoP User Avatar to add the avatar
		var args = {
			object: object
		};
		pop.JSLibraryManager.execute('getUserAccountInitialSessionObject', args);
		return args.object;
	},

	initialLogin : function(domain) {

		var that = this;

		// Comment Leo 07/03/2-16: function "initialLogin" is called by pop.Manager inmediately
		// after initializing the JSON. This way, logging the user in comes before rendering the html, which is needed for
		// the condition wrappers which depend on the user logged in data, such as "isUserIdSameAsLoggedInUser"
		// Log-in from the feedback values
		// It is valid only for the local domain, not for external domains!
		var feedbackAvailable = that.feedbackLoginOut(domain, true);

		// If there is not feedback available, then we gotta send the "/loggedinuser-data" request.
		// Var that.initialuserdata acts as a flag to trigger the request on documentInitialized()
		that.sessions[domain].initialuserdata = !feedbackAvailable;
	},

	fetchAllDomainsLoggedInUserData : function(options) {
	
		var that = this;

		// Can fetch many pages, from different domains
		$.each(pop.c.USERLOGGEDIN_DATA_PAGEURLS, function(index, url) {

			// Comment Leo 07/09/2015: Do not log in from the cookie, since their value might be stale
			// Instead, fetch the "Logged-in User Data" page, it will retrieve all info for the logged in user, if there is such
			// Do it only if the there is no userInfo sent through the feedback, so must query explicitly the server about this info
			// Fetch if either is another domain, or it is this domain (initDomain called on it) and has specified that needs the initialuserdata
			var domain = getDomain(url);
			if (that.sessions[domain].initialuserdata) {

				// that.initialuserdata is a flag so we identify the first log in. Needed for not reloading data initially that must not be reloaded. Eg: TPP Debate Create Stance block. (It reloads its own data initially)
				that.fetchLoggedInUserData(url, options);
			}
		});
	},

	fetchLoggedInUserData : function(url, options) {
	
		var that = this;
		options = options || {};
		
		// This will change the loading message target or, if it doesn't exist, show no loading message
		options['loadingmsg-target'] = pop.c.USERLOGGEDIN_LOADINGMSG_TARGET;
		options.silentDocument = true;

		pop.Manager.fetch(url, options);
	},

	isLoggedInAnyDomain : function() {

		var that = this;

		var ret = false;
		$.each(that.sessions, function(domain, state) {

			if (state.loggedin) {
				ret = true;
				return -1;
			}
		});
		return ret;
	},

	isLoggedIn : function(domain) {

		var that = this;
		return that.sessions[domain].loggedin;
	},

	updateUserInfo : function(domain, userInfo) {
	
		var that = this;

		var updates = {};
		var domainId = getDomainId(domain);

		var id, name, url;
		id = userInfo[pop.c.DATALOAD_USER_ID];
		name = userInfo[pop.c.DATALOAD_USER_NAME];
		url = userInfo[pop.c.DATALOAD_USER_URL];

		// Update name/url (just in case it was modified, or the cookie was lost)
		if (that.sessions[domain].name != name) {
			
			that.sessions[domain].name = name;
			$('.pop-user-name.'+domainId).text(name);

			updates.name = name; 
		}
		if (that.sessions[domain].url != url) {

			that.sessions[domain].url = url;
			$('.pop-user-url.'+domainId).attr('href', url);

			updates.url = url;
		}

		// Allow PoP User Avatar to add the avatar
		var args = {
			domain: domain, 
			userInfo: userInfo,
			updates: updates
		};
		pop.JSLibraryManager.execute('updateUserAccountInfo', args);
		updates = args.updates;

		// Trigger an event if there were changes
		if (!$.isEmptyObject(updates)) {

			updates.id = id;
			$(document).triggerHandler('user:updated', [updates, domain]);
			$(document).triggerHandler('user:updated:'+domain, [updates]);
		}
	},

	loginout : function(source, domain, userInfo) {
	
		var that = this;

		that.updateUserInfo(domain, userInfo);

		var loggedin = userInfo[pop.c.DATALOAD_USER_LOGGEDIN];

		// Login/out
		if (that.sessions[domain].loggedin != loggedin) {

			that.sessions[domain].loggedin = loggedin;

			// If the domain is the local domain, also add "loggedin-localdomain" class to the body
			// This is needed for when the element to be visible or not depending on the user being logged in or not on the local domain
			// Then, we can add it directly in the .css, not in the php
			// Also, they can be outside the block (where the domain is saved)
			// Eg: pagesection-top, when showing the user account dropdown menu
			var extraClasses = [];
			if (domain == pop.c.HOME_DOMAIN) {
				extraClasses.push('loggedin-localdomain');
			}

			var name, body = $(document.body);
			var domainId = getDomainId(domain);
			if (that.sessions[domain].loggedin) {					

				extraClasses.push('loggedin-anydomain');

				// Save the user id in the DOM, so we can remove the class when the user logs out (we won't have the ID then)
				var id = userInfo[pop.c.DATALOAD_USER_ID];
				body.data('loggedinuser-id-'+domainId, id).addClass('loggedin-'+domainId+' user-'+id+'-'+domainId);
				$.each(extraClasses, function(index, classs) {
					body.addClass(classs);
				});
				name = 'user:loggedin';
			}
			else {
				
				// If I'm not logged in to any one domain, then remove the class
				if (!that.isLoggedInAnyDomain()) {
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
				that.closeCheckpointMessages(domain);
			}

			// Trigger handler
			$(document).triggerHandler(name, [source, domain]);
			$(document).triggerHandler(name+':'+domain, [source]);
			$(document).triggerHandler('user:loggedinout', [source, domain]);
			$(document).triggerHandler('user:loggedinout:'+domain, [source]);
		}
	},

	feedbackLoginOut : function(domain, initial) {
	
		var that = this;

		// Check the TopLevel feedback if the User information was fetched. If so,
		// check if login/logout needed based on current and new state
		// var topLevelFeedback = pop.Manager.getTopLevelFeedback(domain);
		// var userInfo = topLevelFeedback[pop.c.DATALOAD_USER];
		var sessionMeta = pop.Manager.getSessionMeta();
		var userInfo = sessionMeta[pop.c.DATALOAD_USER];
		if (typeof userInfo != 'undefined') {

			var source = 'feedback';
			if (initial) {
				source = 'initialfeedback';
			}
			else if (that.sessions[domain].initialuserdata) {
				
				// This could be either from pop.c.USERLOGGEDIN_DATA_PAGEURL coming back, or from a block that was faster.
				// Sometimes the block response comes back before the request to pop.c.USERLOGGEDIN_DATA_PAGEURL
				// So in that case, it's a block the one "logging in" the user
				source = 'initialuserdata';
				that.sessions[domain].initialuserdata = false;
			}

			that.loginout(source, domain, userInfo);

			// Return true: the feedback with user info is available
			return true;
		}		

		// Return false: not logged in because feedback not available, so user might be logged in but we don't know
		return false;
	},

	checkpointInvalidateBlocks : function() {

		var that = this;

		// When the user logs in, invalidate all the blocks which have fetched its data from previous sessions (users)
		$(document).on('user:loggedin', function(e, source, domain) {

			// Do not do it when the user was already logged in => source = 'cookie'
			// If so, it will re-fetch the initial content, however this has already been loaded, no need to re-fetch it
			// (eg: starting on /my-events)
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}

			that.closeCheckpointMessages(domain);
		});
	},

	closeCheckpointMessages : function(domain) {

		var that = this;

		// Close all 'pop-feedbackmessage.checkpoint' messages (if before it said user must log in, then hide it)
		// For that specific domain only!
		jQuery(document).ready( function($) {
			var domainId = getDomainId(domain);
			$('.alert.in.checkpoint.pop-feedbackmessage.feedbackmessage-'+domainId).removeClass('fade').alert('close');
		});
	},

	getLoggedInUserId : function(domain) {

		var that = this;
		if (that.sessions[domain] && that.sessions[domain].loggedin) {
			return that.sessions[domain].id;
		}

		return false;
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.UserAccount, ['initDocument', 'initDomain', 'documentInitialized', 'documentLoaded', 'pageSectionInitialized', 'blockInitialized']);