"use strict";
(function($){
window.pop.MediaManagerState = {

	//-------------------------------------------------
	// INTERNAL VARIABLES
	//-------------------------------------------------
	domain: null, // all Media Managers can share the current domain, since there can only be one Media Manager open at a time
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function() {

		var that = this;

		// Whenever opening the Media Manager, save under what domain it belongs
		// Code copied from wp-includes/js/media-editor.js
		$(document.body).on('click.add-media-button', '.insert-media', function(e) {
		// // Comment Leo 08/01/2018: We introduced an event 'pop:wp.media.editor.open', because mediamanager.js executes later on thanks to code-splitting, 
		// // we can't guarantee to execute 'click.add-media-button' before that same event is intercepted by media-editor.js in function init().
		// // It sucks, but well...
		// $(document).on('pop:wp.media.editor.open', function(e, editorId) {
				
			// // Comment Leo 08/01/2018: since executing hack event 'pop:wp.media.editor.open', we already pass the editorId along, so no need to calculate it
			// // (and also we can't calculate it, since now the fired event is a different one)
			var elem = $(e.currentTarget), editor = $('#'+elem.data('editor'));

			// Obtain the domain from the editor's block
			var block = pop.Manager.getBlock(editor);
			that.domain = pop.Manager.getBlockTopLevelDomain(block);
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	getDomain : function() {

		var that = this;
		return that.domain;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.MediaManagerState, ['documentInitialized']);
