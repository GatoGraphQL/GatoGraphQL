<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Integration_WooCommerce {

	private $_wc_options = array();

	public function init() {
		if ( ! class_exists( 'Woocommerce' ) )
			return;

		add_filter( 'aal_whitelist_options', array( &$this, 'wc_aal_whitelist_options' ) );
		add_filter( 'woocommerce_get_settings_pages', array( &$this, 'wc_get_settings_pages' ), 9999 );
	}

	/**
	 * @param WC_Settings_Page[] $settings
	 *
	 * @return WC_Settings_Page[]
	 */
	public function wc_get_settings_pages( $settings ) {
		if ( empty( $this->_wc_options ) ) {
			$wc_exclude_types  = array(
				'title',
				'sectionend',
			);
			$this->_wc_options = array();

			foreach ( $settings as $setting ) {
				foreach ( $setting->get_settings() as $option ) {
					if ( isset( $option['id'] ) && ( ! isset( $option['type'] ) || ! in_array( $option['type'], $wc_exclude_types ) ) )
						$this->_wc_options[] = $option['id'];
				}
			}
		}
		return $settings;
	}

	public function wc_aal_whitelist_options( $whitelist_options ) {
		if ( ! empty( $this->_wc_options ) ) {
			$whitelist_options = array_unique( array_merge( $whitelist_options, $this->_wc_options ) );
		}
		return $whitelist_options;
	}

	public function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
	}

}
new AAL_Integration_WooCommerce();
