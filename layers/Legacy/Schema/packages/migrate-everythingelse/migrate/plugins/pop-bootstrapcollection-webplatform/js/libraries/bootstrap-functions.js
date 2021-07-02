"use strict";
(function($){
window.pop.BootstrapFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	// Fetch more button
	fetchMore : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		that.toggleButtonState(pageSection, block, targets);
	},

	onFormSubmitToggleButtonState : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		that.toggleButtonState(pageSection, block, targets);
	},

	//-------------------------------------------------
	// PRIVATE FUNCTIONS
	//-------------------------------------------------

	toggleButtonState : function(pageSection, block, targets) {

		var that = this;
		block.on('beforeFetch', function(e, options) {

			// If doing a prepend, then the user can still call on fetchMore and append content
			// Prepend is needed by the loadLatest, which loads more at the top
			if (!options['skip-status']) {

				targets.button('loading');
			}
		});
		block.on('fetchCompleted', function(e) {

			if (!pop.Manager.stopFetchingBlock(pageSection, block)) {

				targets.button('reset');
			}	
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapFunctions, ['fetchMore', 'onFormSubmitToggleButtonState']);
