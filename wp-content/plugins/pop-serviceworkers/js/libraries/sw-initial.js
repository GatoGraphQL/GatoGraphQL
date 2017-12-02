"use strict";
if ('serviceWorker' in navigator) {
	
	// Catch the messages delivered by the Service Worker, and act upon them
	navigator.serviceWorker.onmessage = function (event) {

		// Otherwise, re-dispatch the event after the document is loaded
		if (typeof popServiceWorkers != 'undefined') {
			
			popServiceWorkers.processEvent(event);
		}
		else {

			window.addEventListener('load', function() {
				popServiceWorkers.processEvent(event);
			});
		}
	};
}