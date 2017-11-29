"use strict";
(function($){
window.popBootstrapCarouselStatic = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	carouselStatic : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		popBootstrapCarousel.prepareCarousel(pageSection, block, targets);

		targets.carousel({ 
			interval: false,
			wrap: false
		});	
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBootstrapCarouselStatic, ['carouselStatic']);
