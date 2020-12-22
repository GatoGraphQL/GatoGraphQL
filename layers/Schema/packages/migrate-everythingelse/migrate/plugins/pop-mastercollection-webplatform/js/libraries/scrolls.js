"use strict";
(function($){
window.pop.Scrolls = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	scrollHandler : function(args) {

		var that = this;
		var pageSection = args.pageSection;

		var lastScrollTop = 0, delta = 200;
		pageSection.scroll(function(){
			
			var nowScrollTop = $(this).scrollTop();
			if(Math.abs(lastScrollTop - nowScrollTop) >= delta){

				if (nowScrollTop > lastScrollTop){
					
					// ACTION ON SCROLLING DOWN 
					$(window).triggerHandler('scroll:down');
				} 
				else {
				
					// ACTION ON SCROLLING UP 
					$(window).triggerHandler('scroll:up');
				}
				lastScrollTop = nowScrollTop;
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Scrolls, ['scrollHandler']);
