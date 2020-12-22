"use strict";
(function($){
window.pop.PhotoSwipe = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	linksImage : function(args) {

		var that = this;
		var anchor = args.anchor;

		// Adapted from http://webdesign.tutsplus.com/tutorials/the-perfect-lightbox-using-photoswipe-with-jquery--cms-23587
		var lightBox = new PhotoSwipe($('#pswp')[0], PhotoSwipeUI_Default, that.getItems(anchor), that.getOptions(anchor));

		// Initialize PhotoSwipe
		lightBox.init();
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	getOptions : function(anchor) {

		var that = this;
		
		// Documentation: http://photoswipe.com/documentation/options.html
		return {
			index: that.getIndex(anchor),
			bgOpacity: 0.8,
			showHideOpacity: true,
			history: false,
			loop: false,
			shareButtons: [
				{
					id: 'download', 
					label: pop.c.LABELS.DOWNLOAD, 
					url: '{{raw_image_url}}?fileaction=download', 
					download: true,
				}
			],
		};
	},

	getItem : function(anchor) {

		var that = this;
		var image = anchor.children('img');
		var item = {
			src: anchor.attr('href'),
			msrc: image.attr('src'),
			w: 640, // width/height are mandatory, so first initialize with random default values
			h: 480,
		};

		// then assign the width/height of the image (we assume the link points to the same image)
		if (image.attr('width') && image.attr('height')) {
			
			item.w = image.attr('width');
			item.h = image.attr('height');
		}

		// size: under "data-size" in the anchor, with format: 'width'x'height'
		if (anchor.data('size')) {
			
			var size = anchor.data('size').split('x')
			item.w = size[0];
			item.h = size[1];
		}

		if (anchor.attr('title')) {
			item.title = anchor.attr('title');
		}
		else if (image.attr('alt')) {
			item.title = image.attr('alt');
		}

		return item;
	},

	getItems : function(anchor) {

		var that = this;
		
		var items = [];

		// Check if the link is inside a gallery
		var gallery = anchor.closest('.gallery');
		if (gallery.length) {

			gallery.find('.gallery-item a').each( function() {

				items.push(that.getItem($(this)));
			});
		}
		else {

			items.push(that.getItem(anchor));
		}

		return items;
	},

	getIndex : function(anchor) {

		var that = this;
		
		// Check if the link is inside a gallery
		var galleryItem = anchor.closest('.gallery-item');
		if (galleryItem.length) {

			return galleryItem.index();
		}

		return 0;
	},
	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.PhotoSwipe, ['linksImage']);