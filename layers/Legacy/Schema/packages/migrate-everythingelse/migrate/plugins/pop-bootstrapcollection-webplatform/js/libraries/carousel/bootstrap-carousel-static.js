"use strict";
(function($){
window.pop.BootstrapCarouselStatic = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	carouselStatic : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		pop.BootstrapCarousel.prepareCarousel(pageSection, block, targets);

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
pop.JSLibraryManager.register(pop.BootstrapCarouselStatic, ['carouselStatic']);
