(function($){
popCreateLocation = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	createLocationModal : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		t.execCreateLocationModal(pageSection, targets);
	},

	createLocationModalBlock : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets, link = args.relatedTarget;

		// Transfer the attributes to the modals. Execute it now, because 'show.bs.modal' cannot be intercepted
		// when the modal is first created from the MODALS pageSection
		t.transferAttributes(targets.closest('.modal'), link);

		t.execCreateLocationModal(pageSection, targets.closest('.modal'));
	},

	maybeCloseLocationModal : function(args) {

		var t = this;
		var pageSection = args.pageSection, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);

		targets.on('fetched', function(e, response) {
	
			var block = $(this);
			var bsId = popManager.getSettingsId(block);				
			var blockFeedback = response.feedback.block[pssId][bsId];

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

		var t = this;

		// Make sure the link we got is the original one, and not the intercepted one
		// All properties are stored under the original link, not the interceptor
		link = popManager.getOriginalLink(link);

		// Add an attr to the modal indicating which is the typeahead to trigger a select
		// modal.data('createlocation-typeahead', link.data('createlocation-typeahead'));
		// var typeahead = link.closest('.pop-typeahead');
		// modals.data('createlocation-typeahead', '#'+typeahead.attr('id'));
		modals.data('typeahead-target', link.data('typeahead-target'));
	},

	execCreateLocationModal : function(pageSection, modals) {

		var t = this;

		// This class is needed for the layoutscript to find out where to find the original typeahead
		// (So we don't tie it directly to the modal, it can also be another component)
		modals.addClass('pop-createlocation');
		modals.on('show.bs.modal', function(e) {

			var modal = $(this);
			var link = $(e.relatedTarget);
			t.transferAttributes(modal, link);
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popCreateLocation, ['createLocationModal', 'createLocationModalBlock', 'maybeCloseLocationModal']);