"use strict";
(function($){
window.pop.BlockDataQuery = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initDelegatorFilter : function(args) {

		var that = this;
		var delegatorPageSection = args.pageSection, targets = args.targets;

		// Initialize Filters
		targets.submit(function(e) {
			
			e.preventDefault();
			var form = $(this);

			// Allow the form to indicate which is the block it is filter, under data-target
			// If none is specified, then use its own block
			if (form.data('blocktarget')) {
				
				var block = $(form.data('blocktarget'));
				var pageSection = pop.Manager.getPageSection(block);

				// If the target is a block, then directly filter
				// If it is a blockgroup, then it will pass the filtering to its active block
				if (pop.Manager.isBlockGroup(block)) {
					
					pop.BlockGroupDataQuery.filterBlockGroup(pageSection, block, form) ;	
				}
				else {

					pop.Manager.filter(pageSection, block, form);
				}

				// For mobile phone version: must get the active pageSection back to main
				pop.PageSectionManager.close(delegatorPageSection, 'xs');
			}
		});
	},

	initBlockFilter : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Initialize Filters
		targets.submit(function(e) {
			
			e.preventDefault();
			var form = $(this);

			// Allow the form to indicate which is the block it is filter, under data-target
			// If none is specified, then use its own block
			if (form.data('blocktarget')) {
				
				block = $(form.data('blocktarget'));
				pageSection = pop.Manager.getPageSection(block);
			}
			pop.Manager.filter(pageSection, block, form);
		});
	},

	reloadBlock : function(args) {

		var that = this;

		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.click(function(e) {
			
			e.preventDefault();
			pop.Manager.reload(pageSection, block);
		});
	},

	loadLatestBlock : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.click(function(e) {
			
			e.preventDefault();
			pop.Manager.loadLatest(domain, pageSection, block);
		});
	},

	timeoutLoadLatestBlock : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.each(function() {
			
			var target = $(this);

			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, target);
			var time = target.data('clicktime') || 30000; // Default: 30 seconds
			var datasetCountTarget = jsSettings['datasetcount-target'];
			var updateTitle = jsSettings['datasetcount-updatetitle'];
			var onlyLoggedIn = jsSettings['only-loggedin'] || false;
			that.execTimeoutLoadLatestBlock(domain, pageSection, block, target, time, datasetCountTarget, updateTitle, onlyLoggedIn);
		});
	},

	initFilter : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Initialize Filters
		targets.each(function() {
			
			var filter = $(this);

			// Set Filter Params, for if filtering already (eg: if e.preventDefault() below has failed)
			pop.Manager.setFilterBlockParams(pageSection, block, filter);
			// pop.Manager.setFilterParams(pageSection, filter);	
		});
	},

	makeAlwaysRefetchBlock : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block;

		block.on('visible', function () {
			var block = $(this);
			that.refetchBlock(pageSection, block);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	execTimeoutLoadLatestBlock : function(domain, pageSection, block, target, time, datasetCountTarget, updateTitle, onlyLoggedIn) {

		var that = this;
		var options = {
			'skip-status': true,
			
			// Show the count of results in the toplevel bell
			'datasetcount-target': datasetCountTarget, 
			'datasetcount-updatetitle': updateTitle,
		};

		// Only if the user is logged in? Eg: Latest Notifications, it makes no sense to fetch constantly for logged out users
		if (onlyLoggedIn && !pop.UserAccount.isLoggedIn(domain)) {

			$(document).one('user:loggedin:'+domain, function(e, source) {

				that.execTimeoutLoadLatestBlock(domain, pageSection, block, target, time, datasetCountTarget, updateTitle, onlyLoggedIn);
			});

			// No more needed
			return;
		}

		setTimeout(function () {

			// Make sure object still exists
			if ($('#'+target.attr('id')).length) {

				pop.Manager.loadLatest(domain, pageSection, block, options);
				that.execTimeoutLoadLatestBlock(domain, pageSection, block, target, time, datasetCountTarget, updateTitle, onlyLoggedIn);
			}			
		}, time);
	},

	execRefetchBlock : function(pageSection, blocks) {

		var that = this;
		blocks.each(function() {
	
			// To double-check that the object still exists in the DOM and was not removed when doing pop.Manager.destroyPageSectionPage
			var block = $('#'+$(this).attr('id'));
			if (block.length) {
				
				that.refetchBlock(pageSection, block);
			}
		});
	},

	refetchBlock : function(pageSection, block, options) {
	
		var that = this;
		options = options || {};
		options['post-data'] = block.data('post-data');
		options['loading-msg'] = pop.c.LOADING_MSG; // Needed so that when doing nonending refetch block when user logs in and out, the message for Add/Edit Stance changes from Submitting to Loading
		pop.Manager.refetch(pageSection, block, options);
	},

	makeOneTimeRefetch : function(pageSection, block) {

		var that = this;

		// If the block has not loaded its content, then do nothing, whenever it gets initialized it will send the request all by itself
		// This also handles when skip-data-load=true (eg: Search). Doing instead if (!that.jsInitialized(block)) { fails with Search, since it is not initialized but the 
		// initialization does not fetch the content either
		if (!pop.Manager.jsInitialized(block) || !pop.Manager.isContentLoaded(pageSection, block)) {

			return;
		}

		block.one('visible', function () {
			var block = $(this);
			pop.BlockDataQuery.refetchBlock(pageSection, block);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BlockDataQuery, ['initDelegatorFilter', 'initBlockFilter', 'reloadBlock', 'loadLatestBlock', 'timeoutLoadLatestBlock', 'initFilter', 'makeAlwaysRefetchBlock']);
