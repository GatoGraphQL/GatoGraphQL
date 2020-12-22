"use strict";
(function($){
window.pop.OnlineOffline = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	documentInitialized : function() {
	
		var that = this;

		that.onlineOffline();
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	onlineOffline : function() {
		
		var that = this;
		window.addEventListener('online', that.checkOnlineOffline);
		window.addEventListener('offline', that.checkOnlineOffline);

		// Already execute it
		that.checkOnlineOffline();
	},

	checkOnlineOffline : function() {
		
		var that = this;

		// Add a class to the body with the status online/offline
		if (navigator.onLine){
			$(document.body).addClass('online').removeClass('offline');
		} else {
			$(document.body).addClass('offline').removeClass('online');
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.OnlineOffline, ['documentInitialized']);
