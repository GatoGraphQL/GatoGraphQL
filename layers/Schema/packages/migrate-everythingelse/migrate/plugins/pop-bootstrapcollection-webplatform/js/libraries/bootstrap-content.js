"use strict";
(function($){
window.pop.BootstrapContent = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	imageResponsive : function(args) {

		var that = this;

		var targets = args.targets;
		
		// Add responsive to the images, and their captions, only inside the content-body
		// (So check to add this class wherever the imgs are needed to be resized. This way, we can exclude the images
		// that don't need so, eg: avatar in the comments)
		targets.find('div.pop-content').addBack('div.pop-content').find('img, .wp-caption').addClass('img-responsive');
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapContent, ['imageResponsive']);
