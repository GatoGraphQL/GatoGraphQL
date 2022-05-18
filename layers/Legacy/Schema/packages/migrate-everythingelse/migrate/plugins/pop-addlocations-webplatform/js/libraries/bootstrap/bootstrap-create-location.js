"use strict";
(function($){
window.pop.BootstrapCreateLocation = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	createLocationModal : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		that.execCreateLocationModal(pageSection, targets);
	},

	createLocationModalBlock : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets, link = args.relatedTarget;

		// Transfer the attributes to the modals. Execute it now, because 'show.bs.modal' cannot be intercepted
		// when the modal is first created from the MODALS pageSection
		that.transferAttributes(targets.closest('.modal'), link);

		that.execCreateLocationModal(pageSection, targets.closest('.modal'));
	},

	maybeCloseLocationModal : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = pop.Manager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			var block = $(this);
			var bsId = pop.Manager.getSettingsId(block);				
			var blockFeedback = response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// Close the Modal
				block.closest('.modal').modal('hide');				
			}
		});
	},

	//-------------------------------------------------
	// PRIVATE FUNCTIONS
	//-------------------------------------------------

	transferAttributes : function(modals, link) {

		var that = this;

		// Make sure the link we got is the original one, and not the intercepted one
		// All properties are stored under the original link, not the interceptor
		link = pop.Manager.getOriginalLink(link);

		// Add an attr to the modal indicating which is the typeahead to trigger a select
		modals.data('typeahead-target', link.data('typeahead-target'));
	},

	execCreateLocationModal : function(pageSection, modals) {

		var that = this;

		// This class is needed for the layoutscript to find out where to find the original typeahead
		// (So we don't tie it directly to the modal, it can also be another component)
		modals.addClass('pop-createlocation');
		modals.on('show.bs.modal', function(e) {

			var modal = $(this);
			var link = $(e.relatedTarget);
			that.transferAttributes(modal, link);
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapCreateLocation, ['createLocationModal', 'createLocationModalBlock', 'maybeCloseLocationModal']);