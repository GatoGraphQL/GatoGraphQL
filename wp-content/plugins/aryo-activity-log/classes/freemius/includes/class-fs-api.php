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

	/**
	 * Class FS_Api
	 *
	 * Wraps Freemius API SDK to handle:
	 *      1. Clock sync.
	 *      2. Fallback to HTTP when HTTPS fails.
	 *      3. Adds caching layer to GET requests.
	 *      4. Adds consistency for failed requests by using last cached version.
	 */
	class FS_Api {
		/**
		 * @var FS_Api[]
		 */
		private static $_instances = array();

		/**
		 * @var FS_Option_Manager Freemius options, options-manager.
		 */
		private static $_options;

		/**
		 * @var FS_Cache_Manager API Caching layer
		 */
		private static $_cache;

		/**
		 * @var int Clock diff in seconds between current server to API server.
		 */
		private static $_clock_diff;

		/**
		 * @var Freemius_Api
		 */
		private $_api;

		/**
		 * @var string
		 */
		private $_slug;

		/**
		 * @var FS_Logger
		 * @since 1.0.4
		 */
		private $_logger;

		/**
		 * @param string      $slug
		 * @param string      $scope      'app', 'developer', 'user' or 'install'.
		 * @param number      $id         Element's id.
		 * @param string      $public_key Public key.
		 * @param bool        $is_sandbox
		 * @param bool|string $secret_key Element's secret key.
		 *
		 * @return FS_Api
		 */
		static function instance( $slug, $scope, $id, $public_key, $is_sandbox, $secret_key = false ) {
			$identifier = md5( $slug . $scope . $id . $public_key . ( is_string( $secret_key ) ? $secret_key : '' ) . json_encode( $is_sandbox ) );

			if ( ! isset( self::$_instances[ $identifier ] ) ) {
				self::_init();

				self::$_instances[ $identifier ] = new FS_Api( $slug, $scope, $id, $public_key, $secret_key, $is_sandbox );
			}

			return self::$_instances[ $identifier ];
		}

		private static function _init() {
			if ( isset( self::$_options ) ) {
				return;
			}

			if ( ! class_exists( 'Freemius_Api' ) ) {
				require_once( WP_FS__DIR_SDK . '/Freemius.php' );
			}

			self::$_options = FS_Option_Manager::get_manager( WP_FS__OPTIONS_OPTION_NAME, true );
			self::$_cache   = FS_Cache_Manager::get_manager( WP_FS__API_CACHE_OPTION_NAME );

			self::$_clock_diff = self::$_options->get_option( 'api_clock_diff', 0 );
			Freemius_Api::SetClockDiff( self::$_clock_diff );

			if ( self::$_options->get_option( 'api_force_http', false ) ) {
				Freemius_Api::SetHttp();
			}
		}

		/**
		 * @param string      $slug
		 * @param string      $scope      'app', 'developer', 'user' or 'install'.
		 * @param number      $id         Element's id.
		 * @param string      $public_key Public key.
		 * @param bool|string $secret_key Element's secret key.
		 * @param bool        $is_sandbox
		 */
		private function __construct( $slug, $scope, $id, $public_key, $secret_key, $is_sandbox ) {
			$this->_api = new Freemius_Api( $scope, $id, $public_key, $secret_key, $is_sandbox );

			$this->_slug   = $slug;
			$this->_logger = FS_Logger::get_logger( WP_FS__SLUG . '_' . $slug . '_api', WP_FS__DEBUG_SDK, WP_FS__ECHO_DEBUG_SDK );
		}

		/**
		 * Find clock diff between server and API server, and store the diff locally.
		 *
		 * @param bool|int $diff
		 *
		 * @return bool|int False if clock diff didn't change, otherwise returns the clock diff in seconds.
		 */
		private function _sync_clock_diff( $diff = false ) {
			$this->_logger->entrance();

			// Sync clock and store.
			$new_clock_diff = ( false === $diff ) ?
				Freemius_Api::FindClockDiff() :
				$diff;

			if ( $new_clock_diff === self::$_clock_diff ) {
				return false;
			}

			self::$_clock_diff = $new_clock_diff;

			// Update API clock's diff.
			Freemius_Api::SetClockDiff( self::$_clock_diff );

			// Store new clock diff in storage.
			self::$_options->set_option( 'api_clock_diff', self::$_clock_diff, true );

			return $new_clock_diff;
		}

		/**
		 * Override API call to enable retry with servers' clock auto sync method.
		 *
		 * @param string $path
		 * @param string $method
		 * @param array  $params
		 * @param bool   $retry Is in retry or first call attempt.
		 *
		 * @return array|mixed|string|void
		 */
		private function _call( $path, $method = 'GET', $params = array(), $retry = false ) {
			$this->_logger->entrance();

			if ( self::is_temporary_down() ) {
				$result = $this->get_temporary_unavailable_error();
			} else {
				$result = $this->_api->Api( $path, $method, $params );

				if ( null !== $result &&
				     isset( $result->error ) &&
				     isset( $result->error->code ) &&
				     'request_expired' === $result->error->code
				) {
					if ( ! $retry ) {
						$diff = isset( $result->error->timestamp ) ?
							( time() - strtotime( $result->error->timestamp ) ) :
							false;

						// Try to sync clock diff.
						if ( false !== $this->_sync_clock_diff( $diff ) ) {
							// Retry call with new synced clock.
							return $this->_call( $path, $method, $params, true );
						}
					}
				}
			}

			if ( null !== $result && isset( $result->error ) && isset( $result->error->message ) ) {
				// Log API errors.
				$this->_logger->error( $result->error->message );
			}

			return $result;
		}

		/**
		 * Override API call to wrap it in servers' clock sync method.
		 *
		 * @param string $path
		 * @param string $method
		 * @param array  $params
		 *
		 * @return array|mixed|string|void
		 * @throws Freemius_Exception
		 */
		function call( $path, $method = 'GET', $params = array() ) {
			return $this->_call( $path, $method, $params );
		}

		/**
		 * Get API request URL signed via query string.
		 *
		 * @param string $path
		 *
		 * @return string
		 */
		function get_signed_url( $path ) {
			return $this->_api->GetSignedUrl( $path );
		}

		/**
		 * @param string $path
		 * @param bool   $flush
		 * @param int    $expiration (optional) Time until expiration in seconds from now, defaults to 24 hours
		 *
		 * @return stdClass|mixed
		 */
		function get( $path = '/', $flush = false, $expiration = WP_FS__TIME_24_HOURS_IN_SEC ) {
			$this->_logger->entrance();

			$cache_key = $this->get_cache_key( $path );

			// Always flush during development.
			if ( WP_FS__DEV_MODE || $this->_api->IsSandbox() ) {
				$flush = true;
			}

			$cached_result = self::$_cache->get( $cache_key );

			if ( $flush || ! self::$_cache->has_valid( $cache_key ) ) {
				$result = $this->call( $path );

				if ( ! is_object( $result ) || isset( $result->error ) ) {
					// Api returned an error.
					if ( is_object( $cached_result ) &&
					     ! isset( $cached_result )
					) {
						// If there was an error during a newer data fetch,
						// fallback to older data version.
						$result = $cached_result;
					} else {
						// If no older data version, return result without
						// caching the error.
						return $result;
					}
				}

				self::$_cache->set( $cache_key, $result, $expiration );

				$cached_result = $result;
			}

			return $cached_result;
		}

		private function get_cache_key( $path, $method = 'GET', $params = array() ) {
			$canonized = $this->_api->CanonizePath( $path );
//			$exploded = explode('/', $canonized);
//			return $method . '_' . array_pop($exploded) . '_' . md5($canonized . json_encode($params));
			return $method . ':' . $canonized . ( ! empty( $params ) ? '#' . md5( json_encode( $params ) ) : '' );
		}

		/**
		 * Test API connectivity.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.0.9 If fails, try to fallback to HTTP.
		 * @since  1.1.6 Added a 5-min caching mechanism, to prevent from overloading the server if the API if
		 *         temporary down.
		 *
		 * @return bool True if successful connectivity to the API.
		 */
		static function test() {
			self::_init();

			$cache_key = 'ping_test';

			$test = self::$_cache->get_valid( $cache_key, null );

			if ( is_null( $test ) ) {
				$test = Freemius_Api::Test();

				if ( false === $test && Freemius_Api::IsHttps() ) {
					// Fallback to HTTP, since HTTPS fails.
					Freemius_Api::SetHttp();

					self::$_options->set_option( 'api_force_http', true, true );

					$test = Freemius_Api::Test();

					if ( false === $test ) {
						/**
						 * API connectivity test fail also in HTTP request, therefore,
						 * fallback to HTTPS to keep connection secure.
						 *
						 * @since 1.1.6
						 */
						self::$_options->set_option( 'api_force_http', false, true );
					}
				}

				self::$_cache->set( $cache_key, $test, WP_FS__TIME_5_MIN_IN_SEC );
			}

			return $test;
		}

		/**
		 * Check if API is temporary down.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.6
		 *
		 * @return bool
		 */
		static function is_temporary_down() {
			self::_init();

			$test = self::$_cache->get_valid( 'ping_test', null );

			return ( false === $test );
		}

		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.6
		 *
		 * @return object
		 */
		private function get_temporary_unavailable_error() {
			return (object) array(
				'error' => array(
					'type'    => 'TemporaryUnavailable',
					'message' => 'API is temporary unavailable.',
					'code'    => 'temporary_unavailable',
					'http'    => 503
				)
			);
		}

		/**
		 * Ping API for connectivity test, and return result object.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.0.9
		 *
		 * @param null|string $unique_anonymous_id
		 * @param bool        $is_update False if new plugin installation.
		 * @param string      $version
		 *
		 * @return object
		 */
		function ping( $unique_anonymous_id = null, $is_update = false, $version = '0.0.1' ) {
			$this->_logger->entrance();

			if ( self::is_temporary_down() ) {
				return $this->get_temporary_unavailable_error();
			}

			$pong = is_null( $unique_anonymous_id ) ?
				Freemius_Api::Ping() :
				$this->_call( 'ping.json?' . http_build_query( array(
						'uid'       => $unique_anonymous_id,
						'is_update' => $is_update,
						'version'   => $version,
					) ) );

			if ( $this->is_valid_ping( $pong ) ) {
				return $pong;
			}

			if ( self::should_try_with_http( $pong ) ) {
				// Fallback to HTTP, since HTTPS fails.
				Freemius_Api::SetHttp();

				self::$_options->set_option( 'api_force_http', true, true );

				$pong = is_null( $unique_anonymous_id ) ?
					Freemius_Api::Ping() :
					$this->_call( 'ping.json?' . http_build_query( array(
							'uid'       => $unique_anonymous_id,
							'is_update' => $is_update,
							'version'   => $version,
						) ) );

				if ( ! $this->is_valid_ping( $pong ) ) {
					self::$_options->set_option( 'api_force_http', false, true );
				}
			}

			return $pong;
		}

		/**
		 * Check if based on the API result we should try
		 * to re-run the same request with HTTP instead of HTTPS.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.6
		 *
		 * @param $result
		 *
		 * @return bool
		 */
		private static function should_try_with_http($result) {
			if ( ! Freemius_Api::IsHttps() ) {
				return false;
			}

			return ( ! is_object( $result ) ||
			         ! isset( $result->error ) ||
			         ! isset( $result->error->code ) ||
			         ! in_array( $result->error->code, array(
				         'curl_missing',
				         'cloudflare_ddos_protection',
				         'squid_cache_block',
				         'too_many_requests',
			         ) ) );

		}

		/**
		 * Check if valid ping request result.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.1
		 *
		 * @param mixed $pong
		 *
		 * @return bool
		 */
		function is_valid_ping( $pong ) {
			return Freemius_Api::Test( $pong );
		}

		function get_url( $path = '' ) {
			return Freemius_Api::GetUrl( $path, $this->_api->IsSandbox() );
		}

		/**
		 * Clear API cache.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.0.9
		 */
		static function clear_cache() {
			self::_init();

			self::$_cache = FS_Cache_Manager::get_manager( WP_FS__API_CACHE_OPTION_NAME );
			self::$_cache->clear();
		}
	}