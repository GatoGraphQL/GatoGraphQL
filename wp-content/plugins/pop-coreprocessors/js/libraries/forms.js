(function($){
window.popForms = {

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

	formHandleDisabledLayer : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		var pssId = popManager.getSettingsId(pageSection);
		var bsId = popManager.getSettingsId(block);

		block.on('fetched', function(e, response) {
	
			// Delete the textarea / fields if the form was successful
			var blockFeedback = response.feedback.block[pssId][bsId];

			// Remove the disabled layer?
			if (!(blockFeedback['validate-checkpoints'] && blockFeedback['checkpointvalidation-failed'])) {
				targets.children('.form-inner').children('.pop-disabledlayer').addClass('hidden');
			}
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
popJSLibraryManager.register(popForms, ['interceptForm', 'forms', 'formHandleDisabledLayer', 'clearInput']);
