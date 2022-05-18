"use strict";
(function($){
window.pop.FeaturedImage = {

	//-------------------------------------------------
	// INTERNAL VARIABLES
	//-------------------------------------------------

	frame: null,

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	init : function() {

		var that = this;
		
		$(document).ready(function($) {

			// Initialize the frame immediately, so that it can also be accessed by pop.MediaManagerCORS
			that.frame = wp.media({
				title: pop.c.LABELS.MEDIA_FEATUREDIMAGE_TITLE,
				multiple: false,
				library: {type: 'image'},
				button: {text: pop.c.LABELS.MEDIA_FEATUREDIMAGE_BTN}
			});
		});
	},
	documentInitializedIndependent : function(args) {

		var that = this;

		$(document).ready(function($) {

			that.frame.on('open',function() {

				// We know which is the featuredImage opening the frame because it is the only one with class "open"
				var featuredImage = that.getOpenFeaturedImage();

				// If there's an ID already selected, then select it in the Media Manager
				var selected = featuredImage.find('input[type="hidden"]');
				if (selected.val()) {
					var selection = that.frame.state().get('selection');
					var attachment = wp.media.attachment(selected.val());
					attachment.fetch();
					selection.add( attachment ? [ attachment ] : [] );
				}
			});
			that.frame.on('close',function() {
				
				var featuredImage = $('.pop-featuredimage.open');

				var configuration = {};
				var selection = that.frame.state().get('selection');
				if (selection.length) {

					var imgSize = featuredImage.data('img-size');

					// Recreate the datum with the needed structure, set through JS settings
					var datum;
					var block = pop.Manager.getBlock(featuredImage);
					var pageSection = pop.Manager.getPageSection(block);
					var domain = pop.Manager.getBlockTopLevelDomain(block);

					// The datum placeholder was saved in FeaturedImageInner's JS settings
					var moduleName = featuredImage.data('merge-component');
					var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, moduleName);
					var datum_placeholder = jsSettings['datum-placeholder'];
					
					selection.map(function(attachment) {

						attachment = attachment.toJSON();

						// If the image is smaller than 360x200, them 'medium-1' is empty, if that happens use the 'medium' or if not 'thumbnail'
						var imgData = attachment.sizes[imgSize] || attachment.sizes['medium'] || attachment.sizes['thumbnail'];

						// Set the attachment id into the selected featuredImage id
						// Convert the placeholder string into JSON, after replacing the values inside
						datum = JSON.parse(datum_placeholder.format(
							attachment.id, 
							imgData.url, 
							imgData.width, 
							imgData.height
						));
					});

					// Finally, having recreated the datum as the dbObject, it can be merged
					that.merge(domain, pageSection, block, featuredImage, datum);
				}
				
				featuredImage.removeClass('open');
			});

			// Allow MediaManagerCors to initialize itself
			$(document).triggerHandler('initialized.featuredImage', [that.frame])
		});
	},
	featuredImageSet : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		targets.click(function(e) {
				
			e.preventDefault();
			var button = $(this);			
			var featuredImage = button.closest('.pop-featuredimage');

			// We know which is the featuredImage opening the frame because it is the only one with class "open"
			featuredImage.addClass('open');

			that.frame.open();
		});
	},
	featuredImageRemove : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		targets.click(function(e) {
				
			e.preventDefault();
			var button = $(this);			
			var featuredImage = button.closest('.pop-featuredimage');

			that.remove(domain, pageSection, block, featuredImage);
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	merge : function(domain, pageSection, block, featuredImage, datum) {

		var that = this;

		var context = {
			dbObject: datum,
		};
		var options = {
			operation: pop.c.URLPARAM_OPERATION_REPLACE,
		}
		
		// Run again the Handlebars module to re-print the image with the new data
		var moduleName = featuredImage.data('merge-component');
		pop.DynamicRender.render(domain, pageSection, block, moduleName, featuredImage, context, options);
	},
	remove : function(domain, pageSection, block, featuredImage) {

		var that = this;
		
		// Delete the value, set the default-img 
		that.merge(domain, pageSection, block, featuredImage, {});
	},
	getFrame : function() {

		var that = this;

		// Function needed by pop.MediaManagerCORS
		return that.frame;
	},
	getOpenFeaturedImage : function() {

		var that = this;

		// We know which is the featuredImage opening the frame because it is the only one with class "open"
		return $('.pop-featuredimage.open');
	}	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.FeaturedImage, ['documentInitializedIndependent', 'featuredImageSet', 'featuredImageRemove']);
pop.FeaturedImage.init();
