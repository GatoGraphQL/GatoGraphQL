(function($){
popSystem = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	documentInitialized : function() {
	
		var t = this;

		t.links();
		t.onlineOffline();
	},

	activeLinks : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets, domain = args.domain;

		// Only if the pageSection allows activeLinks (eg: side menu does not, or otherwise that link will remain always painted)
		var settings = popManager.getFetchPageSectionSettings(pageSection);
		if (settings.activeLinks) {

			// Source can be params or topLevelFeedback
			var feedback = popManager.getTopLevelFeedback(domain);

			var parentPageId = feedback[M.URLPARAM_PARENTPAGEID];
			if (parentPageId) {

				targets.find('.menu-item-object-id-'+parentPageId).each(function() {

					var menuItem = $(this);
					menuItem.addClass('active');
					menuItem.parents('.menu-item').addClass('active');

					// // If inside a collapse (with class 'pop-showactive'), open it
					// // Needed for the Side Sections Menu
					// jQuery(document).ready( function($) {
					// 	var collapse = menuItem.closest('.collapse.pop-showactive').not('.in');
					// 	collapse.collapse('show');
					// });
				});
			}
		}
	},

	fullscreen : function(args) {

		var t = this;
		var targets = args.targets;

		$(document).bind("fullscreenchange", function() {
			
			var glyphicon = targets.find('.glyphicon');
			if ($(document).fullScreen()) {

				glyphicon
					.removeClass('glyphicon-fullscreen')
					.addClass('glyphicon-resize-small');
			}
			else {

				glyphicon
					.addClass('glyphicon-fullscreen')
					.removeClass('glyphicon-resize-small');	
			}
		});

		targets.click(function(e) {

			e.preventDefault();

			var button = $(this);
			var fullScreen = button.closest('.pop-fullscreen');
			
			fullScreen.toggleFullScreen();
		});
	},

	newWindow : function(args) {

		var t = this;
		var targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			window.open(popManager.getUnembedUrl(window.location.href), '_blank');
		});
	},

	clickURLParam : function(args) {

		var t = this;

		// "click" on the URL defined in parameter "url"
		var url = getParam(M.URLPARAM_URL);
		if (url) {
			$(document).ready(function($) {
				popManager.click(decodeURIComponent(url));
			});
		}
	},

	initDelegatorFilter : function(args) {

		var t = this;
		var delegatorPageSection = args.pageSection, targets = args.targets;

		// Initialize Filters
		targets.submit(function(e) {
			
			e.preventDefault();
			var form = $(this);

			// Allow the form to indicate which is the block it is filter, under data-target
			// If none is specified, then use its own block
			if (form.data('blocktarget')) {
				
				var block = $(form.data('blocktarget'));
				var pageSection = popManager.getPageSection(block);

				// If the target is a block, then directly filter
				// If it is a blockgroup, then it will pass the filtering to its active block
				if (popManager.isBlockGroup(block)) {
					
					t.filterBlockGroup(pageSection, block, form) ;	
				}
				else {

					popManager.filter(pageSection, block, form);
				}

				// For mobile phone version: must get the active pageSection back to main
				popPageSectionManager.close(delegatorPageSection, 'xs');
			}
		});
	},

	initBlockFilter : function(args) {

		var t = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Initialize Filters
		targets.submit(function(e) {
			
			e.preventDefault();
			var form = $(this);

			// Allow the form to indicate which is the block it is filter, under data-target
			// If none is specified, then use its own block
			if (form.data('blocktarget')) {
				
				block = $(form.data('blocktarget'));
				pageSection = popManager.getPageSection(block);
			}
			popManager.filter(pageSection, block, form);
		});
	},

	initBlockGroupFilter : function(args) {

		var t = this;

		var pageSection = args.pageSection, blockGroup = args.block, targets = args.targets;
		// var blocks = popManager.getBlockGroupBlocks(blockGroup);

		//If the filter came loaded with a filtering value, already set these in the contained blocks filters
		targets.each(function(e) {
			
			// Copy the filter params to all contained blocks
			var filter = $(this);
			// Set all the blocks filters with the value of this one filter
			blocks.each(function() {

				var block = $(this);
				popManager.setFilterBlockParams(pageSection, block, filter);
			});
		});

		// Initialize Filters
		targets.submit(function(e) {
			
			e.preventDefault();

			// Copy the filter params to all contained blocks
			var filter = $(this);
			t.filterBlockGroup(pageSection, blockGroup, filter) ;
		});
	},

	reloadBlockGroup : function(args) {

		var t = this;

		var pageSection = args.pageSection, blockGroup = args.block, targets = args.targets;
		targets.click(function(e) {
			
			e.preventDefault();

			// Copy the filter params to all contained blocks
			var activeBlock = popManager.getBlockGroupActiveBlock(blockGroup);
			popManager.reload(pageSection, activeBlock);
		});
	},

	reloadBlock : function(args) {

		var t = this;

		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.click(function(e) {
			
			e.preventDefault();
			popManager.reload(pageSection, block);
		});
	},

	loadLatestBlock : function(args) {

		var t = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.click(function(e) {
			
			e.preventDefault();
			popManager.loadLatest(domain, pageSection, block);
		});
	},

	timeoutLoadLatestBlock : function(args) {

		var t = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.each(function() {
			
			var target = $(this);
			// Default: 30 seconds
			var time = target.data('clicktime') || 30000;
			t.execTimeoutLoadLatestBlock(domain, pageSection, block, target, time);
		});
	},

	displayBlockDatasetCount : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block;

		if (block.data('datasetcount-target')) {

			var jsSettings = popManager.getJsSettings(domain, pageSection, block);

			// By default: execute only when the block is created
			var when = jsSettings['display-datasetcount-when'] || ['oncreated'];
			var updateTitle = jsSettings['datasetcount-updatetitle'];

			// Wait until the document has fully loaded, to make sure the target has been added in the DOM
			// Otherwise, when first loading the website, it will fail since the JS executes before the elem is in the DOM
			if (when.indexOf('oncreated') > -1) {
			
				$(document).ready(function($) {
					var target = $(block.data('datasetcount-target'));
					popManager.displayDatasetCount(domain, pageSection, block, target, updateTitle);
				});
			}
			
			// Needed for the Log-in, since we're fetching data and then the block will trigger 'rendered', then execute
			if (when.indexOf('onrendered') > -1) {
				block.on('rendered', function(e) {
					var target = $(block.data('datasetcount-target'));
					popManager.displayDatasetCount(domain, pageSection, block, target, updateTitle);
				});
			}
		}
	},

	clearDatasetCount : function(args) {

		var t = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.each(function() {
			
			var link = $(this);
			var target = $(link.data('datasetcount-target'));
			// Can't use jsSettings because we don't have the settings for the pageSection notifications link
			// var jsSettings = popManager.getJsSettings(pageSection, block, target);
			// var updateTitle = jsSettings['update-title'];
			var updateTitle = link.data('datasetcount-updatetitle') ? true : false;
			link.click(function(e) {
				e.preventDefault();
				t.execClearDatasetCount(target, updateTitle);
			});
			link.hover(function() {
				t.execClearDatasetCount(target, updateTitle);
			});
		});
	},

	clearDatasetCountOnUserLoggedOut : function(args) {

		var t = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.each(function() {
			
			var link = $(this);
			var target = $(link.data('datasetcount-target'));
			// Can't use jsSettings because we don't have the settings for the pageSection notifications link
			// var jsSettings = popManager.getJsSettings(pageSection, block, target);
			// var updateTitle = jsSettings['update-title'];
			var updateTitle = link.data('datasetcount-updatetitle') ? true : false;
			$(document).on('user:loggedout', function(e) {
				t.execClearDatasetCount(target, updateTitle);
			});
		});
	},

	onDestroyPageSwitchTab : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		// Catch the event 'destroy' and switch the tab then. This way, it will also work when destroying the pageSectionPage
		// not just fron clicking on this button but also other sources, eg: the self-closing of the addon pageSection after submitting a comment
		targets.each(function() {
			
			var link = $(this);
			var pageSectionPage = popManager.getPageSectionPage(link);
			pageSectionPage.on('destroy', function() {

				t.switchTab(link);
			})
		})
	},

	addOpenTab : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets, addOpenTab = args.addOpenTab;

		// Allow function openTabs to set this attr in false, so this value is not added yet once again since it's already open
		// It's needed for opening Add Post pages, which have different hashtags to differentiate them, so otherwise opening one Add Page
		// and reloading the page will open one page again, then 2, then 4, etc
		if (addOpenTab === false) {
			return;
		}

		targets.each(function() {
			
			var link = $(this);
			
			var params = t.getDestroyPageTabParams(pageSection, link);

			// Add the url to the session tabs, so next session they can be re-opened
			popManager.addOpenTab(params.url, params.target);
		});
	},

	closePageTab : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {
			
			e.preventDefault();
			var link = $(this);
			
			var params = t.getDestroyPageTabParams(pageSection, link);

			// Comment Leo 10/01/2017: this line has been moved to triggerDestroyTarget so it's executed even
			// if the user did not click on the close button, eg: after adding a comment, the window self destroys,
			// but the Add Comment tab remained open for the next session!
			// // Remove the tab from the open sessions
			// popManager.removeOpenTab(params.url, params.target);

			// destroy the PageTab
			popManager.triggerDestroyTarget(params.url, params.target);
		});
	},

	resetOnSuccess : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = popManager.getSettingsId(block);				
			var blockFeedback = response.feedback.block[pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// skip-restore: allow the message feedback to still show
				popManager.reset(domain, pageSection, block, {'skip-restore': true});
			}
		});
	},

	resetOnUserLogout : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		$(document).on('user:loggedout:'+domain, function(e, source) {

			targets.each(function() {
		
				// To double-check that the object still exists in the DOM and was not removed when doing popManager.destroyPageSectionPage
				var block = $('#'+$(this).attr('id'));
				if (block.length) {

					// 'delete-dataset': needed to erase the personal information of the logged in user. Eg: Add OpinionatedVoted, where it was showing the OpinionatedVoted by the user.
					// popManager.reset(pageSection, block, {'delete-dataset': true});
					popManager.reset(domain, pageSection, block);
				}
			});
		});
	},

	destroyPageOnSuccess : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = popManager.getSettingsId(block);				
			var blockFeedback = response.feedback.block[pssId][bsId];
			var target = popManager.getFrameTarget(pageSection);

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// get the destroyTime from the block
				var destroyTime = block.data('destroytime') || 0;
				setTimeout(function () {
					
					// Destroy this pageSectionPage
					popManager.triggerDestroyTarget(popManager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/, target);
				}, destroyTime);
			}
		});
	},

	destroyPageOnUserLoggedOut : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		// Do it only after the pageSection has been initialized
		// This is so that the pageSectionPage doesn't get destroyed when having loaded that one! That one will give the info the user is not logged in
		// So now, it first destroys all pageSectionPages, and then sets the handler for this one
		// This will execute after t.feedbackLoginOut in user-account.js->pageSectionInitialized
		pageSection.one('completed', function() {

			t.execDestroyPageOnUserLoggedOut(domain, targets);
		});
	},

	destroyPageOnUserNoRole : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		pageSection.one('completed', function() {
		
			t.execDestroyPageOnUserNoRole(domain, pageSection, targets);
		});
	},

	refetchBlockOnUserLoggedIn : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		$(document).one('user:loggedin:'+domain, function(e, source) {

			if (source == 'initialfeedback') {
				return;
			}

			t.execRefetchBlock(pageSection, targets);
		});
	},

	nonendingRefetchBlockOnUserLoggedIn : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		t.execNonendingRefetchBlockOnUserEvent(pageSection, targets, 'user:loggedin:'+domain);
	},

	nonendingRefetchBlockOnUserLoggedInOut : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		t.execNonendingRefetchBlockOnUserEvent(pageSection, targets, 'user:loggedinout:'+domain);
	},

	deleteBlockFeedbackValueOnUserLoggedInOut : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block;
		$(document).on('user:loggedinout:'+domain, function(e, source) {

			// Ask for 'initialuserdata' because some blocks will update themselves to load the content,
			// and will not depend on the user loggedin data. eg: Create OpinionatedVoted Block for the TPP Website
			// So if the source was that initial data, dismiss, otherwise it will trigger the URL load once again
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}
			
			// Deleting values is needed for the Notifications: when creating a user account, it will create notification "Welcome!",
			// but to fetch it we gotta delete param `hist_time` with value from previous fetching of notifications
			var jsSettings = popManager.getJsSettings(domain, pageSection, block);
			var keys = jsSettings['user:loggedinout-deletefeedbackvalue'] || [];
			if (keys.length) {
				
				var blockFeedback = popManager.getBlockFeedback(domain/*popManager.getBlockTopLevelDomain(block)*/, pageSection, block);
				$.each(keys, function(index, keyLevels) {

					// each param in params is an array of levels, to go down the blockParams to delete it (eg: 1 param will be ['params', 'hist_time'] to delete blockParams['params']['hist_time'])
					// Go down to the last level
					var feedbackLevel = blockFeedback;
					for (i = 0; i < (keyLevels.length)-1; i++) { 
						feedbackLevel = feedbackLevel[keyLevels[i]];
					}

					// Delete that last level
					delete feedbackLevel[keyLevels[(keyLevels.length)-1]];
				});
			}

		});
	},

	scrollTopOnUserLoggedInOut : function (args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		$(document).on('user:loggedinout:'+domain, function(e, source) {

			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}

			targets.each(function() {
				var target = $(this);

				// Comment Leo 15/02/2017: Make sure the target still exists, because it may have been destroyed already but the hook still stays on
				if ($('#'+target.attr('id')).length) {
					var jsSettings = popManager.getJsSettings(domain, pageSection, block, target);
					var animate = jsSettings['scrolltop-animate'] || false;
					popManager.scrollToElem(target, target, animate);
				}
			});
		});
	},

	refetchBlockGroupOnUserLoggedIn : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		$(document).one('user:loggedin:'+domain, function(e, source) {

			// if (source == 'cookie' || source == 'initialfeedback') {
			if (source == 'initialfeedback') {
				return;
			}

			// Comment Leo 07/09/2015: Operate with the target ids, and not with the target itself
			// This is because, after calling pageSectionPage.remove(), the targets are not under the DOM,
			// but the reference still exists! So it doesn't know that it was removed
			// This happened when:
			// - User is logged out
			// - Calling a page with a onUserLoggedIn reload (eg: My Content)
			// - closing this tab
			// - Then logging in
			// producing a javascript error: Uncaught Error: Mempage not available
			targets.each(function() {
		
				// To double-check that the object still exists in the DOM and was not removed when doing popManager.destroyPageSectionPage
				var blockGroup = $('#'+$(this).attr('id'));
				if (blockGroup.length) {

					var blocks = popManager.getBlockGroupBlocks(blockGroup);
					var activeBlock = popManager.getBlockGroupActiveBlock(blockGroup);
					blocks.each(function() {

						var block = $(this);
						if (block.attr('id') == activeBlock.attr('id')) {
							t.refetchBlock(pageSection, block);
						}
						else {
							t.makeOneTimeRefetch(pageSection, block);
						}
					});
				}
			});
		});
	},

	// refetchBlockIfUserLoggedIn : function(args) {

	// 	var t = this;
	// 	var pageSection = args.pageSection, targets = args.targets;

	// 	targets.each(function() {

	// 		var block = $(this);
	// 		t.makeOneTimeRefetch(pageSection, block);
	// 	});
	// },

	closeMessageFeedbacksOnPageSectionOpen : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		popPageSectionManager.getGroup(pageSection).on('on.bs.pagesection-group:pagesection-'+pageSection.attr('id')+':opened', function() {

			// Needed to erase previous feedback messages. Eg: Reset password
			popManager.closeMessageFeedbacks(pageSection);
		});
	},

	closePageSectionOnSuccess : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var block = $(this);
			var bsId = popManager.getSettingsId(block);				
			var blockFeedback = response.feedback.block[pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// get the time from the block
				var closeTime = block.data('closetime') || 0;
				t.timeoutClosePageSection(pageSection, closeTime);
				// setTimeout(function () {
					
				// 	popPageSectionManager.close(pageSection);

				// 	// After closing, also delete all the messageFeedbacks within. This way, after logging in, it deletes the 'Logged in successful'
				// 	// message for the next time we try to log in. And we close all, so that if a 'Log out unsuccessful' message was there, it will be gone also
				// 	popManager.closeMessageFeedbacks(pageSection);
				// }, closeTime);
			}
		});
	},

	destroyPage : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;
		
		targets.click(function(e) {
			
			e.preventDefault();
			var link = $(this);
			var pageSectionPage = $(link.data('target'));

			// Call the destroy to the pageSection, it will know which page to destroy
			popManager.destroyPageSectionPage(domain, pageSection, pageSectionPage);
		});
	},

	replicateTopLevel : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.each(function() {
			
			var link = $(this);
			var type = link.data('replicate-type');
			if (type == M.REPLICATETYPES.MULTIPLE) {
				
				t.replicateMultipleTopLevel(pageSection, link);
			}
			else if (type == M.REPLICATETYPES.SINGLE) {

				t.replicateSingleTopLevel(pageSection, link);
			}
		});
	},
	replicatePageSection : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.each(function() {
			
			var link = $(this);
			var type = link.data('replicate-type');
			if (type == M.REPLICATETYPES.MULTIPLE) {
				
				t.replicateMultiplePageSection(pageSection, link);
			}
			else if (type == M.REPLICATETYPES.SINGLE) {

				t.replicateSinglePageSection(pageSection, link);
			}
		});
	},

	closePageSection : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			popPageSectionManager.close(pageSection);
		});
	},

	closePageSectionOnTabShown : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.each(function() {

			var block = $(this);
			var pageSectionPage = popManager.getPageSectionPage(block);

			pageSectionPage.on('shown.bs.tabpane', function() {

				popPageSectionManager.close(pageSection);
			});
			
			// Execute already since it is mostly used by the replicable blocks, so gotta close also on initialization
			popPageSectionManager.close(pageSection);
		});
	},

	initFilter : function(args) {

		var t = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Initialize Filters
		targets.each(function() {
			
			var filter = $(this);

			// Set Filter Params, for if filtering already (eg: if e.preventDefault() below has failed)
			popManager.setFilterBlockParams(pageSection, block, filter);
			// popManager.setFilterParams(pageSection, filter);	
		});
	},

	interceptForm : function(args) {

		var t = this;

		var pageSection = args.pageSection, targets = args.targets;
		targets.click(function(e) {

			e.preventDefault();
			var link = $(this);
			var form = $(link.data('target'));

			// Set the params into the form field
			var params = splitParams(link.data('post-data'));
			$.each(params, function(key, value) {
				form.find('input[name="'+key+'"]').val(value);
			});

			form.submit();
		});
	},

	// initBlockProxyFilter : function(args) {

	// 	var t = this;
	// 	var targets = args.targets;

	// 	// Initialize Filters
	// 	targets.submit(function(e) {
			
	// 		e.preventDefault();
	// 		var form = $(this);

	// 		// The form is a proxy that is filtering the block
	// 		var block = $(form.data('target'));
	// 		var pageSection = popManager.getPageSection(block);
	// 		popManager.filter(pageSection, block, form);
	// 	});
	// },
	// formProxy : function(args) {

	// 	var t = this;

	// 	var pageSection = args.pageSection, targets = args.targets;
	// 	targets.submit(function(e) {

	// 		e.preventDefault();
	// 		var form = $(this);
	// 		var proxied = $(form.data('target'));

	// 		// Remove all proxied inputs from previous searches
	// 		proxied.find('.pop-proxiedinput').remove();

	// 		// Copy the values from the form to the proxied form
	// 		var inputs = form.find('.' + M.FILTER_INPUT);
	// 		$.each(inputs, function() {
	// 			var input = $(this);
	// 			var input_name = input.attr('name');

	// 			// Either copy the input value to the corresponding input or, if this doesn't exist, create it (eg: for any Typeahead, the input is created on the fly)
	// 			if (proxied.find('input[name="'+input_name+'"]').length) {

	// 				proxied.find('input[name="'+input_name+'"]').val(input.val());
	// 			}
	// 			else {
					
	// 				proxied.append('<input type="hidden" class="pop-proxiedinput hidden '+M.FILTER_INPUT+'" name="'+input_name+'" value="'+input.val()+'">');
	// 			}
	// 		});

	// 		proxied.submit();
	// 	});
	// },

	forms : function(args) {

		var t = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);

		targets.submit(function(e) {

			e.preventDefault();

			var form = $(this);

			// Allow form inputs to modify their value before sending anything
			// (eg: editor can save itself, pop-browserurl can take the browser url, etc)
			form.triggerHandler('beforeSubmit');

			// Submit to fetch the Block data, when it comes back process it through a handler on the gdFullCalendar side
			popManager.fetchBlock(pageSection, block, {type: 'POST', "post-data": form.serialize()});

			// Scroll Top to show the "Submitting" message
			popManager.blockScrollTop(pageSection, block);
			// var modal = form.closest('.modal');
			// if (modal.length == 0) {
			// 	// Scrolling to the block and not to the form because sometimes there's a block of text on top of the form,
			// 	// and the Submitting message will appear on top of these, not on top of the form
			// 	popManager.scrollToElem(pageSection, /*form*/block, true);
			// }
			// else {
			// 	modal.animate({ scrollTop: 0 }, 'fast');
			// }
		});	

		block.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var blockFeedback = response.feedback.block[pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// Allow other components to clear themselves. Eg: editor
				targets.triggerHandler('clear');
			}

			// If there is feedback, scroll to it
			if (blockFeedback['show-msgs'] && blockFeedback['msgs'] && blockFeedback['msgs'].length) {
				popManager.scrollToElem(pageSection, block.children('.blocksection-messagefeedback').first(), true);
			}

			popManager.maybeRedirect(blockFeedback);
		});
	},

	clearInput : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.each(function () {
			var input = $(this);
			input.closest('form').on('clear', function() {
				input.val('');
			});
		});
	},

	makeAlwaysRefetchBlock : function(args) {

		var t = this;
		var pageSection = args.pageSection, block = args.block;

		block.on('visible', function () {
			var block = $(this);
			t.refetchBlock(pageSection, block);
		});
	},

	addDomainClass : function(args) {

		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.each(function() {

			var target = $(this);

			// Add a prefix to the domain, such as 'visible-loggedin-', etc
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, target);
			var prefix = jsSettings['prefix'] || '';
			targets.addClass(prefix+getDomainId(domain));
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	getDestroyPageTabParams : function(pageSection, link) {

		var t = this;
			
		// URL: take it from 'data-url' on the link (eg: Page Tab), or if it doesn't exist,
		// from the corresponding paramsscope URL (eg: blockunit-frame controls to close a page)
		return {
			url: link.data('url') || popManager.getTargetParamsScopeURL(popManager.getBlock(link))/*popManager.getBlock(link).data('paramsscope-url')*/,
			target: link.attr('target') || popManager.getFrameTarget(pageSection)
		};
	},

	filterBlockGroup : function(pageSection, blockGroup, filter) {

		var t = this;

		var activeBlock = popManager.getBlockGroupActiveBlock(blockGroup);
		var blocks = popManager.getBlockGroupBlocks(blockGroup);

		// Set all the blocks filters with the value of this one filter
		blocks.each(function() {

			var block = $(this);

			popManager.setFilterBlockParams(pageSection, block, filter);

			// Filter already the active block, add Refetch to all non-active blocks
			if (block.attr('id') == activeBlock.attr('id')) {
				popManager.reload(pageSection, block);
			}
			else {
				t.makeOneTimeRefetch(pageSection, block);
			}
		});
	},

	execClearDatasetCount : function(target, updateTitle) {

		var t = this;
		target.html('').addClass('hidden');

		// Delete the datasetCount from the document title
		if (updateTitle) {
			document.title = popManager.documentTitle;
		}
	},

	execTimeoutLoadLatestBlock : function(domain, pageSection, block, target, time) {

		var t = this;
		var jsSettings = popManager.getJsSettings(domain, pageSection, block, target);
		var options = {
			'skip-status': true,
			
			// Show the count of results in the toplevel bell
			'datasetcount-target': jsSettings['datasetcount-target'],
			'datasetcount-updatetitle': jsSettings['datasetcount-updatetitle'],
		};

		// Only if the user is logged in? Eg: Latest Notifications, it makes no sense to fetch constantly for logged out users
		var onlyLoggedIn = jsSettings['only-loggedin'] || false;
		if (onlyLoggedIn && !popUserAccount.isLoggedIn(domain)) {

			$(document).one('user:loggedin:'+domain, function(e, source) {

				t.execTimeoutLoadLatestBlock(domain, pageSection, block, target, time);
			});

			// No more needed
			return;
		}

		setTimeout(function () {

			// Make sure object still exists
			if ($('#'+target.attr('id')).length) {

				popManager.loadLatest(domain, pageSection, block, options);
				t.execTimeoutLoadLatestBlock(domain, pageSection, block, target, time);
			}			
		}, time);
	},

	timeoutClosePageSection : function(pageSection, closeTime) {

		var t = this;
		setTimeout(function () {
			
			popPageSectionManager.close(pageSection);

			// After closing, also delete all the messageFeedbacks within. This way, after logging in, it deletes the 'Logged in successful'
			// message for the next time we try to log in. And we close all, so that if a 'Log out unsuccessful' message was there, it will be gone also
			popManager.closeMessageFeedbacks(pageSection);
		}, closeTime);
	},

	execNonendingRefetchBlockOnUserEvent : function(pageSection, targets, handler) {

		var t = this;
		$(document).on(handler, function(e, source) {

			// Ask for 'initialuserdata' because some blocks will update themselves to load the content,
			// and will not depend on the user loggedin data. eg: Create OpinionatedVoted Block for the TPP Website
			// So if the source was that initial data, dismiss, otherwise it will trigger the URL load once again
			if (source == 'initialfeedback' || source == 'initialuserdata') {
				return;
			}
			
			t.execRefetchBlock(pageSection, targets);
		});
	},

	execRefetchBlock : function(pageSection, blocks) {

		var t = this;
		blocks.each(function() {
	
			// To double-check that the object still exists in the DOM and was not removed when doing popManager.destroyPageSectionPage
			var block = $('#'+$(this).attr('id'));
			if (block.length) {
				
				t.refetchBlock(pageSection, block);
			}
		});
	},

	execDestroyPageOnUserLoggedOut : function(domain, targets) {

		var t = this;

		$(document).one('user:loggedout:'+domain, function(e, source) {

			targets.each(function() {
		
				// To double-check that the object still exists in the DOM and was not removed when doing popManager.destroyPageSectionPage
				var block = $('#'+$(this).attr('id'));
				if (block.length) {
					var pageSection = popManager.getPageSection(block);
					var target = popManager.getFrameTarget(pageSection);
					popManager.triggerDestroyTarget(popManager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/, target);
				}
			});
		});
	},

	execDestroyPageOnUserNoRole : function(domain, pageSection, block) {

		var t = this;		
		var neededRole = block.data('neededrole');
		
		$(document).one('user:updated:'+domain, function(e, updates) {

			// To double-check that the object still exists in the DOM and was not removed when doing popManager.destroyPageSectionPage
			block = $('#'+block.attr('id'));
			if (block.length) {

				// If the roles where updated, check the needed role is still there. If not, destroy the page
				// Needed for when a Community sets "Do you accept members?" in false, then it's not a community anymore, then destroy "My Members" page
				if (updates.roles && updates.roles.indexOf(neededRole) == -1) {
					
					var target = popManager.getFrameTarget(pageSection);
					popManager.triggerDestroyTarget(popManager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/, target);
				}
				else {

					// Re-add the event handler
					t.execDestroyPageOnUserNoRole(domain, pageSection, block);
				}
			}
		});
	},

	switchTab : function(link) {

		var t = this;

		// If the link is active, then switch to previous or next tab
		if (link.prev('a.pop-pagetab-btn').hasClass('active')) {

			var current = popManager.getPageSectionPage(link);

			// Can't use current.prev() because sometimes it's a '.pop-pagesection-page' object, sometimes it's an interceptor
			var next = current.nextAll('.pop-pagesection-page').first();
			if (!next.length) {
				next = current.prevAll('.pop-pagesection-page').first();
			}

			if (next.length) {
				// If another tab is available, click on it to open it
				next.find('a.pop-pagetab-btn').trigger('click');
			}
		}
	},

	replicateMultipleTopLevel : function(pageSection, targets) {

		var t = this;
		targets.click(function(e) {
			
			e.preventDefault();
			var link = $(this);		
			var addUniqueId = link.data('unique-url') || false;
			t.execReplicateTopLevel(pageSection, link, true, addUniqueId);
		});
	},
	replicateSingleTopLevel : function(pageSection, targets) {

		var t = this;
		targets.one('click', function(e) {
			
			e.preventDefault();
			var link = $(this);	
			t.execReplicateTopLevel(pageSection, link, false, false);
		});
	},
	execReplicateTopLevel : function(pageSection, link, generateUniqueId, addUniqueId) {

		var t = this;

		// Comment Leo 26/10/2015: the URL is not the intercepted one but the original one. These 2 differ when intercepting without params
		// Eg: adding a new comment, https://www.mesym.com/add-comment/?pid=19604, url to intercept is https://www.mesym.com/add-comment/
		var url = link.data('original-url');
		
		var domain = getDomain(url);
		var memory = popManager.getMemory(domain);
		
		// Restore initial toplevel feedback from when the page was loaded
		var interceptUrl = link.data('intercept-url');
		var target = popManager.getFrameTarget(pageSection);
		var replicableMemory = popManager.getReplicableMemory(interceptUrl, target);

		// Comment Leo 05/04/2017: doing the line below deleted the topLevelFeedback params,
		// and that was a problem since it had all values added on loading_frame(): version, theme, thememode, etc
		// So now iterating all the fields, and setting the value by copy
		// memory.feedback.toplevel = $.extend({}, replicableMemory.feedback.toplevel);
		var tlFeedback = popManager.getTopLevelFeedback(domain);
		// var tlFeedback = memory.feedback.toplevel;
		$.each(replicableMemory.feedback.toplevel, function(key, value) {

			// If it is an empty array then do nothing but set the object: this happens when the pageSection has no modules (eg: sideInfo for Discussions page)
			// and because we can't specify FORCE_OBJECT for encoding the json, then it assumes it's an array instead of an object, and it makes mess
			if ($.type(value) == 'array' && value.length == 0) {
				// do Nothing
			}
			else if ($.type(value) == 'object') {

				// If it is an object, extend it. If not, just assign the value
				if (!tlFeedback[key]) {
					tlFeedback[key] = {};
				}
				$.extend(tlFeedback[key], value);
			}
			else {
				tlFeedback[key] = value;
			}
		});
		// popManager.maybeRestoreUniqueId(memory);

		// Generate a new uniqueId
		// Change the tab "current-page" URL to the intercepted URL + add an ID to make this URL
		// different for if again replicating the same element (eg: clicking twice on Add Event)
		if (generateUniqueId) {

			// function openTabs(): It might be the case that we're calling a an Add Post page with a unique-id and that page doesn't exist
			// That is because the saved URL contains the hashtag, so after refreshing the page, it will intercept again that URL
			// So if the URL already has an ID, use that one. Otherwise, it makes a mess, adding hashtags on top of each other
			// (something like /add-post/#asddk8980808234#fdkwwp4234355) and it creats mess opening way many tabs when refreshing the page, one for each new URL
			if (addUniqueId && url.indexOf('#') > -1) {
				tlFeedback[M.UNIQUEID] = url.substr(url.indexOf('#')+1);
			}
			else {
			
				popManager.generateUniqueId(domain);			
				if (addUniqueId) {

					url = popManager.addUniqueId(url);
				}
			}
		}
		var title = link.data('title');

		// Set new values, coming from the intercepted link
		tlFeedback[M.URLPARAM_TITLE] = title;
		tlFeedback[M.URLPARAM_PARENTPAGEID] = null;
		tlFeedback[M.URLPARAM_URL] = url;

		// Update document
		popManager.maybeUpdateDocument(domain, pageSection);
	},

	replicateMultiplePageSection : function(pageSection, targets) {

		var t = this;
		targets.click(function(e) {

			e.preventDefault();
			var link = $(this);
			t.execReplicatePageSection(pageSection, link);
		});
	},
	replicateSinglePageSection : function(pageSection, targets) {

		var t = this;
		targets.one('click', function(e) {

			e.preventDefault();
			var link = $(this);
			t.execReplicatePageSection(pageSection, link);
		});
	},
	execReplicatePageSection : function(pageSection, link) {

		var t = this;

		var bsId = link.data('block-settingsid');
		var pssId = popManager.getSettingsId(pageSection);
		var template = link.data('templateid');
		
		var interceptUrl = link.data('intercept-url');
		var domain = getDomain(interceptUrl);
		var memory = popManager.getMemory(domain);

		var target = popManager.getFrameTarget(pageSection);
		var replicableMemory = popManager.getReplicableMemory(interceptUrl, target);
		
		// Override the feedback, dataset, params to the initial values
		// (otherwise: sequence: click Add Project, submit with errors, Add a Project again, it will also draw the validation error, we gotta clear the messagefeedback)
		$.extend(memory.feedback.pagesection, replicableMemory.feedback.pagesection);
		$.each(replicableMemory.feedback.block, function(ipssId, ipsFeedback) {
			$.extend(memory.feedback.block[ipssId], ipsFeedback);
			$.extend(memory.dataset[ipssId], replicableMemory.dataset[ipssId]);
			$.extend(memory['query-state'].general[ipssId], replicableMemory['query-state'].general[ipssId]);
			$.extend(memory['query-state'].domain[ipssId], replicableMemory['query-state'].domain[ipssId]);
		});

		// Intercept URL: the newly created URL, assigned already to the toplevel feedback on the fuction above
		var tlFeedback = popManager.getTopLevelFeedback(domain);
		var psFeedback = popManager.getPageSectionFeedback(domain, pageSection);
		var psConfiguration = popManager.getPageSectionConfiguration(domain, pageSection);
		if (!psFeedback['intercept-urls'][template]) {
			psFeedback['intercept-urls'][template] = {};
		}
		psFeedback['intercept-urls'][template][template] = tlFeedback[M.URLPARAM_URL];

		// Set what blocks must be replicated in 'blockunits', replicable must be empty since the "tell me what blocks are to be replicated" was already executed
		psConfiguration[M.JS_TEMPLATE/*'template'*/] = template;

		// Comment Leo 05/11/2015: the configuration of the block-settings-ids to be drawn is passed as settings
		// next to the interceptor link, on <span class="pop-interceptor-blocksettingsids"/>
		// contained inside are configuration items, namely: what blockunitGroup ('blockunits', 'replicable', 'blockunits-frame', etc)
		// must be initialized with what block-settings-ids (as a list)
		var blockSettingsIds = {};
		link.next('.pop-interceptor-blocksettingsids').children().each(function() {
			
			var settingElem = $(this);

			// The values must be an array
			var val = settingElem.data('value');
			if (settingElem.data('value')) {
				val = [settingElem.data('value')];
			}
			else {
				val = [];
			}
			blockSettingsIds[settingElem.data('key')] = val;
		});
		psConfiguration[M.JS_BLOCKSETTINGSIDS/*'block-settings-ids'*/] = blockSettingsIds;

		// This will set the 'pss' in the context with the new toplevel feedback
		popManager.initPageSectionSettings(domain, pageSection, psConfiguration);

		// Set up the clicked link as a relatedTarget. This is needed for the Addons, eg: when clicking on Volunteer, it can pickup what Project it is from the data-header in the original link
		var options = {
			'js-args': {
				relatedTarget: link
			},
			url: tlFeedback[M.URLPARAM_URL]
		}
		popManager.renderPageSection(domain, pageSection, options);
	},

	refetchBlock : function(pageSection, block) {
	
		var t = this;
		var options = {'post-data': block.data('post-data'), 'show-disabled-layer': true};
		popManager.refetch(pageSection, block, options);
	},

	makeOneTimeRefetch : function(pageSection, block) {

		var t = this;

		// If the block has not loaded its content, then do nothing, whenever it gets initialized it will send the request all by itself
		// This also handles when data-load=false (eg: Search). Doing instead if (!t.jsInitialized(block)) { fails with Search, since it is not initialized but the 
		// initialization does not fetch the content either
		if (!popManager.jsInitialized(block) || !popManager.isContentLoaded(pageSection, block)) {

			return;
		}

		block.one('visible', function () {
			var block = $(this);
			t.refetchBlock(pageSection, block);
		});
	},

	linkBlankTarget : function(anchor) {

		var t = this;

		// Open in new window
		if (!anchor.attr('target')) {
			anchor.attr('target', '_blank');
		}
	},

	maybeaddspinner : function(anchor, url) {

		var t = this;

		// Add a spinner next to the clicked link? Allow to override on an anchor by anchor basis
		var addspinner = !anchor.hasClass('hidden') && ((M.ADDANCHORSPINNER && (typeof anchor.data('addspinner') == 'undefined') || anchor.data('addspinner')));
		if (addspinner) {

			// Create the spinner object and add an extra class so we can remove it later on
			var spinner = $('<span class="pop-spinner">'+M.SPINNER+'</span>');

			// If the anchor contains an image, assign a class to the spinner so it can be placed on top of the image
			if (anchor.find('img').length) {
				spinner.addClass('spinner-img');
			}

			// Whenever the URL was fetched, undo the changes. The URL must be escaped, or otherwise the string triggered cannot be handled correctly
			$(document).one('urlfetched:'+escape(url), function() {
				anchor
					.removeClass('loading')
					// .removeClass('disabled')
					.children('.pop-spinner')
						.remove();
			});

			// Add class 'disabled' to the button, and remove class 'disabled' to the button siblings, since once the request
			// comes back, they'll be visible on their normal state (eg: Follow/Unfollow user)
			// And add the spinner
			// Also blur from the button, so that it doesn't have an annoying "_" underscore like produced between the spinner and the text
			anchor
				.trigger('blur')
				// .addClass('loading disabled')
				.addClass('loading')
				.prepend(spinner);
		}

		return addspinner;
	},

	onlineOffline : function() {
		
		var t = this;
		window.addEventListener('online', t.checkOnlineOffline);
		window.addEventListener('offline', t.checkOnlineOffline);

		// Already execute it
		t.checkOnlineOffline();
	},

	checkOnlineOffline : function() {
		
		var t = this;

		// Add a class to the body with the status online/offline
		if (navigator.onLine){
			$(document.body).addClass('online').removeClass('offline');
		} else {
			$(document.body).addClass('offline').removeClass('online');
		}
	},

	links : function() {

		var t = this;

		// Capture all internal/external links
		var imgRegex = new RegExp(/\.(gif|jpg|jpeg|tiff|png)$/i);
		var otherRegex = new RegExp(/\.(pdf|css|js|zip|tar|ppt|pptx|doc|docx|xls|xlsx)$/i);

		var allowedAnchors = [];
		$.each(M.ALLOWED_DOMAINS, function(index, domain) {

			allowedAnchors.push('a[href^="'+domain+'"]');
		});

		// All links pointing to the website: capture them and do the request with fetch functions
		// $(document).on('click', 'a[href^="'+M.HOME_DOMAIN+'"]', function(e) {
		$(document).on('click', allowedAnchors.join(','), function(e) {

			var anchor = $(this);
			var url = anchor.attr('href');

			if (imgRegex.test(url)) {

				var method = 'linksImage';

				// Allow for a 3rd party plugin to intercept it (eg: photoSwipe). If no plugin did, then open in new window
				if (popJSLibraryManager.getLibraries(method).length) {

					e.preventDefault();
					var args = {
						anchor: anchor
					}
					popJSLibraryManager.execute(method, args);
				}
				else {

					t.linkBlankTarget(anchor);
				}
			}
			else if (otherRegex.test(url)) {
				
				t.linkBlankTarget(anchor);
			}
			else {

				// Execute functions to reset state of DOM (eg: for bubbling prevention), etc
				popCustomBootstrap.resetBubbling();

				// Caged Target: allow to pageSections to open all links within inside of itself.
				// Eg: Quickview (any link clicked in the Quickview will also open in the Quickview)
				var pageSection = popManager.getPageSection(anchor);
				// var cagedTarget = pageSection.data('caged-target');
				var target = anchor.attr('target') || M.URLPARAM_TARGET_MAIN; // || '_self';

				// Open the whole page? Then do not catch. Needed to switch language
				if (target == M.URLPARAM_TARGET_FULL) {
					e.preventDefault();
					t.maybeaddspinner(anchor, url);
					window.location = url;
					return;
				}
				// Print: special case, handle differently
				if (target == M.URLPARAM_TARGET_PRINT) {
					e.preventDefault();
					t.openPrint(url);
					return;
				}
				if (popManager.targetExists(target)) {
					
					// Internal links: use ajax / output = json to process them
					e.preventDefault();

					// Special case for the homepage: the link can be https://www.mesym.com or https://www.mesym.com/. However, in the popURLInterceptors it's stored as 'https://www.mesym.com/', and if the link doesn't have that final trail, then it will not be intercepted. So make sure to add it
					if (url == M.HOME_DOMAIN) {
						url = M.HOME_DOMAIN+'/';
					}

					// See if we intercept the link, or go fetch it from the server
					var intercept = false, options = {}, interceptOptions = {};

					// If reloading the url, then do not intercept it at all
					if (anchor.data('reloadurl')) {

						options.reloadurl = true;
					}
					else {
					
						// URL can be intercepted: only for the main target, do not intercept otherwise
						// Only do it if this current call to .click is actually not coming from an interceptor
						// relatedTarget: pass the original clicked link to retrieve atts later on thru 'transfer-atts'
						// var interceptTarget = anchor.attr('target') || anchor.closest('[data-intercept-target]').data('intercept-target') || M.URLPARAM_TARGET_MAIN;
						var interceptTarget = anchor.attr('target') || popManager.getClickFrameTarget(pageSection);
						interceptOptions = {
							event: e, 
							relatedTarget: anchor, 
							target: interceptTarget,
						};

						// Allow a different interceptUrl from the actual URL
						// This is so that, for the Locations Map, we don't intercept modals created under different blocks,
						// which will have different styles. Eg: clicking on https://www.mesym.com/locations-map/?lid[]=13514 on the location link in the Project Details (infoWindow about the location),
						// produces a different result than clicking on http://m3l.localhost/locations-map/?lid[]=13514 on the Projects Map (infoWindow about the projects)
						var interceptUrl = url;
						if (anchor.data('append-intercepturl')) {
							interceptUrl += anchor.data('append-intercepturl');
						}

						// intercept if there are interceptors for that URL
						intercept = popURLInterceptors.getInterceptors(interceptUrl, interceptOptions).length;
					}
					if (intercept) {
						// The intercept will take care from now on, so stop Propagation from this side
						// Done in the interceptor
						// 	e.stopPropagation();

						// First push the URL and only then do the intercept. Needed for initBlockParams (which takes window.location.href to initialize the params)
						// Comment Leo: commented since now function newRuntimeMemoryPage takes the URL from the topLevelFeedback, not from the browser URL
						// popBrowserHistory.pushState(url);
						popURLInterceptors.intercept(interceptUrl, interceptOptions);
					}
					else {

						// Fetch url from the server
						options.target = target;

						// Keep a reference to the original link
						options['js-args'] = {
							relatedTarget: anchor
						};

						// When calling popManager.click (eg: done through the quicklinks) the anchor has class hidden
						// In these cases, use the normal 'Loading'
						
						// Add a spinner next to the clicked link? Allow to override on an anchor by anchor basis
						var addspinner = t.maybeaddspinner(anchor, url);

						// Set the 'loading msg' target if it was defined. If empty, it will show no message, that's why here asking for != 'undefined'
						// Initially, set the value to the opposite of "addspinner": when adding a spinner next to the link, no need for the loading mesage
						var loadingmsg_target = addspinner ? '' : null;
						if (typeof anchor.data('loadingmsg-target') != 'undefined') {
							loadingmsg_target = anchor.data('loadingmsg-target');
						}
						if (loadingmsg_target != null) {
							options['loadingmsg-target'] = loadingmsg_target;
						}
						// Possible options: e.shiftKey, e.altKey, e.metaKey
						// e.ctrlkey is not an option, since Mac shows a contextual menu, doesn't do the link
						if (e.metaKey) {
							
							// Open the tab but don't focus on it
							options.silentDocument = true;
							options['js-args'].inactivePane = true;
						}

						popManager.fetch(url, options);
					}
				}
			}
		});

		// Social Media Links
		$(document).on('click', 'a[target="'+M.URLPARAM_TARGET_SOCIALMEDIA+'"]', function(e) {
		
			e.preventDefault();
			var anchor = $(this);
			var url = anchor.attr('href');
			t.openSocialMedia(url);
		});

		// Scroll to an anchor?
		$(document).on('click', 'a[href^="#"]', function(e) {
		
			var anchor = $(this);
			var url = anchor.attr('href');
			if (url.length > 1) {
				
				// url is something of the type '#element-id'. Since it already starts with '#', it can be used directly
				// to see if the object exists
				// data-toggle property: used by bootstrap (eg: to open a collapse). So if it has this property set, ignore
				var elem = $(url);

				// Bootstrap javascript elems:
				// data-toggle: collapse
				// data-slide: carousel
				var bootstrap = typeof anchor.data('toggle') !== 'undefined' || typeof anchor.data('slide') !== 'undefined';
				if (elem.length && !bootstrap) {

					e.preventDefault();
					popManager.scrollToElem(popManager.getPageSection(elem), elem, true);
				}
			}	
		});	

		// External sites: open in new window
		$(document).on('click', 'a[href^="http://"],a[href^="https://"]', function(e) {


			var anchor = $(this);
			var url = anchor.attr('href');
			var openblank = true;
			$.each(M.ALLOWED_DOMAINS, function(index, domain) {

				if(url.startsWith(domain)) {

					openblank = false;
					return -1;
				}
			});
			if (openblank) {

				t.linkBlankTarget(anchor);
			}
		});		
	},

	openPrint : function(url) {

		// Comment Leo 11/04/2015: Using "_blank" instead of "print" because, since we don't add mode=print to the browser url,
		// if the user just refreshes the page he'll browse the website as normal, and if then he clicks on print, it will open
		// on the same window! that's a bug.
		
		window.open(url, '_blank');
	},

	openSocialMedia : function(url) {

		window.open(url, '_blank', 'left=20,top=20,width=500,height=400,toolbar=1,resizable=0');
	},

	scrollHandler : function(args) {

		var t = this;
		var pageSection = args.pageSection;

		var lastScrollTop = 0, delta = 200;
		pageSection.scroll(function(){
			
			var nowScrollTop = $(this).scrollTop();
			if(Math.abs(lastScrollTop - nowScrollTop) >= delta){

				if (nowScrollTop > lastScrollTop){
					
					// ACTION ON SCROLLING DOWN 
					$(window).triggerHandler('scroll:down');
				} 
				else {
				
					// ACTION ON SCROLLING UP 
					$(window).triggerHandler('scroll:up');
				}
				lastScrollTop = nowScrollTop;
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popSystem, ['documentInitialized', 'activeLinks', 'newWindow', 'fullscreen', 'clickURLParam', 'initDelegatorFilter', 'initBlockGroupFilter', 'initBlockFilter', 'reloadBlockGroup', 'reloadBlock', 'loadLatestBlock', 'timeoutLoadLatestBlock', 'displayBlockDatasetCount', 'clearDatasetCount', 'clearDatasetCountOnUserLoggedOut', 'replicateTopLevel', 'replicatePageSection', 'closePageSection', 'closePageSectionOnTabShown', 'onDestroyPageSwitchTab', 'addOpenTab', 'closePageTab', 'resetOnSuccess', 'resetOnUserLogout', 'closeMessageFeedbacksOnPageSectionOpen', 'closePageSectionOnSuccess', 'destroyPageOnUserLoggedOut', 'destroyPageOnUserNoRole', 'refetchBlockOnUserLoggedIn', 'nonendingRefetchBlockOnUserLoggedIn', 'nonendingRefetchBlockOnUserLoggedInOut', 'deleteBlockFeedbackValueOnUserLoggedInOut', 'scrollTopOnUserLoggedInOut', 'refetchBlockGroupOnUserLoggedIn', 'destroyPageOnSuccess', 'destroyPage', 'initFilter', 'interceptForm', 'forms', /*'initBlockProxyFilter', */'clearInput', 'makeAlwaysRefetchBlock', 'scrollHandler', 'addDomainClass']);