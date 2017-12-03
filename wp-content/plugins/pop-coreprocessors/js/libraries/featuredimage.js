"use strict";
(function($){
window.popFeaturedImage = {

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

			// Initialize the frame immediately, so that it can also be accessed by popMediaManagerCORS
			that.frame = wp.media({
				title: M.LABELS.MEDIA_FEATUREDIMAGE_TITLE,
				multiple: false,
				library: {type: 'image'},
				button: {text: M.LABELS.MEDIA_FEATUREDIMAGE_BTN}
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

					var value, imgsrc;
					var imgSize = featuredImage.data('img-size');
					selection.map(function(attachment) {

						attachment = attachment.toJSON();

						// If the image is smaller than 360x200, them 'medium-1' is empty, if that happens use the 'medium' or if not 'thumbnail'
						var imgData = attachment.sizes[imgSize] || attachment.sizes['medium'] || attachment.sizes['thumbnail'];

						// Set the attachment id into the selected featuredImage id
						value = attachment.id;
						imgsrc = {
							src : imgData.url,
							width : imgData.width,
							height : imgData.height
						};
					});

					var block = popManager.getBlock(featuredImage);
					var pageSection = popManager.getPageSection(block);
					var domain = popManager.getBlockTopLevelDomain(block);
					that.merge(domain, pageSection, block, featuredImage, value, imgsrc);
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

	merge : function(domain, pageSection, block, featuredImage, value, imgsrc) {

		var that = this;

		var options = {
			extendContext: {
				value : value,
				img : imgsrc
			}
		}
		
		// Run again the Handlebars template to re-print the image with the new data
		var template = featuredImage.data('merge-template');
		popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
		// var domain = block.data('domain') || getDomain(block.data('toplevel-url'));
		popManager.mergeTargetTemplate(domain, pageSection, block, template, options);
		popManager.runJSMethods(domain, pageSection, block, template, 'full');
	},
	remove : function(domain, pageSection, block, featuredImage) {

		var that = this;
		
		// Delete the value, set the default-img 
		that.merge(domain, pageSection, block, featuredImage, '', '');
	},
	getFrame : function() {

		var that = this;

		// Function needed by popMediaManagerCORS
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
popJSLibraryManager.register(popFeaturedImage, ['documentInitializedIndependent', 'featuredImageSet', 'featuredImageRemove']);
popFeaturedImage.init();
