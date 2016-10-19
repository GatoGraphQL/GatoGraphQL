( function( $ ) {
	DSPublicPostPreview = {

		/**
		 * Initializes the plugin.
		 *
		 * @since 2.0.0
		 */
		initialize : function() {
			var t = this;

			t.checkbox = $( '#public-post-preview' );
			t.link     = $( '#public-post-preview-link' );
			t.nonce    = $( '#public_post_preview_wpnonce' );
			t.status   = $( '#public-post-preview-ajax' );

			t.status.css( 'opacity', 0 );

			t.checkbox.bind( 'change', function() {
				t.change();
			} );

			t.link.find( 'input' ).on( 'focus', function() {
				$( this ).select();
			} );
		},

		/**
		 * Handles a checkbox change.
		 *
		 * @since 2.0.0
		 */
		change : function() {
			var t = this,
				checked = t.checkbox.prop( 'checked' ) ? 1 : 0;

			// Toggle visibility of the link
			t.link.toggle();

			// Disable the checkbox, to prevent double AJAX requests
			t.checkbox.prop( 'disabled', 'disabled' );

			t.request(
				{
					_ajax_nonce : t.nonce.val(),
					checked : checked,
					post_ID : $( '#post_ID' ).val()
				},
				function( data ) {
					// data is '1' if it's a successful request
					if ( data ) {
						if ( checked ) {
							t.status.text( DSPublicPostPreviewL10n.enabled );
							t._pulsate( t.status, 'green' );
						} else {
							t.status.text( DSPublicPostPreviewL10n.disabled );
							t._pulsate( t.status, 'red' );
						}
					}

					// Enable the checkbox again
					t.checkbox.prop( 'disabled', '' );
				}
			);
		},

		/**
		 * Does the AJAX request.
		 *
		 * @since  2.0.0
		 *
		 * @param  {Object}  data     The data to send.
		 * @param  {Object}  callback Callback function for a successful request.
		 */
		request : function( data, callback ) {
			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: $.extend(
					data,
					{
						action: 'public-post-preview'
					}
				),
				success : callback
			} );
		},

		/**
		 * Helper for a pulse effect.
		 *
		 * @since  2.0.0
		 *
		 * @param  {Object} e     The element.
		 * @param  {String} color The text color of the element.
		 */
		_pulsate : function( e, color ) {
			e.css( 'color', color )
				.animate( { opacity: 1 }, 600, 'linear' )
				.animate( { opacity: 0 }, 600, 'linear', function() {
					e.empty();
				} );
		}
	};

	// Document is ready.
	$( DSPublicPostPreview.initialize() );

} )( jQuery );
