"use strict";
(function($){
window.pop.Cookies = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	cookies : function(args) {

		var that = this;
		var targets = args.targets;
		targets.each(function() {

			var cookie = $(this);

			if (!($.cookie(cookie.data('cookieid')))) {
			
				cookie.removeClass('hidden');
			}			
			cookie.children("button.close").click(function(e){

				e.preventDefault();
			
				// After click on dismiss, Set the cookie
				var cookie = $(this).parent('.cookie');
				$.cookie(cookie.data('cookieid'), "set", { expires: 90, path: "/" });
			});	
		});	
	},

	cookieToggleClass : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, targets = args.targets;		
		targets.each(function() {

			var container = $(this);
			var cookieid = container.data('cookieid');

			// Only pay attention to the value of the cookie when first loading the website
			// Eg: toggle sideinfo will execute "cookieToggleClass" many times, but only at the beginning we must open/keep closed the sideinfo
			// if (pop.Manager.isFirstLoad(pageSection)) {
			
			that.cookieToggleClassInitial(pageSection, container);
			// if (container.data('cookieonready')) {
				
			// 	// Do document ready so that the collapse works for Google Maps (eg: Homepage's Project widget)
			// 	jQuery(document).ready(function($) {

			// 		that.cookieToggleClassInitial(pageSection, container);
			// 	});
			// }
			// else {
			// 	that.cookieToggleClassInitial(pageSection, container);
			// }

			// }

			// Delete cookie when clicking on the corresponding btn
			if (container.data('deletecookiebtn')) {
				var selector = container.data('deletecookiebtn');
				var btn = (selector == 'self' ? container : $(selector));
				btn.click(function(e){
					$.cookie(cookieid, null, { expires: -1, path: "/" });
				});	
			}			
			// Set cookie when clicking on the corresponding btn
			if (container.data('setcookiebtn')) {
				var selector = container.data('setcookiebtn');
				var btn = (selector == 'self' ? container : $(selector));
				btn.click(function(e){
					$.cookie(cookieid, "set", { expires: 180, path: "/" });
				});	
			}			
			// Toggle cookie when clicking on the corresponding btn
			if (container.data('togglecookiebtn')) {
				var selector = container.data('togglecookiebtn');
				var btn = (selector == 'self' ? container : $(selector));
				btn.click(function(e){
					if ($.cookie(cookieid)) {
						$.cookie(cookieid, null, { expires: -1, path: "/" });
					}
					else {
						$.cookie(cookieid, "set", { expires: 180, path: "/" });
					}
				});	
			}		
		});	
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	cookieToggleClassInitial : function(pageSection, container) {
	
		var that = this;

		var cookieid = container.data('cookieid');

		// Values for initial: 'set' or 'notset'. Default 'notset' => It will add the class to the target if the cookie does not exist
		var initial = container.data('initial') || 'notset';
		if ((initial == 'set' && $.cookie(cookieid)) || (initial == 'notset' && !$.cookie(cookieid)) || (initial == 'toggle')) {

			var target = $(container.data('cookietarget'));

			// Support for Bootstrap Collapse: execute JS instead of just adding the class, or it doesn't work
			var collapse = container.data('cookiecollapse');
			if (collapse) {

				pop.JSLibraryManager.execute('collapseCookie', {target: target, collapse: collapse});
			}
			var classs = container.data('cookieclass');
			if (classs) {

				if (initial == 'toggle') {

					if ($.cookie(cookieid)) {
						target.removeClass(classs);
					}
					else {
						target.addClass(classs);
					}
				}
				else {
					
					// Add class to the target
					target.addClass(classs);
				}
			}
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Cookies, ['cookies', 'cookieToggleClass']);