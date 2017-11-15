"use strict";
(function($){
window.popFileUpload = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	fileUpload : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, targets = args.targets;

		targets.each(function() {

			var that = this;
			var fileupload = $(this);
			var action = fileupload.data('action');
			var block = popManager.getBlock(fileupload);

			fileupload.fileupload({
				url: action,
				autoUpload: true,
				maxNumberOfFiles: 1,
				maxFileSize: 2000000,
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,

			    // filesContainer: $('table.files'),
			    uploadTemplateId: null,
			    downloadTemplateId: null,
			    uploadTemplate: function (o) {

			    	var templateName = fileupload.data('template-upload');
					var rows = $();
					
					var data = {o: o, locale: locale};
					// Set the Block URL for popJSRuntimeManager.addTemplateId to know under what URL to place the session-ids
					popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
					var html = popManager.getHtml(/*domain, */templateName, data);
					var row = $(html);

					rows = rows.add(row);
					return rows;
			    },
			    downloadTemplate: function (o) {

					var templateName = fileupload.data('template-download');
					var rows = $();
					
					var targetConfiguration = popManager.getTargetConfiguration(domain, pageSection, block, templateName);

					// Expand the JS Keys for the configuration
					popManager.expandJSKeys(targetConfiguration);

					var data = $.extend({}, targetConfiguration, {o: o, locale: locale});

					// Set the Block URL for popJSRuntimeManager.addTemplateId to know under what URL to place the session-ids
					popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
					var html = popManager.getHtml(/*domain, */templateName, data);
					var row = $(html);

					// Allow for wp-prettyPhoto to add its handlers here
					$(document).triggerHandler('gd-fileupload:rendered', [row]);

					rows = rows.add(row);
					return rows;
			    },
			    // Callback for file uploaded:
				completed: function (e, data) {
					
					fileupload.find('.pop-fileuploaded-hide').hide();
				},
				
				// Callback for file deletion:
				destroyed: function (e, data) {
					
					fileupload.find('.pop-fileuploaded-hide').show();
				}
			});

			// Load existing files: only if editing, don't do it for creating a new profile
			if (fileupload.hasClass('pop-fileupload-loadfromserver')) {

				$.getJSON(action, function (result) {
					if (result && result.files && result.files.length) {
						fileupload.fileupload('option', 'done')
							.call(that, $.Event('done'), {result: result});
					}
				});
			}
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popFileUpload, ['fileUpload']);