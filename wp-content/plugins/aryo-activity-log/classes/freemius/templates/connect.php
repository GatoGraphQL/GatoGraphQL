<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2015, Freemius, Inc.
	 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
	 * @since       1.0.7
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	$slug                  = $VARS['slug'];
	$fs                    = freemius( $slug );
	$is_pending_activation = $fs->is_pending_activation();

	$fs->_enqueue_connect_essentials();

	$current_user = wp_get_current_user();

	$first_name = $current_user->user_firstname;
	if ( empty( $first_name ) ) {
		$first_name = $current_user->nickname;
	}

	$site_url     = get_site_url();
	$protocol_pos = strpos( $site_url, '://' );
	if ( false !== $protocol_pos ) {
		$site_url = substr( $site_url, $protocol_pos + 3 );
	}

	$freemius_site_url = $fs->has_paid_plan() ?
		'https://freemius.com/wordpress/' :
		// Insights platform information.
		'https://freemius.com/wordpress/insights/';
?>
<div id="fs_connect" class="wrap<?php if ( ! $fs->enable_anonymous() || $is_pending_activation ) {
	echo ' fs-anonymous-disabled';
} ?>">
	<div class="fs-visual">
		<b class="fs-site-icon"><i class="dashicons dashicons-wordpress"></i></b>
		<i class="dashicons dashicons-plus fs-first"></i>
		<?php
			$vars = array( 'slug' => $slug );
			fs_require_once_template( 'plugin-icon.php', $vars );
		?>
		<i class="dashicons dashicons-plus fs-second"></i>
		<img class="fs-connect-logo" width="80" height="80" src="//img.freemius.com/connect-logo.png"/>
	</div>
	<div class="fs-content">
		<p><?php
				if ( $is_pending_activation ) {
					echo $fs->apply_filters( 'pending_activation_message', sprintf(
						__fs( 'thanks-x', $slug ) . '<br>' .
						__fs( 'pending-activation-message', $slug ),
						$first_name,
						'<b>' . $fs->get_plugin_name() . '</b>',
						'<b>' . $current_user->user_email . '</b>'
					) );
				} else {
					$filter                = 'connect_message';
					$default_optin_message = 'connect-message';

					if ( $fs->is_plugin_update() ) {
						// If Freemius was added on a plugin update, set different
						// opt-in message.
						$default_optin_message = 'connect-message_on-update';

						// If user customized the opt-in message on update, use
						// that message. Otherwise, fallback to regular opt-in
						// custom message if exist.
						if ( $fs->has_filter( 'connect_message_on_update' ) ) {
							$filter = 'connect_message_on_update';
						}
					}

					echo $fs->apply_filters( $filter,
						sprintf(
							__fs( 'hey-x', $slug ) . '<br>' .
							__fs( $default_optin_message, $slug ),
							$first_name,
							'<b>' . $fs->get_plugin_name() . '</b>',
							'<b>' . $current_user->user_login . '</b>',
							'<a href="' . $site_url . '" target="_blank">' . $site_url . '</a>',
							'<a href="' . $freemius_site_url . '" target="_blank">freemius.com</a>'
						),
						$first_name,
						$fs->get_plugin_name(),
						$current_user->user_login,
						'<a href="' . $site_url . '" target="_blank">' . $site_url . '</a>',
						'<a href="' . $freemius_site_url . '" target="_blank">freemius.com</a>'
					);
				}
			?></p>
	</div>
	<div class="fs-actions">
		<?php if ( $fs->enable_anonymous() && ! $is_pending_activation ) : ?>
			<a href="<?php echo wp_nonce_url( $fs->_get_admin_page_url( '', array( 'fs_action' => $slug . '_skip_activation' ) ), $slug . '_skip_activation' ) ?>"
			   class="button button-secondary" tabindex="2"><?php _efs( 'skip', $slug ) ?></a>
		<?php endif ?>

		<?php $fs_user = Freemius::_get_user_by_email( $current_user->user_email ) ?>
		<?php if ( is_object( $fs_user ) && ! $is_pending_activation ) : ?>
			<form action="" method="POST">
				<input type="hidden" name="fs_action" value="<?php echo $slug ?>_activate_existing">
				<?php wp_nonce_field( 'activate_existing_' . $fs->get_public_key() ) ?>
				<button class="button button-primary" tabindex="1"
				        type="submit"><?php _efs( 'opt-in-connect', $slug ) ?></button>
			</form>
		<?php else : ?>
			<form method="post" action="<?php echo WP_FS__ADDRESS ?>/action/service/user/install/">
				<?php
					$params = array(
						'user_firstname'    => $current_user->user_firstname,
						'user_lastname'     => $current_user->user_lastname,
						'user_nickname'     => $current_user->user_nicename,
						'user_email'        => $current_user->user_email,
						'user_ip'           => WP_FS__REMOTE_ADDR,
						'plugin_slug'       => $slug,
						'plugin_id'         => $fs->get_id(),
						'plugin_public_key' => $fs->get_public_key(),
						'plugin_version'    => $fs->get_plugin_version(),
						'return_url'        => wp_nonce_url( $fs->_get_admin_page_url(
							'',
							array( 'fs_action' => $slug . '_activate_new' )
						), $slug . '_activate_new' ),
						'account_url'       => wp_nonce_url( $fs->_get_admin_page_url(
							'account',
							array( 'fs_action' => 'sync_user' )
						), 'sync_user' ),
						'site_uid'          => $fs->get_anonymous_id(),
						'site_url'          => get_site_url(),
						'site_name'         => get_bloginfo( 'name' ),
						'platform_version'  => get_bloginfo( 'version' ),
						'php_version'       => phpversion(),
						'language'          => get_bloginfo( 'language' ),
						'charset'           => get_bloginfo( 'charset' ),
					);

					if ( WP_FS__SKIP_EMAIL_ACTIVATION && $fs->has_secret_key() ) {
						// Even though rand() is known for its security issues,
						// the timestamp adds another layer of protection.
						// It would be very hard for an attacker to get the secret key form here.
						// Plus, this should never run in production since the secret should never
						// be included in the production version.
						$params['ts']     = WP_FS__SCRIPT_START_TIME;
						$params['salt']   = md5( uniqid( rand() ) );
						$params['secure'] = md5(
							$params['ts'] .
							$params['salt'] .
							$fs->get_secret_key()
						);
					}
				?>
				<?php foreach ( $params as $name => $value ) : ?>
					<input type="hidden" name="<?php echo $name ?>" value="<?php echo esc_attr( $value ) ?>">
				<?php endforeach ?>
				<button class="button button-primary" tabindex="1"
				        type="submit"><?php _efs( $is_pending_activation ? 'resend-activation-email' : 'opt-in-connect', $slug ) ?></button>
			</form>
		<?php endif ?>
	</div><?php

		// Set core permission list items.
		$permissions = array(
			'profile' => array(
				'icon-class' => 'dashicons dashicons-admin-users',
				'label'      => __fs( 'permissions-profile' ),
				'desc'       => __fs( 'permissions-profile_desc' ),
				'priority'   => 5,
			),
			'site'    => array(
				'icon-class' => 'dashicons dashicons-wordpress',
				'label'      => __fs( 'permissions-site' ),
				'desc'       => __fs( 'permissions-site_desc' ),
				'priority'   => 10,
			),
			'events'  => array(
				'icon-class' => 'dashicons dashicons-admin-plugins',
				'label'      => __fs( 'permissions-events' ),
				'desc'       => __fs( 'permissions-events_desc' ),
				'priority'   => 20,
			),
		);

		// Add newsletter permissions if enabled.
		if ( $fs->is_permission_requested( 'newsletter' ) ) {
			$permissions['newsletter'] = array(
				'icon-class' => 'dashicons dashicons-email-alt',
				'label'      => __fs( 'permissions-newsletter' ),
				'desc'       => __fs( 'permissions-newsletter_desc' ),
				'priority'   => 15,
			);
		}

		// Allow filtering of the permissions list.
		$permissions = $fs->apply_filters( 'permission_list', $permissions );

		// Sort by priority.
		uasort( $permissions, 'fs_sort_by_priority' );

		if ( ! empty( $permissions ) ) : ?>
			<div class="fs-permissions">
				<a class="fs-trigger" href="#"><?php _efs( 'what-permissions', $slug ) ?></a>
				<ul><?php
						foreach ( $permissions as $id => $permission ) : ?>
							<li id="fs-permission-<?php esc_attr_e( $id ); ?>"
							    class="fs-permission fs-<?php esc_attr_e( $id ); ?>">
								<i class="<?php esc_attr_e( $permission['icon-class'] ); ?>"></i>

								<div>
									<span><?php esc_html_e( $permission['label'] ); ?></span>

									<p><?php esc_html_e( $permission['desc'] ); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

	<div class="fs-terms">
		<a href="https://freemius.com/privacy/" target="_blank"><?php _efs( 'privacy-policy', $slug ) ?></a>
		&nbsp;&nbsp;-&nbsp;&nbsp;
		<a href="https://freemius.com/terms/" target="_blank"><?php _efs( 'tos', $slug ) ?></a>
	</div>
</div>
<script type="text/javascript">
	(function ($) {
		$('.button').on('click', function () {
			// Set loading mode.
			$(document.body).css({'cursor': 'wait'});
		});
		$('.button.button-primary').on('click', function () {
			$(this).addClass('fs-loading');
			$(this).html('<?php _efs( $is_pending_activation ? 'sending-email' : 'activating' , $slug ) ?>...').css({'cursor': 'wait'});
		});
		$('.fs-permissions .fs-trigger').on('click', function () {
			$('.fs-permissions').toggleClass('fs-open');
		});
	})(jQuery);
</script>