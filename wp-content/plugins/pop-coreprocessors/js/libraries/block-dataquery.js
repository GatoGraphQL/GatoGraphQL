(function($){
popBlockDataQuery = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

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
					
					popBlockGroupDataQuery.filterBlockGroup(pageSection, block, form) ;	
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

	makeAlwaysRefetchBlock : function(args) {

		var t = this;
		var pageSection = args.pageSection, block = args.block;

		block.on('visible', function () {
			var block = $(this);
			t.refetchBlock(pageSection, block);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

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
			popBlockDataQuery.refetchBlock(pageSection, block);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBlockDataQuery, ['initDelegatorFilter', 'initBlockFilter', 'reloadBlock', 'loadLatestBlock', 'timeoutLoadLatestBlock', 'refetchBlockOnUserLoggedIn', 'nonendingRefetchBlockOnUserLoggedIn', 'nonendingRefetchBlockOnUserLoggedInOut', 'initFilter', 'makeAlwaysRefetchBlock']);
