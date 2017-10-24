(function($){
popOnlineOffline = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	documentInitialized : function() {
	
		var t = this;

		t.onlineOffline();
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	onlineOffline : function() {
		
		var t = this;
		window.addEventListener('online', t.checkOnlineOffline);
		window.addEventListener('offline', t.checkOnlineOffline);

		// Already execute it
		t.checkOnlineOffline();
	},

	checkOnlineOffline : function() {
		
		var t = this;

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
popJSLibraryManager.register(popOnlineOffline, ['documentInitialized']);
