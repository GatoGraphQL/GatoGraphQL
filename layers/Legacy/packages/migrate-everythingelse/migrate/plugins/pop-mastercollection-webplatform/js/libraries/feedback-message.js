"use strict";
(function($){
window.pop.FeedbackMessage = {

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	formatFeedbackMessage : function(message, topLevelSettings, pageSectionSettings, blockSettings) {

		var that = this;
			
		// Allow pop.MultiDomain to modify the message, adding the domain name
	    var args = {
			message: message,
			tls: topLevelSettings, 
			pss: pageSectionSettings, 
			bs: blockSettings
		};
		pop.JSLibraryManager.execute('formatFeedbackMessage', args);
		return args.message;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.FeedbackMessage, []);
