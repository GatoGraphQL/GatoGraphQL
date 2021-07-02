"use strict";
(function($){
window.pop.MediaManager = {

	//-------------------------------------------------
	// INTERNAL VARIABLES
	//-------------------------------------------------
	// domain: null, // all Media Managers can share the current domain, since there can only be one Media Manager open at a time
	initialized: {
		// editor: {},
		// featuredImage: false
	},
	needsRefresh: {
		// editor: {},
		// featuredImage: false
	},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// init : function() {

	// 	var that = this;

	// 	// Whenever opening the Media Manager, save under what domain it belongs
	// 	// Code copied from wp-includes/js/media-editor.js
	// 	$(document.body).on('click.add-media-button', '.insert-media', function(e) {
	// 	// // Comment Leo 08/01/2018: We introduced an event 'pop:wp.media.editor.open', because mediamanager.js executes later on thanks to code-splitting, 
	// 	// // we can't guarantee to execute 'click.add-media-button' before that same event is intercepted by media-editor.js in function init().
	// 	// // It sucks, but well...
	// 	// $(document).on('pop:wp.media.editor.open', function(e, editorId) {
				
	// 		// // Comment Leo 08/01/2018: since executing hack event 'pop:wp.media.editor.open', we already pass the editorId along, so no need to calculate it
	// 		// // (and also we can't calculate it, since now the fired event is a different one)
	// 		var elem = $(e.currentTarget);
	// 		var editorId = elem.data('editor');
	// 		var editor = $('#'+editorId);

	// 		// Obtain the domain from the editor's block
	// 		var block = pop.Manager.getBlock(editor);
	// 		that.domain = pop.Manager.getBlockTopLevelDomain(block);
	// 	});
	// },

	documentInitializedIndependent : function() {

		var that = this;

		// Remove the 'post_id', we don't need it ever
		$(document).on('uploader:start:settings', function(e, settings) {

			that.setUploadSettings(settings);
		});

		// Whenever the user logs out, set the needsRefresh as true, so that next user logging in will have the media manager refreshed
		$(document).on('user:loggedout', function(e) {
			
			// Refresh everything, without paying attention to what domain the user is logging out from
			// It takes away some complexity
			that.needsRefresh = {
				featuredImage: true,
				'editor-gallery': true,
				'editor-insert': true
			};
		});

		$(document).ready( function($) {

			// Notice that I have also added element '.media-menu-item', so that when clicking on the side navigation on the left,
			// switching between Insert Media and Gallery, it also refreshes the content if needed
			$(document.body).on('click.add-media-button', '.insert-media,.media-menu-item', function(e) {
			// // Comment Leo 08/01/2018: We introduced an event 'pop:wp.media.editor.open', because mediamanager.js executes later on thanks to code-splitting, 
			// // we can't guarantee to execute 'click.add-media-button' before that same event is intercepted by media-editor.js in function init().
			// // It sucks, but well...
			// $(document).on('pop:wp.media.editor.open', function(e) {

				// Trigger a refresh of the data after the user logs in/out
				// States could be either 'gallery' or 'insert'
				var state = wp.media.frame.state();
				var states = ['gallery', 'insert'];
				if (states.indexOf(state.id) > -1) {

					// Only if it had been previously initialized, so that the refresh is not executed the first time it opens
					if (that.initialized['editor-'+state.id] && that.needsRefresh['editor-'+state.id]) {

						that.refresh(state);
					}

					// Set as initialized, no need to refresh anymore
					that.initialized['editor-'+state.id] = true;
					that.needsRefresh['editor-'+state.id] = false;
				}
			});

			// Initialize only if/when the pop.FeaturedImage has been loaded
			if (typeof pop.FeaturedImage != 'undefined') {
				
				that.initFeaturedImage();
			}
			else {

				$(document).on('initialized.featuredImage', function() {

					that.initFeaturedImage();
				});
			}
			
		});
	},
	featuredImageSet : function(args) {
	
		var that = this;
		var domain = args.domain, targets = args.targets;
		
		// Set the that.domain when clicking on the Set Featured Image link
		targets.click(function(e) {
				
			/*that*/pop.MediaManagerState.domain = domain;
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	initFeaturedImage : function() {

		var that = this;

		// After the frame is open, check if need to refresh content
		pop.FeaturedImage.getFrame().on('open', function() {
				
			// Only if it had been previously initialized, so that the refresh is not executed the first time it opens
			if (that.initialized.featuredImage && that.needsRefresh.featuredImage) {

				that.refresh(wp.media.frame.state());
			}

			// Set as initialized, no need to refresh anymore
			that.initialized.featuredImage = true;
			that.needsRefresh.featuredImage = false;
		});
	},

	getDomain : function() {

		var that = this;
		return /*that*/pop.MediaManagerState.getDomain();
	},

	refresh : function(state) {

		var that = this;
		state.get('library')._requery(true);
	},

	setUploadSettings : function(settings) {

		var that = this;

		// Do not set the 'post_id' on the upload, or it might fail, specially for the cross-domain
		// We will get error "You don't have permission to attach files to this post."
		if (settings.multipart_params.post_id) {

			delete settings.multipart_params.post_id;
		}
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.MediaManager, ['documentInitializedIndependent']);
pop.JSLibraryManager.register(pop.MediaManager, ['featuredImageSet'], true); // High priority: execute before function 'featuredImageSet' from pop.FeaturedImage, so we set that.domain BEFORE the frame opens
// pop.MediaManager.init();
