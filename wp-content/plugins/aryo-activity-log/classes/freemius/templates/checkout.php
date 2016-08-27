<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2015, Freemius, Inc.
	 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
	 * @since       1.0.3
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'json2' );
	fs_enqueue_local_script( 'postmessage', 'nojquery.ba-postmessage.min.js' );
	fs_enqueue_local_script( 'fs-postmessage', 'postmessage.js' );
	fs_enqueue_local_style( 'fs_common', '/admin/common.css' );

	$slug = $VARS['slug'];
	$fs   = freemius( $slug );

	$timestamp = time();

	$context_params = array(
		'plugin_id'         => $fs->get_id(),
		'plugin_public_key' => $fs->get_public_key(),
		'plugin_version'    => $fs->get_plugin_version(),
	);

	// Get site context secure params.
	if ( $fs->is_registered() ) {
		$context_params = array_merge( $context_params, FS_Security::instance()->get_context_params(
			$fs->get_site(),
			$timestamp,
			'checkout'
		) );
	} else {
		$current_user = wp_get_current_user();

		// Add site and user info to the request, this information
		// is NOT being stored unless the user complete the purchase
		// and agrees to the TOS.
		$context_params = array_merge( $context_params, array(
			'user_firstname' => $current_user->user_firstname,
			'user_lastname'  => $current_user->user_lastname,
			'user_email'     => $current_user->user_email,
//			'user_nickname'    => $current_user->user_nicename,
//			'plugin_slug'      => $slug,
//			'site_url'         => get_site_url(),
//			'site_name'        => get_bloginfo( 'name' ),
//			'platform_version' => get_bloginfo( 'version' ),
//			'language'         => get_bloginfo( 'language' ),
//			'charset'          => get_bloginfo( 'charset' ),
//			'account_url'      => fs_nonce_url( $fs->_get_admin_page_url(
//				'account',
//				array( 'fs_action' => 'sync_user' )
//			), 'sync_user' ),
		) );

		$fs_user = Freemius::_get_user_by_email( $current_user->user_email );

		if ( is_object( $fs_user ) ) {
			$context_params = array_merge( $context_params, FS_Security::instance()->get_context_params(
				$fs_user,
				$timestamp,
				'checkout'
			) );
		}
	}

	if ( $fs->is_payments_sandbox() ) // Append plugin secure token for sandbox mode authentication.)
	{
		$context_params['sandbox'] = FS_Security::instance()->get_secure_token(
			$fs->get_plugin(),
			$timestamp,
			'checkout'
		);
	}

	$return_url = fs_nonce_url( $fs->_get_admin_page_url(
		'account',
		array(
			'fs_action' => $slug . '_sync_license',
			'plugin_id' => isset( $_GET['plugin_id'] ) ? $_GET['plugin_id'] : $fs->get_id()
		)
	), $slug . '_sync_license' );

	$query_params = array_merge( $context_params, $_GET, array(
		// Current plugin version.
		'plugin_version' => $fs->get_plugin_version(),
		'return_url'     => $return_url,
		// Admin CSS URL for style/design competability.
//		'wp_admin_css'   => get_bloginfo('wpurl') . "/wp-admin/load-styles.php?c=1&load=buttons,wp-admin,dashicons",
	) );
