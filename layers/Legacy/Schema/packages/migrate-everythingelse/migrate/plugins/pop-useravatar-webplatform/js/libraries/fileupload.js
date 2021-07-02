"use strict";
(function($){
window.pop.FileUpload = {

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

			// If there is no action, then do nothing <= User is still not logged in
			if (action) {

				var block = pop.Manager.getBlock(fileupload);
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

				    	var moduleName = fileupload.data('module-upload');
						var rows = $();
						
						var data = {o: o, locale: locale};
						// Set the Block URL for pop.JSRuntimeManager.addModule to know under what URL to place the session-ids
						pop.JSRuntimeManager.setBlockURL(domain, block);
						var html = pop.Manager.getHtml(domain, moduleName, data);
						var row = $(html);

						rows = rows.add(row);
						return rows;
				    },
				    downloadTemplate: function (o) {

						var moduleName = fileupload.data('module-download');
						var rows = $();
						
						var targetConfiguration = pop.Manager.getTargetConfiguration(domain, pageSection, block, moduleName);

						// Expand the JS Keys for the configuration
						pop.Manager.expandJSKeys(targetConfiguration);

						var data = $.extend({}, targetConfiguration, {o: o, locale: locale});

						// Set the Block URL for pop.JSRuntimeManager.addModule to know under what URL to place the session-ids
						pop.JSRuntimeManager.setBlockURL(domain, block);
						var html = pop.Manager.getHtml(domain, moduleName, data);
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
			}
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.FileUpload, ['fileUpload']);