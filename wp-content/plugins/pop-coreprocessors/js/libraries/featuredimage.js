(function($){
popFeaturedImage = {

	//-------------------------------------------------
	// INTERNAL VARIABLES
	//-------------------------------------------------

	frame: null,

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function(args) {

		var t = this;

		$(document).ready(function($) {

			t.frame = wp.media({
				title: M.LABELS.MEDIA_FEATUREDIMAGE_TITLE,
				multiple: false,
				library: {type: 'image'},
				button: {text: M.LABELS.MEDIA_FEATUREDIMAGE_BTN}
			});
			t.frame.on('open',function() {

				// We know which is the featuredImage opening the frame because it is the only one with class "open"
				var featuredImage = t.getOpenFeaturedImage();

				// If there's an ID already selected, then select it in the Media Manager
				var selected = featuredImage.find('input[type="hidden"]');
				if (selected.val()) {
					var selection = t.frame.state().get('selection');
					attachment = wp.media.attachment(selected.val());
					attachment.fetch();
					selection.add( attachment ? [ attachment ] : [] );
				}
			});
			t.frame.on('close',function() {
				
				var featuredImage = $('.pop-featuredimage.open');
				var configuration = {};
				var selection = t.frame.state().get('selection');
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
					t.merge(domain, pageSection, block, featuredImage, value, imgsrc);
				}
				
				featuredImage.removeClass('open');
			});

			// Allow MediaManagerCors to initialize itself
			$(document).triggerHandler('initialized.featuredImage', [t.frame])
		});
	},
	featuredImageSet : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		targets.click(function(e) {
				
			e.preventDefault();
			var button = $(this);			
			var featuredImage = button.closest('.pop-featuredimage');

			// We know which is the featuredImage opening the frame because it is the only one with class "open"
			featuredImage.addClass('open');

			t.frame.open();
		});
	},
	featuredImageRemove : function(args) {
	
		var t = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		targets.click(function(e) {
				
			e.preventDefault();
			var button = $(this);			
			var featuredImage = button.closest('.pop-featuredimage');

			t.remove(domain, pageSection, block, featuredImage);
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	merge : function(domain, pageSection, block, featuredImage, value, imgsrc) {

		var t = this;

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

		var t = this;
		
		// Delete the value, set the default-img 
		t.merge(domain, pageSection, block, featuredImage, '', '');
	},
	getFrame : function() {

		var t = this;

		// Function needed by popMediaManagerCORS
		return t.frame;
	},
	getOpenFeaturedImage : function() {

		var t = this;

		// We know which is the featuredImage opening the frame because it is the only one with class "open"
		return $('.pop-featuredimage.open');
	}	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popFeaturedImage, ['documentInitialized', 'featuredImageSet', 'featuredImageRemove']);