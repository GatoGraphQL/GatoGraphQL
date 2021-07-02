"use strict";
(function($){
window.pop.BootstrapInput = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	// fill the input when a the modal is shown (eg: Share by email)
	fillModalInput : function(args) {

		var that = this;

		var targets = args.targets;
		targets.each(function() {

			var input = $(this);
			var modal = input.closest('.modal');
			modal.on('show.bs.modal', function(e) {

				var link = $(e.relatedTarget);
				pop.InputFunctions.fillInput(input, link);
			});
		});
	},
	// fill the input when a the modal is shown (eg: Share by email)
	fillModalURLInput : function(args) {

		var that = this;

		var domain = args.domain, targets = args.targets;
		targets.each(function() {

			var input = $(this);
			var modal = input.closest('.modal');
			modal.on('show.bs.modal', function(e) {

				var link = $(e.relatedTarget);
				pop.InputFunctions.fillURLInput(domain, input, link);
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapInput, ['fillModalInput', 'fillModalURLInput']);
