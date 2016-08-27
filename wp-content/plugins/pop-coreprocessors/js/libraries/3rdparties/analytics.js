(function($){
popGA = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	documentInitialized : function() {
	
		var t = this;

		// Google Analytics for WordPress by Yoast v4.3.5 | http://yoast.com/wordpress/google-analytics/
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', M.GOOGLEANALYTICS]);
		_gaq.push(['_trackPageview']);
		(function () {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
	},

	replaceState : function(args) {
	
		var t = this;

		_gaq.push(['_trackPageview']);
	},
	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popGA, ['documentInitialized', 'replaceState']);