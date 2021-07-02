"use strict";
(function($){
window.pop.Utils = {
	
	getPath : function(url) {
		
		// Depending on the locale, get everything either after: 
	    // - The first "/" after "//" (https://mesym.com/about/)
	    // - The following "/" (https://mesym.com/en/about/)
	    // To always produce path "about/"

	    // Taken from https://stackoverflow.com/questions/12023430/regex-url-path-from-url
	    var parser = document.createElement('a');
	    parser.href = url; // This will either produce "/about/" or "/en/about/"

	    // Remove the leading "/" (pathStartPos = 1) or "/en/" (pathStartPos = 4, added by qTrans)
	    return parser.pathname.substr(pop.c.PATHSTARTPOS);
	},
};
})(jQuery);
