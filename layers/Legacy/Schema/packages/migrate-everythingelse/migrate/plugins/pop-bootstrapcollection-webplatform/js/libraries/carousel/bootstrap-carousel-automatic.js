"use strict";
(function($){
window.pop.BootstrapCarouselAutomatic = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	carouselAutomatic : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		// Stop the Carousel when filtering, if not Javascript error
		block.on('beforeReload', function(e) {
			
			// After filtering, possibly there will be no results, so start the carousel only if not empty
			targets.carousel('pause');
		});

		pop.BootstrapCarousel.prepareCarousel(pageSection, block, targets);
			
		// Initialize if there's one active item. If none, the carousel has no elements at all
		if (carousel.find('.item.active').length) {

			if (pop.BootstrapCarousel.numberSlides(carousel) >= 2) {

				// Start Automatic Carousel except for mobile phones, or we have an ugly problem where the homepage keeps moving (because height of slides is not uniform)
				if ($(window).width() >= 768) {

					carousel.carousel();	
				}
				else {

					carousel.carousel({ 
						interval: false,
						wrap: false
					});	
				}
				carousel.find('.pop-carousel-controls').removeClass('hidden');
			}
			else {
				
				carousel.find('.pop-carousel-controls').addClass('hidden');
			}
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapCarouselAutomatic, ['carouselAutomatic']);
