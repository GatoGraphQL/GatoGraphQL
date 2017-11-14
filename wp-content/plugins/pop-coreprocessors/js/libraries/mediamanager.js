(function($){
window.popMediaManager = {

	//-------------------------------------------------
	// INTERNAL VARIABLES
	//-------------------------------------------------
	domain: null, // all Media Managers can share the current domain, since there can only be one Media Manager open at a time
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

	documentInitialized : function() {

		var that = this;

		// Remove the 'post_id', we don't need it ever
		$(document).on('uploader:start:settings', function(e, settings) {

			that.setUploadSettings(settings);
		});

		// Whenever opening the Media Manager, save under what domain it belongs
		// Code copied from wp-includes/js/media-editor.js
		// The code below is split into 2: 1st part is executed immediately, 2nd part inside $(document).ready()
		// Do NOT change this, otherwise it will not work! First part executes before executing
		// $(document.body).on('click.add-media-button', '.insert-media'... on function wp-includes/js/media-editor.js,
		// And 2nd part will execute after this logic. Then, we first set that.domain with the corresponding domain,
		// BEFORE the frame opens, and then we refresh the content, AFTER it has opened, so that 
		// wp.media.frame.state().get('library') returns the right frame and not another one
		$(document.body).on('click.add-media-button', '.insert-media', function(e) {
				
			var elem = $(e.currentTarget), editor = $('#'+elem.data('editor'));

			// Obtain the domain from the editor's block
			var block = popManager.getBlock(editor);
			that.domain = popManager.getBlockTopLevelDomain(block);
		});

		// Whenever the user logs out, set the needsRefresh as true, so that next user logging in will have the media manager refreshed
		$(document).on('user:loggedout', function(e/*, source, domain*/) {
			
			// Refresh everything, without paying attention to what domain the user is logging out from
			// It takes away some complexity
			that.needsRefresh/*[domain]*/ = {
				featuredImage: true,
				'editor-gallery': true,
				'editor-insert': true
			};
		});

		$(document).ready( function($) {

			// Notice that I have also added element '.media-menu-item', so that when clicking on the side navigation on the left,
			// switching between Insert Media and Gallery, it also refreshes the content if needed
			$(document.body).on('click.add-media-button', '.insert-media,.media-menu-item', function(e) {

				// Trigger a refresh of the data after the user logs in/out
				// States could be either 'gallery' or 'insert'
				var state = wp.media.frame.state();
				var states = ['gallery', 'insert'];
				if (states.indexOf(state.id) > -1) {

					// By this time, we should get the current domain set
					// var domain = that.getDomain();
					
					// Initialize vars
					// that.initDomain(domain);

					// Only if it had been previously initialized, so that the refresh is not executed the first time it opens
					if (that.initialized/*[domain]*/['editor-'+state.id] && that.needsRefresh/*[domain]*/['editor-'+state.id]) {

						that.refresh(state);
					}

					// Set as initialized, no need to refresh anymore
					that.initialized/*[domain]*/['editor-'+state.id] = true;
					that.needsRefresh/*[domain]*/['editor-'+state.id] = false;
				}
			});

			// Inside $(document).ready( because popFeaturedImage.documentInitialized will execute after, so getFrame() is not ready yet
			// After the frame is open, check if need to refresh content
			popFeaturedImage.getFrame().on('open', function() {

				// // By this time, we should get the current domain set
				// var domain = that.getDomain();

				// // Initialize vars
				// that.initDomain(domain);
				
				// Only if it had been previously initialized, so that the refresh is not executed the first time it opens
				if (that.initialized/*[domain]*/.featuredImage && that.needsRefresh/*[domain]*/.featuredImage) {

					that.refresh(wp.media.frame.state());
				}

				// Set as initialized, no need to refresh anymore
				that.initialized/*[domain]*/.featuredImage = true;
				that.needsRefresh/*[domain]*/.featuredImage = false;
			});
		});
	},
	featuredImageSet : function(args) {
	
		var that = this;
		var domain = args.domain, targets = args.targets;
		
		// Set the that.domain when clicking on the Set Featured Image link
		targets.click(function(e) {
				
			that.domain = domain;
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	// initDomain : function(domain) {

	// 	var that = this;
					
	// 	// Initialize vars
	// 	that.initialized[domain] = that.initialized[domain] || {};
	// 	that.needsRefresh[domain] = that.needsRefresh[domain] || {};
	// },

	getDomain : function() {

		var that = this;
		return that.domain;
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
popJSLibraryManager.register(popMediaManager, ['documentInitialized']);
popJSLibraryManager.register(popMediaManager, ['featuredImageSet'], true); // High priority: execute before function 'featuredImageSet' from popFeaturedImage, so we set that.domain BEFORE the frame opens
