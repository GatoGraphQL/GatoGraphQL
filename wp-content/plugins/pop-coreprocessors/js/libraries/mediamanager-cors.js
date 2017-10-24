(function($){
popMediaManagerCORS = {

	//-------------------------------------------------
	// INTERNAL VARIABLES
	//-------------------------------------------------
	domains: {
		'editor-gallery': null,
		'editor-insert': null,
		featuredImage: null
	},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function() {

		var t = this;

		// Whenever executing wp.ajax.send, in file wp-includes/js/wp-util.js, we may change the url
		// depending on the domain corresponding to the open media manager
		$(document).on('wp.ajax:send:options', function(e, options) {

			t.setEditorOptions(options);
		});

		// Similarly, whenever executing uploader:start:settings, in file wp-includes/js/plupload/wp-plupload.js, we may change the url
		// depending on the domain corresponding to the open media manager
		$(document).on('uploader:start:settings', function(e, settings) {

			t.setUploadSettings(settings);
		});

		$(document).ready( function($) {

			// Notice that I have also added element '.media-menu-item', so that when clicking on the side navigation on the left,
			// switching between Insert Media and Gallery, it also refreshes the content if needed
			$(document.body).on('click.add-media-button', '.insert-media,.media-menu-item', function(e) {

				// By this time, we should get the current domain set
				var domain = popMediaManager.getDomain();
				
				// If the current and previous domains are different, then trigger a refresh of the data
				// States could be either 'gallery' or 'insert'
				var state = wp.media.frame.state();
				var states = ['gallery', 'insert'];
				if (states.indexOf(state.id) > -1) {
					
					if (domain && t.domains['editor-'+state.id] && domain != t.domains['editor-'+state.id]) {

						// Instruct the Media Manager that it needs to refresh
						// state.get('library')._requery(true);// Initialize vars
						// popMediaManager.initDomain(domain);
						popMediaManager.needsRefresh/*[domain]*/['editor-'+state.id] = true;
					}

					// Set the editor's domain to be the current domain
					t.domains['editor-'+state.id] = domain;
				}
			});
		});

		$(document).on('initialized.featuredImage', function(e) {

			// Inside $(document).ready( because popFeaturedImage.documentInitialized will execute after, so getFrame() is not ready yet
			// After the frame is open, check if need to refresh content
			popFeaturedImage.getFrame().on('open', function() {

				// By this time, we should get the current domain set
				var domain = popMediaManager.getDomain();
				
				// If the current and previous domains are different, then trigger a refresh of the data
				if (domain && t.domains.featuredImage && domain != t.domains.featuredImage) {

					// Instruct the Media Manager that it needs to refresh
					// wp.media.frame.state().get('library')._requery(true);
					// popMediaManager.initDomain(domain);
					popMediaManager.needsRefresh/*[domain]*/.featuredImage = true;
				}

				// Set the featuredImage's domain to be the current domain
				t.domains.featuredImage = domain;
			});
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	setEditorOptions : function(options) {

		var t = this;

		var domain = popMediaManager.getDomain();
		if (domain) {

			// Only change if the current domain is not the local domain (options.url will always be originally pointing to `/wp-admin/admin-ajax.php`, so by default it always works for local domain)
			// IMPORTANT: Do this always, no need to ask for the actions below. That is because action 'delete-post' also needs to send its cookies,
			// so it also needs .withCredentials=true, even though we don't need to modify the nonce
			// So then, as longs as it's an external domain, add the cross-domain support!
			if (domain != M.HOME_DOMAIN) {

				options.url = domain + M.AJAXURL;//'/wp-admin/admin-ajax.php';
				options.xhrFields = {
					withCredentials: true
				};
				options.crossDomain = true;
			}

			if (options.data && options.data.action) {

				// Only proceed for the following actions, and already indicate their corresponding nonces
				var action_nonces = {
					'query-attachments': 'media-form', 
					// Comment Leo 06/07/2017: nonces below commented because their corresponding nonce is already set when bringing the attachments, so these are fine
					// 'get-attachment': 'media-form', 
					// 'save-attachment': 'media-form', 
					// 'delete-post': 'media-form', 
					'save-attachment-compat': 'media-form', 
					'save-attachment-order': 'media-form', 
					'send-attachment-to-editor': 'media-send-to-editor',
					'send-link-to-editor': 'media-send-to-editor',
				};
				var nonce_key = action_nonces[options.data.action];
				if (nonce_key) {

					// Replace the 'media-form', 'send-to-editor', etc nonces
					// Change the nonce for both local and external domains:
					// 1. For local domain, change it anyway, so that we are using a fresh version from the server, overriding the potentially stale one saved in the Service Workers' cached app-shell
					// 2. For external domain, set their nonce, or the nonce-validation will fail and the operation will fail
					var nonce = popManager.getTopLevelFeedback(domain)[M.URLPARAM_NONCES][nonce_key];
					// The nonce may be placed in different places:
					// - Under param "_wpnonce"
					if (options.data._wpnonce) {
						options.data._wpnonce = nonce;
					}
					// - Under param "nonce" (eg: 'send-attachment-to-editor')
					if (options.data.nonce) {
						options.data.nonce = nonce;
					}
					// -Under param "query._wpnonce" (eg: 'media-form')
					if (options.data.query && options.data.query._wpnonce) {
						options.data.query._wpnonce = nonce;
					}
					// -Under param "query.nonce"
					if (options.data.query && options.data.query.nonce) {
						options.data.query.nonce = nonce;
					}
				}
			}
		}
	},

	setUploadSettings : function(settings) {

		var t = this;

		// Set it always, because the settings are kept in the state of the Uploader,
		// so we make sure it will always have the right domain, and not the previous one
		var domain = popMediaManager.getDomain();
		if (domain) {

			settings.url = domain + M.UPLOADURL;//'/wp-admin/async-upload.php';

			// There is only 1 action currently: 'upload-attachment'. However keep support for more actions in the future
			var nonce_keys = {
				'upload-attachment': 'media-form'
			}
			var action = settings.multipart_params.action;
			var nonce_key = nonce_keys[action];
			if (nonce_key) {

				// Also change the nonce, use the one for this domain
				settings.multipart_params._wpnonce = popManager.getTopLevelFeedback(domain)[M.URLPARAM_NONCES][nonce_key];
			}
		}
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popMediaManagerCORS, ['documentInitialized'], true); // High priority: execute before function 'documentInitialized' from popMediaManager, so we set t.needsRefresh before the media manager checks for this value and performs the refresh
