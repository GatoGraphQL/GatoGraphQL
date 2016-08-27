<?php
/*
Plugin Name: Activity Log
Plugin URI: http://wordpress.org/plugins/aryo-activity-log/
Description: Get aware of any activities that are taking place on your dashboard! Imagine it like a black-box for your WordPress site. e.g. post was deleted, plugin was activated, user logged in or logged out - it's all these for you to see.
Author: Yakir Sitbon, Maor Chasen, Ariel Klikstein
Author URI: http://pojo.me/
Version: 2.2.11
Text Domain: aryo-activity-log
Domain Path: /language/
License: GPLv2 or later


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ACTIVITY_LOG__FILE__', __FILE__ );
define( 'ACTIVITY_LOG_BASE', plugin_basename( ACTIVITY_LOG__FILE__ ) );

include( 'classes/class-aal-maintenance.php' );
include( 'classes/class-aal-activity-log-list-table.php' );
include( 'classes/class-aal-admin-ui.php' );
include( 'classes/class-aal-settings.php' );
include( 'classes/class-aal-api.php' );
include( 'classes/class-aal-hooks.php' );
include( 'classes/class-aal-notifications.php' );
include( 'classes/class-aal-help.php' );

// Integrations
include( 'classes/class-aal-integration-woocommerce.php' );

// Probably we should put this in a separate file
final class AAL_Main {

	/**
	 * @var AAL_Main The one true AAL_Main
	 * @since 2.0.5
	 */
	private static $_instance = null;

	/**
	 * @var AAL_Admin_Ui
	 * @since 1.0.0
	 */
	public $ui;

	/**
	 * @var AAL_Hooks
	 * @since 1.0.1
	 */
	public $hooks;

	/**
	 * @var AAL_Settings
	 * @since 1.0.0
	 */
	public $settings;

	/**
	 * @var AAL_API
	 * @since 2.0.5
	 */
	public $api;

	/**
	 * @var Freemius
	 */
	public $freemius;

	/**
	 * Load text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'aryo-activity-log', false, basename( dirname( __FILE__ ) ) . '/language' );
	}

	/**
	 * Construct
	 */
	protected function __construct() {
		global $wpdb;
		
		//$this->_setup_freemius();
		
		// Hack GreenDrinks: initialize everything using filters, and after the plugins have loaded, to allow these to be overriden
		// Set the priority long enough to allow other plugins to add their filter before
		add_action ('plugins_loaded', array($this, 'pop_init'), 1000);
		// $this->ui            = new AAL_Admin_Ui();
		// $this->hooks         = new AAL_Hooks();
		// $this->settings      = new AAL_Settings();
		// $this->api           = new AAL_API();
		// $this->notifications = new AAL_Notifications();
		// $this->help          = new AAL_Help();

		// set up our DB name
		$wpdb->activity_log = $wpdb->prefix . 'aryo_activity_log';
		
		add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );
	}

	// Hack GreenDrinks: allow to override the classes below. Needed to override the Hooks implementations
	function pop_init() {

		$ui_class = apply_filters('PoP:AAL_Main:ui:classname', 'AAL_Admin_Ui');
		$hooks_class = apply_filters('PoP:AAL_Main:hooks:classname', 'AAL_Hooks');
		$settings_class = apply_filters('PoP:AAL_Main:settings:classname', 'AAL_Settings');
		$api_class = apply_filters('PoP:AAL_Main:api:classname', 'AAL_API');
		$notifications_class = apply_filters('PoP:AAL_Main:notifications:classname', 'AAL_Notifications');
		$help_class = apply_filters('PoP:AAL_Main:help:classname', 'AAL_Help');

		$this->ui            = new $ui_class();
		$this->hooks         = new $hooks_class();
		$this->settings      = new $settings_class();
		$this->api           = new $api_class();
		$this->notifications = new $notifications_class();
		$this->help          = new $help_class();
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 2.0.7
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'aryo-activity-log' ), '2.0.7' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 2.0.7
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'aryo-activity-log' ), '2.0.7' );
	}

	/**
	 * @return AAL_Main
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new AAL_Main();
		return self::$_instance;
	}

	private function _setup_freemius() {
		// Include Freemius SDK.
		require_once  'classes/freemius/start.php';
		
		$this->freemius = fs_dynamic_init(
			array(
				'id' => '111',
				'slug' => 'aryo-activity-log',
				'public_key' => 'pk_939ce05ca99db10045c0094c6e953',
				'is_premium' => false,
				'has_paid_plans' => false,
				'menu' => array(
					'slug' => 'activity_log_page',
					'account' => false,
					'contact' => false,
					'support' => false,
				),
			)
		);
		
		if ( $this->freemius->is_plugin_update() ) {
			$this->freemius->add_filter( 'connect_message', array( &$this, '_freemius_custom_connect_message' ), WP_FS__DEFAULT_PRIORITY, 6 );
		}
	}

	public function _freemius_custom_connect_message( $message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link ) {
		return sprintf(
			__(
				'<b>Please help us improve %1$s!</b><br>
		     	If you opt-in, some data about your usage of <b>%1$s</b> will be sent to %2$s.
		     	If you skip this, that\'s okay! <b>%1$s</b> will still work just fine.',
				'aryo-activity-log'
			),
			$this->freemius->get_plugin_name(),
			$freemius_link
		);
	}

}
AAL_Main::instance();