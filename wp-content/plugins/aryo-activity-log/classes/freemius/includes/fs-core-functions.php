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

	global $fs_core_logger;

	$fs_core_logger = FS_Logger::get_logger( WP_FS__SLUG . '_core', WP_FS__DEBUG_SDK, WP_FS__ECHO_DEBUG_SDK );

	function fs_dummy() {
	}

	/* Url.
	--------------------------------------------------------------------------------------------*/
	function fs_get_url_daily_cache_killer() {
		return date( '\YY\Mm\Dd' );
	}

	/* Templates / Views.
	--------------------------------------------------------------------------------------------*/
	function fs_get_template_path( $path ) {
		return WP_FS__DIR_TEMPLATES . '/' . trim( $path, '/' );
	}

	function fs_include_template( $path, &$params = null ) {
		$VARS = &$params;
		include( fs_get_template_path( $path ) );
	}

	function fs_include_once_template( $path, &$params = null ) {
		$VARS = &$params;
		include_once( fs_get_template_path( $path ) );
	}

	function fs_require_template( $path, &$params = null ) {
		$VARS = &$params;
		require( fs_get_template_path( $path ) );
	}

	function fs_require_once_template( $path, &$params = null ) {
		$VARS = &$params;
		require_once( fs_get_template_path( $path ) );
	}

	function fs_get_template( $path, &$params = null ) {
		ob_start();

		$VARS = &$params;
		require_once( fs_get_template_path( $path ) );

		return ob_get_clean();
	}

	/* Scripts and styles including.
	--------------------------------------------------------------------------------------------*/
	function fs_enqueue_local_style( $handle, $path, $deps = array(), $ver = false, $media = 'all' ) {
		global $fs_core_logger;
		if ( $fs_core_logger->is_on() ) {
			$fs_core_logger->info( 'handle = ' . $handle . '; path = ' . $path . ';' );
			$fs_core_logger->info( 'plugin_basename = ' . plugins_url( WP_FS__DIR_CSS . trim( $path, '/' ) ) );
			$fs_core_logger->info( 'plugins_url = ' . plugins_url( plugin_basename( WP_FS__DIR_CSS . '/' . trim( $path, '/' ) ) ) );
		}

		wp_enqueue_style( $handle, plugins_url( plugin_basename( WP_FS__DIR_CSS . '/' . trim( $path, '/' ) ) ), $deps, $ver, $media );
	}

	function fs_enqueue_local_script( $handle, $path, $deps = array(), $ver = false, $in_footer = 'all' ) {
		global $fs_core_logger;
		if ( $fs_core_logger->is_on() ) {
			$fs_core_logger->info( 'handle = ' . $handle . '; path = ' . $path . ';' );
			$fs_core_logger->info( 'plugin_basename = ' . plugins_url( WP_FS__DIR_JS . trim( $path, '/' ) ) );
			$fs_core_logger->info( 'plugins_url = ' . plugins_url( plugin_basename( WP_FS__DIR_JS . '/' . trim( $path, '/' ) ) ) );
		}

		wp_enqueue_script( $handle, plugins_url( plugin_basename( WP_FS__DIR_JS . '/' . trim( $path, '/' ) ) ), $deps, $ver, $in_footer );
	}

	function fs_img_url( $path, $img_dir = WP_FS__DIR_IMG ) {
		return plugins_url( plugin_basename( $img_dir . '/' . trim( $path, '/' ) ) );
	}

	/* Request handlers.
	--------------------------------------------------------------------------------------------*/
	/**
	 * @param string $key
	 * @param mixed  $def
	 *
	 * @return mixed
	 */
	function fs_request_get( $key, $def = false ) {
		return isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $def;
	}

	function fs_request_has( $key ) {
		return isset( $_REQUEST[ $key ] );
	}

	function fs_request_get_bool( $key, $def = false ) {
		return ( isset( $_REQUEST[ $key ] ) && ( 1 == $_REQUEST[ $key ] || 'true' === strtolower( $_REQUEST[ $key ] ) ) ) ? true : $def;
	}

	function fs_request_is_post() {
		return ( 'post' === strtolower( $_SERVER['REQUEST_METHOD'] ) );
	}

	function fs_request_is_get() {
		return ( 'get' === strtolower( $_SERVER['REQUEST_METHOD'] ) );
	}

	function fs_get_action( $action_key = 'action' ) {
		if ( ! empty( $_REQUEST[ $action_key ] ) ) {
			return strtolower( $_REQUEST[ $action_key ] );
		}

		if ( 'action' == $action_key ) {
			$action_key = 'fs_action';

			if ( ! empty( $_REQUEST[ $action_key ] ) ) {
				return strtolower( $_REQUEST[ $action_key ] );
			}
		}

		return false;
	}

	function fs_request_is_action( $action, $action_key = 'action' ) {
		return ( strtolower( $action ) === fs_get_action( $action_key ) );
	}

	function fs_is_plugin_page( $menu_slug ) {
		return ( is_admin() && $_REQUEST['page'] === $menu_slug );
	}

	/* Core UI.
	--------------------------------------------------------------------------------------------*/
	function fs_ui_action_button( $slug, $page, $action, $title, $params = array(), $is_primary = true ) {
		?><a class="button<?php if ( $is_primary ) {
			echo ' button-primary';
		} ?>"
		     href="<?php echo wp_nonce_url( freemius( $slug )->_get_admin_page_url( $page, array_merge( $params, array( 'fs_action' => $action ) ) ), $action ) ?>"><?php echo $title ?></a><?php
	}

	function fs_ui_action_link( $slug, $page, $action, $title, $params = array() ) {
		?><a class=""
		     href="<?php echo wp_nonce_url( freemius( $slug )->_get_admin_page_url( $page, array_merge( $params, array( 'fs_action' => $action ) ) ), $action ) ?>"><?php echo $title ?></a><?php
	}

	/*function fs_error_handler($errno, $errstr, $errfile, $errline)
	{
		if (false === strpos($errfile, 'freemius/'))
		{
			// @todo Dump Freemius errors to local log.
		}

//		switch ($errno) {
//			case E_USER_ERROR:
//				break;
//			case E_WARNING:
//			case E_USER_WARNING:
//				break;
//			case E_NOTICE:
//			case E_USER_NOTICE:
//				break;
//			default:
//				break;
//		}
	}

	set_error_handler('fs_error_handler');*/

	function fs_nonce_url( $actionurl, $action = - 1, $name = '_wpnonce' ) {
//		$actionurl = str_replace( '&amp;', '&', $actionurl );
		return add_query_arg( $name, wp_create_nonce( $action ), $actionurl );
	}

	/**
	 * Check if string starts with.
	 *
	 * @author Vova Feldman (@svovaf)
	 * @since  1.1.3
	 *
	 * @param string $haystack
	 * @param string $needle
	 *
	 * @return bool
	 */
	function fs_starts_with( $haystack, $needle ) {
		$length = strlen( $needle );

		return ( substr( $haystack, 0, $length ) === $needle );
	}

	#region Url Canonization ------------------------------------------------------------------

	/**
	 * @author Vova Feldman (@svovaf)
	 * @since  1.1.3
	 *
	 * @param string $url
	 * @param bool   $omit_host
	 * @param array  $ignore_params
	 *
	 * @return string
	 */
	function fs_canonize_url( $url, $omit_host = false, $ignore_params = array() ) {
		$parsed_url = parse_url( strtolower( $url ) );

//		if ( ! isset( $parsed_url['host'] ) ) {
//			return $url;
//		}

		$canonical = ( ( $omit_host || ! isset( $parsed_url['host'] ) ) ? '' : $parsed_url['host'] ) . $parsed_url['path'];

		if ( isset( $parsed_url['query'] ) ) {
			parse_str( $parsed_url['query'], $queryString );
			$canonical .= '?' . fs_canonize_query_string( $queryString, $ignore_params );
		}

		return $canonical;
	}

	/**
	 * @author Vova Feldman (@svovaf)
	 * @since  1.1.3
	 *
	 * @param array $params
	 * @param array $ignore_params
	 * @param bool  $params_prefix
	 *
	 * @return string
	 */
	function fs_canonize_query_string( array $params, array &$ignore_params, $params_prefix = false ) {
		if ( ! is_array( $params ) || 0 === count( $params ) ) {
			return '';
		}

		// Urlencode both keys and values
		$keys   = fs_urlencode_rfc3986( array_keys( $params ) );
		$values = fs_urlencode_rfc3986( array_values( $params ) );
		$params = array_combine( $keys, $values );

		// Parameters are sorted by name, using lexicographical byte value ordering.
		// Ref: Spec: 9.1.1 (1)
		uksort( $params, 'strcmp' );

		$pairs = array();
		foreach ( $params as $parameter => $value ) {
			$lower_param = strtolower( $parameter );

			// Skip ignore params.
			if ( in_array( $lower_param, $ignore_params ) || ( false !== $params_prefix && startsWith( $lower_param, $params_prefix ) ) ) {
				continue;
			}

			if ( is_array( $value ) ) {
				// If two or more parameters share the same name, they are sorted by their value
				// Ref: Spec: 9.1.1 (1)
				natsort( $value );
				foreach ( $value as $duplicate_value ) {
					$pairs[] = $lower_param . '=' . $duplicate_value;
				}
			} else {
				$pairs[] = $lower_param . '=' . $value;
			}
		}

		if ( 0 === count( $pairs ) ) {
			return '';
		}

		return implode( "&", $pairs );
	}

	/**
	 * @author Vova Feldman (@svovaf)
	 * @since  1.1.3
	 *
	 * @param string|string[] $input
	 *
	 * @return array|mixed|string
	 */
	function fs_urlencode_rfc3986( $input ) {
		if ( is_array( $input ) ) {
			return array_map( 'fs_urlencode_rfc3986', $input );
		} else if ( is_scalar( $input ) ) {
			return str_replace( '+', ' ', str_replace( '%7E', '~', rawurlencode( $input ) ) );
		}

		return '';
	}

	#endregion Url Canonization ------------------------------------------------------------------

	function fs_download_image( $from, $to ) {
		$ch = curl_init( $from );
		$fp = fopen( fs_normalize_path( $to ), 'wb' );
		curl_setopt( $ch, CURLOPT_FILE, $fp );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_exec( $ch );
		curl_close( $ch );
		fclose( $fp );
	}

    /* General Utilities
    --------------------------------------------------------------------------------------------*/

    /**
     * Sorts an array by the value of the priority key.
     *
     * @author Daniel Iser (@danieliser)
     * @since  1.1.7
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    function fs_sort_by_priority( $a, $b ) {

        // If b has a priority and a does not, b wins.
        if ( ! isset( $a['priority'] ) && isset( $b['priority'] ) ) {
            return 1;
        }
        // If b has a priority and a does not, b wins.
        elseif ( isset( $a['priority'] ) && ! isset( $b['priority'] ) ) {
            return -1;
        }
        // If neither has a priority or both priorities are equal its a tie.
        elseif ( ( ! isset( $a['priority'] ) && ! isset( $b['priority'] ) ) || $a['priority'] === $b['priority'] ) {
            return 0;
        }

        // If both have priority return the winner.
        return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
    }

