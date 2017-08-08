(function($){
popMediaManager = {

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

		var t = this;

		// Remove the 'post_id', we don't need it ever
		$(document).on('uploader:start:settings', function(e, settings) {

			t.setUploadSettings(settings);
		});

		// Whenever opening the Media Manager, save under what domain it belongs
		// Code copied from wp-includes/js/media-editor.js
		// The code below is split into 2: 1st part is executed immediately, 2nd part inside $(document).ready()
		// Do NOT change this, otherwise it will not work! First part executes before executing
		// $(document.body).on('click.add-media-button', '.insert-media'... on function wp-includes/js/media-editor.js,
		// And 2nd part will execute after this logic. Then, we first set t.domain with the corresponding domain,
		// BEFORE the frame opens, and then we refresh the content, AFTER it has opened, so that 
		// wp.media.frame.state().get('library') returns the right frame and not another one
		$(document.body).on('click.add-media-button', '.insert-media', function(e) {
				
			var elem = $(e.currentTarget), editor = $('#'+elem.data('editor'));

			// Obtain the domain from the editor's block
			var block = popManager.getBlock(editor);
			t.domain = popManager.getBlockTopLevelDomain(block);
		});

		// Whenever the user logs out, set the needsRefresh as true, so that next user logging in will have the media manager refreshed
		$(document).on('user:loggedout', function(e/*, source, domain*/) {
			
			// Refresh everything, without paying attention to what domain the user is logging out from
			// It takes away some complexity
			t.needsRefresh/*[domain]*/ = {
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
					// var domain = t.getDomain();
					
					// Initialize vars
					// t.initDomain(domain);

					// Only if it had been previously initialized, so that the refresh is not executed the first time it opens
					if (t.initialized/*[domain]*/['editor-'+state.id] && t.needsRefresh/*[domain]*/['editor-'+state.id]) {

						t.refresh(state);
					}

					// Set as initialized, no need to refresh anymore
					t.initialized/*[domain]*/['editor-'+state.id] = true;
					t.needsRefresh/*[domain]*/['editor-'+state.id] = false;
				}
			});

			// Inside $(document).ready( because popFeaturedImage.documentInitialized will execute after, so getFrame() is not ready yet
			// After the frame is open, check if need to refresh content
			popFeaturedImage.getFrame().on('open', function() {

				// // By this time, we should get the current domain set
				// var domain = t.getDomain();

				// // Initialize vars
				// t.initDomain(domain);
				
				// Only if it had been previously initialized, so that the refresh is not executed the first time it opens
				if (t.initialized/*[domain]*/.featuredImage && t.needsRefresh/*[domain]*/.featuredImage) {

					t.refresh(wp.media.frame.state());
				}

				// Set as initialized, no need to refresh anymore
				t.initialized/*[domain]*/.featuredImage = true;
				t.needsRefresh/*[domain]*/.featuredImage = false;
			});
		});
	},
	featuredImageSet : function(args) {
	
		var t = this;
		var domain = args.domain, targets = args.targets;
		
		// Set the t.domain when clicking on the Set Featured Image link
		targets.click(function(e) {
				
			t.domain = domain;
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	// initDomain : function(domain) {

	// 	var t = this;
					
	// 	// Initialize vars
	// 	t.initialized[domain] = t.initialized[domain] || {};
	// 	t.needsRefresh[domain] = t.needsRefresh[domain] || {};
	// },

	getDomain : function() {

		var t = this;
		return t.domain;
	},

	refresh : function(state) {

		var t = this;
		state.get('library')._requery(true);
	},

	setUploadSettings : function(settings) {

		var t = this;

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
popJSLibraryManager.register(popMediaManager, ['featuredImageSet'], true); // High priority: execute before function 'featuredImageSet' from popFeaturedImage, so we set t.domain BEFORE the frame opens
