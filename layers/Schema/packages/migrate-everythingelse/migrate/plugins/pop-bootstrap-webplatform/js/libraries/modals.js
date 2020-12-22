"use strict";
(function($){
window.pop.Modals = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	modalForm : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.on('show.bs.modal', function(e) {

			var modal = $(this);
			var link = $(e.relatedTarget);

			// Close the feedback message
			var blocks = $(modal.data('initjs-targets'));
			blocks.each(function() {
				var block = $(this);
				pop.Manager.closeFeedbackMessage(block);
			})
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Modals, ['modalForm']);