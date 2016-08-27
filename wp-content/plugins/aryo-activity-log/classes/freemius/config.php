<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2015, Freemius, Inc.
	 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
	 * @since       1.0.4
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	define( 'WP_FS__SLUG', 'freemius' );
	if ( ! defined( 'WP_FS__DEV_MODE' ) ) {
		define( 'WP_FS__DEV_MODE', false );
	}

	/**
	 * API Connectivity Simulation
	 */
	define( 'WP_FS__SIMULATE_NO_API_CONNECTIVITY', false && WP_FS__DEV_MODE );
	define( 'WP_FS__SIMULATE_NO_CURL', false && WP_FS__DEV_MODE );
	define( 'WP_FS__SIMULATE_NO_API_CONNECTIVITY_CLOUDFLARE', false && WP_FS__DEV_MODE );
	define( 'WP_FS__SIMULATE_NO_API_CONNECTIVITY_SQUID_ACL', false && WP_FS__DEV_MODE );
	if ( WP_FS__SIMULATE_NO_CURL ) {
		define( 'FS_SDK__SIMULATE_NO_CURL', true );
	}
	if ( WP_FS__SIMULATE_NO_API_CONNECTIVITY_CLOUDFLARE ) {
		define( 'FS_SDK__SIMULATE_NO_API_CONNECTIVITY_CLOUDFLARE', true );
	}
	if ( WP_FS__SIMULATE_NO_API_CONNECTIVITY_SQUID_ACL ) {
		define( 'FS_SDK__SIMULATE_NO_API_CONNECTIVITY_SQUID_ACL', true );
	}

	/**
	 * If your dev environment supports custom public network IP setup
	 * like VVV, please update WP_FS__LOCALHOST_IP with your public IP
	 * and uncomment it during dev.
	 */
	if ( ! defined( 'WP_FS__LOCALHOST_IP' ) ) {
		// VVV default public network IP.
		define( 'WP_FS__VVV_DEFAULT_PUBLIC_IP', '192.168.50.4' );

//		define( 'WP_FS__LOCALHOST_IP', WP_FS__VVV_DEFAULT_PUBLIC_IP );
	}

	/**
	 * If true and running with secret key, the opt-in process
	 * will skip the email activation process which is invoked
	 * when the email of the context user already exist in Freemius
	 * database (as a security precaution, to prevent sharing user
	 * secret with unauthorized entity).
	 *
	 * IMPORTANT:
	 *      AS A SECURITY PRECAUTION, WE VALIDATE THE TIMESTAMP OF THE OPT-IN REQUEST.
	 *      THEREFORE, MAKE SURE THAT WHEN USING THIS PARAMETER,YOUR TESTING ENVIRONMENT'S
	 *      CLOCK IS SYNCED.
	 */
	if ( ! defined( 'WP_FS__SKIP_EMAIL_ACTIVATION' ) ) {
		define( 'WP_FS__SKIP_EMAIL_ACTIVATION', false );
	}

	/**
	 * Directories
	 */
	define( 'WP_FS__DIR', dirname( __FILE__ ) );
	define( 'WP_FS__DIR_INCLUDES', WP_FS__DIR . '/includes' );
	define( 'WP_FS__DIR_TEMPLATES', WP_FS__DIR . '/templates' );
	define( 'WP_FS__DIR_ASSETS', WP_FS__DIR . '/assets' );
	define( 'WP_FS__DIR_CSS', WP_FS__DIR_ASSETS . '/css' );
	define( 'WP_FS__DIR_JS', WP_FS__DIR_ASSETS . '/js' );
	define( 'WP_FS__DIR_IMG', WP_FS__DIR_ASSETS . '/img' );
	define( 'WP_FS__DIR_SDK', WP_FS__DIR_INCLUDES . '/sdk' );


	/**
	 * Domain / URL / Address
	 */
	define( 'WP_FS__TESTING_DOMAIN', 'fswp:8080' );
	define( 'WP_FS__DOMAIN_PRODUCTION', 'wp.freemius.com' );
	define( 'WP_FS__DOMAIN_LOCALHOST', 'wp.freemius' );
	define( 'WP_FS__ADDRESS_LOCALHOST', 'http://' . WP_FS__DOMAIN_LOCALHOST . ':8080' );
	define( 'WP_FS__ADDRESS_PRODUCTION', 'https://' . WP_FS__DOMAIN_PRODUCTION );

	define( 'WP_FS__IS_HTTP_REQUEST', isset( $_SERVER['HTTP_HOST'] ) );
	define( 'WP_FS__REMOTE_ADDR', fs_get_ip() );

	define( 'WP_FS__IS_PRODUCTION_MODE', ! defined( 'WP_FS__DEV_MODE' ) || ! WP_FS__DEV_MODE || ( WP_FS__TESTING_DOMAIN !== $_SERVER['HTTP_HOST'] ) );

	define( 'WP_FS__ADDRESS', ( WP_FS__IS_PRODUCTION_MODE ? WP_FS__ADDRESS_PRODUCTION : WP_FS__ADDRESS_LOCALHOST ) );

	if ( defined( 'WP_FS__LOCALHOST_IP' ) ) {
		define( 'WP_FS__IS_LOCALHOST', ( WP_FS__LOCALHOST_IP === WP_FS__REMOTE_ADDR ) );
	} else {
		define( 'WP_FS__IS_LOCALHOST', WP_FS__IS_HTTP_REQUEST &&
		                               is_string( WP_FS__REMOTE_ADDR ) &&
		                               ( substr( WP_FS__REMOTE_ADDR, 0, 4 ) == '127.' ||
		                                 WP_FS__REMOTE_ADDR == '::1' )
		);
	}

	define( 'WP_FS__IS_LOCALHOST_FOR_SERVER', ( ! WP_FS__IS_HTTP_REQUEST ||
	                                            false !== strpos( $_SERVER['HTTP_HOST'], 'localhost' ) ) );

	// Set API address for local testing.
	if ( ! WP_FS__IS_PRODUCTION_MODE ) {
		define( 'FS_API__ADDRESS', 'http://api.freemius:8080' );
		define( 'FS_API__SANDBOX_ADDRESS', 'http://sandbox-api.freemius:8080' );
	}

	define( 'WP_FS___OPTION_PREFIX', 'fs' . ( WP_FS__IS_PRODUCTION_MODE ? '' : '_dbg' ) . '_' );

	if ( ! defined( 'WP_FS__ACCOUNTS_OPTION_NAME' ) ) {
		define( 'WP_FS__ACCOUNTS_OPTION_NAME', WP_FS___OPTION_PREFIX . 'accounts' );
	}
	if ( ! defined( 'WP_FS__API_CACHE_OPTION_NAME' ) ) {
		define( 'WP_FS__API_CACHE_OPTION_NAME', WP_FS___OPTION_PREFIX . 'api_cache' );
	}
	define( 'WP_FS__OPTIONS_OPTION_NAME', WP_FS___OPTION_PREFIX . 'options' );

	define( 'WP_FS__IS_HTTPS', ( WP_FS__IS_HTTP_REQUEST &&
		                           // Checks if CloudFlare's HTTPS (Flexible SSL support)
		                           isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) ||
	                           // Check if HTTPS request.
	                           ( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] ) ||
	                           ( isset( $_SERVER['SERVER_PORT'] ) && 443 == $_SERVER['SERVER_PORT'] )
	);

	define( 'WP_FS__IS_POST_REQUEST', ( WP_FS__IS_HTTP_REQUEST &&
	                                    strtoupper( $_SERVER['REQUEST_METHOD'] ) == 'POST' ) );

	/**
	 * Billing Frequencies
	 */
	define( 'WP_FS__PERIOD_ANNUALLY', 'annual' );
	define( 'WP_FS__PERIOD_MONTHLY', 'monthly' );
	define( 'WP_FS__PERIOD_LIFETIME', 'lifetime' );

	/**
	 * Plans
	 */
	define( 'WP_FS__PLAN_DEFAULT_PAID', false );
	define( 'WP_FS__PLAN_FREE', 'free' );
	define( 'WP_FS__PLAN_TRIAL', 'trial' );

	/**
	 * Times in seconds
	 */
	define( 'WP_FS__TIME_5_MIN_IN_SEC', 300 );
	define( 'WP_FS__TIME_10_MIN_IN_SEC', 600 );
//	define( 'WP_FS__TIME_15_MIN_IN_SEC', 900 );
	define( 'WP_FS__TIME_24_HOURS_IN_SEC', 86400 );

	/**
	 * Debugging
	 */
	define( 'WP_FS__DEBUG_SDK', WP_FS__DEV_MODE && ! empty( $_GET['fs_dbg'] ) );
	define( 'WP_FS__ECHO_DEBUG_SDK', WP_FS__DEV_MODE && ! empty( $_GET['fs_dbg_echo'] ) );
	define( 'WP_FS__LOG_DATETIME_FORMAT', 'Y-n-d H:i:s' );

	if ( WP_FS__ECHO_DEBUG_SDK ) {
		error_reporting( E_ALL );
		ini_set( 'error_reporting', E_ALL );
		ini_set( 'display_errors', true );
		ini_set( 'html_errors', true );
	}


	define( 'WP_FS__SCRIPT_START_TIME', time() );
	define( 'WP_FS__DEFAULT_PRIORITY', 10 );
	define( 'WP_FS__LOWEST_PRIORITY', 999999999 );
