"use strict";
(function($){
window.pop.Forms = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	interceptForm : function(args) {

		var that = this;

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

	forms : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		var pssId = pop.Manager.getSettingsId(pageSection);
		var bsId = pop.Manager.getSettingsId(block);

		targets.submit(function(e) {

			e.preventDefault();

			var form = $(this);

			// Allow form inputs to modify their value before sending anything
			// (eg: editor can save itself, pop-browserurl can take the browser url, etc)
			form.triggerHandler('beforeSubmit');

			var options = {
				type: 'POST', 
				"post-data": form.serialize(),
				'scroll-top': true,
			};

			// Submit to fetch the Block data, when it comes back process it through a handler on the gdFullCalendar side
			pop.Manager.fetchBlock(pageSection, block, options);
		});	

		block.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var blockFeedback = response.statefuldata.feedback.block[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pssId][bsId];

			// result = true means it was successful
			if (blockFeedback.result === true) {

				// Allow other components to clear themselves. Eg: editor
				targets.triggerHandler('clear');
			}

			// If there is feedback, scroll to it
			if (/*blockFeedback['show-msgs'] && */blockFeedback['msgs'] && blockFeedback['msgs'].length) {
				pop.Manager.scrollToElem(pageSection, block.find('.dataload-feedbackmessage').first(), true);
			}

			pop.Manager.maybeRedirect(blockFeedback);
		});
	},

	clearInput : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.each(function () {
			var input = $(this);
			input.closest('form').on('clear', function() {
				input.val('');
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Forms, ['interceptForm', 'forms', 'clearInput']);