?>
	<div class="fs-secure-notice">
		<i class="dashicons dashicons-lock"></i>
		<span><b>Secure HTTPS Checkout</b> - PCI compliant, running via iframe from external domain</span>
	</div>
	<div id="fs_contact" class="wrap" style="margin: 40px 0 -65px -20px;">
		<div id="iframe"></div>
		<script type="text/javascript">
			// http://stackoverflow.com/questions/4583703/jquery-post-request-not-ajax
			jQuery(function ($) {
				$.extend({
					form: function (url, data, method) {
						if (method == null) method = 'POST';
						if (data == null) data = {};

						var form = $('<form>').attr({
							method: method,
							action: url
						}).css({
							display: 'none'
						});

						var addData = function (name, data) {
							if ($.isArray(data)) {
								for (var i = 0; i < data.length; i++) {
									var value = data[i];
									addData(name + '[]', value);
								}
							} else if (typeof data === 'object') {
								for (var key in data) {
									if (data.hasOwnProperty(key)) {
										addData(name + '[' + key + ']', data[key]);
									}
								}
							} else if (data != null) {
								form.append($('<input>').attr({
									type : 'hidden',
									name : String(name),
									value: String(data)
								}));
							}
						};

						for (var key in data) {
							if (data.hasOwnProperty(key)) {
								addData(key, data[key]);
							}
						}

						return form.appendTo('body');
					}
				});
			});

			(function ($) {
				$(function () {

					var
					// Keep track of the iframe height.
					iframe_height = 800,
					base_url = '<?php echo WP_FS__ADDRESS ?>',
					// Pass the parent page URL into the Iframe in a meaningful way (this URL could be
					// passed via query string or hard coded into the child page, it depends on your needs).
					src = base_url + '/checkout/?<?php echo (isset($_REQUEST['XDEBUG_SESSION']) ? 'XDEBUG_SESSION=' . $_REQUEST['XDEBUG_SESSION'] . '&' : '') . http_build_query($query_params) ?>#' + encodeURIComponent(document.location.href),

					// Append the Iframe into the DOM.
					iframe = $('<iframe " src="' + src + '" width="100%" height="' + iframe_height + 'px" scrolling="no" frameborder="0" style="background: transparent;"><\/iframe>')
						.appendTo('#iframe');

					FS.PostMessage.init(base_url);
					FS.PostMessage.receiveOnce('height', function (data) {
						var h = data.height;
						if (!isNaN(h) && h > 0 && h != iframe_height) {
							iframe_height = h;
							$("#iframe iframe").height(iframe_height + 'px');
						}
					});

					FS.PostMessage.receiveOnce('install', function (data) {
						// Post data to activation URL.
						$.form('<?php echo fs_nonce_url($fs->_get_admin_page_url('account', array(
							'fs_action' => $slug . '_activate_new',
							'plugin_id' => isset($_GET['plugin_id']) ? $_GET['plugin_id'] : $fs->get_id()
							)), $slug . '_activate_new') ?>', {
							user_id           : data.user.id,
							user_secret_key   : data.user.secret_key,
							user_public_key   : data.user.public_key,
							install_id        : data.install.id,
							install_secret_key: data.install.secret_key,
							install_public_key: data.install.public_key
						}).submit();
					});

					FS.PostMessage.receiveOnce('pending_activation', function (data) {
						$.form('<?php echo fs_nonce_url($fs->_get_admin_page_url('account', array(
							'fs_action' => $slug . '_activate_new',
							'plugin_id' => fs_request_get('plugin_id', $fs->get_id()),
							'pending_activation' => true,
							)), $slug . '_activate_new') ?>', {
							user_email: data.user_email
						}).submit();
					});

					FS.PostMessage.receiveOnce('get_context', function () {
						console.debug('receiveOnce', 'get_context');

						// If the user didn't connect his account with Freemius,
						// once he accepts the Terms of Service and Privacy Policy,
						// and then click the purchase button, the context information
						// of the user will be shared with Freemius in order to complete the
						// purchase workflow and activate the license for the right user.
						<?php $current_user = wp_get_current_user() ?>
						FS.PostMessage.post('context', {
//						user_firstname: '<?php //echo $current_user->user_firstname ?>//',
//						user_lastname: '<?php //echo $current_user->user_lastname ?>//',
//						user_email: '<?php //echo $current_user->user_email ?>//'
							plugin_id        : '<?php echo $fs->get_id() ?>',
							plugin_public_key: '<?php echo $fs->get_public_key() ?>',
							plugin_version   : '<?php echo $fs->get_plugin_version() ?>',
							plugin_slug      : '<?php echo $slug ?>',
							site_name        : '<?php echo get_bloginfo('name') ?>',
							platform_version : '<?php echo get_bloginfo('version') ?>',
							language         : '<?php echo get_bloginfo('language') ?>',
							charset          : '<?php echo get_bloginfo('charset') ?>',
							return_url       : '<?php echo $return_url ?>',
							account_url      : '<?php echo fs_nonce_url($fs->_get_admin_page_url(
									'account',
									array('fs_action' => 'sync_user')
						), 'sync_user') ?>',
							activation_url   : '<?php echo fs_nonce_url($fs->_get_admin_page_url('',
							array(
								'fs_action' => $slug . '_activate_new',
								'plugin_id' => fs_request_get('plugin_id', $fs->get_id()),

								)),
							$slug . '_activate_new') ?>'
						}, iframe[0]);
					});

					FS.PostMessage.receiveOnce('get_dimensions', function (data) {
						console.debug('receiveOnce', 'get_dimensions');

						FS.PostMessage.post('dimensions', {
							height   : $(document.body).height(),
							scrollTop: $(document).scrollTop()
						}, iframe[0]);
					});
				});
			})(jQuery);
		</script>
	</div>
<?php fs_require_template( 'powered-by.php' ) ?>