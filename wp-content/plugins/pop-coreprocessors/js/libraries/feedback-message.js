(function($){
window.popFeedbackMessage = {

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	formatFeedbackMessage : function(message, topLevelSettings, pageSectionSettings, blockSettings) {

		var that = this;
			
		// Allow popMultiDomain to modify the message, adding the domain name
	    var args = {
			message: message,
			tls: topLevelSettings, 
			pss: pageSectionSettings, 
			bs: blockSettings
		};
		popJSLibraryManager.execute('formatFeedbackMessage', args);
		return args.message;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popFeedbackMessage, []);
