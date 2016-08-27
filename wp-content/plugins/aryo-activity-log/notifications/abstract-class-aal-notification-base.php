<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Base class, handles notifications
 * 
 * Class AAL_Notification_Base
 */
abstract class AAL_Notification_Base {
	/**
	 * The following variables have to be defined for each payment method.
	 */
	public $id = '';
	public $name = '';
	public $description = '';
	
	public $aal_options;
	
	public function __construct() {
		$this->aal_options = AAL_Main::instance()->settings->get_options();
		
		add_action( 'init', array( &$this, 'init' ), 30 );
		add_action( 'aal_validate_options', array( &$this, '_validate_options' ), 10, 2 );
	}

	private function settings_field_name_attr( $name ) {
		return esc_attr( "notification_handler_options_{$this->id}[{$name}]" );
	}
	
	public function init() {}
	
	/**
	 * Registers the settings for this individual extension
	 */
	public function settings_fields() {}
	
	/**
	 * Exectutes when notification is due
	 */
	public function trigger( $args ) {}
	
	public function _settings_section_callback() {
		echo '<p>' . $this->description . '</p>';
	}
	
	public function _settings_enabled_field_callback( $args = array() ) {
		AAL_Settings_Fields::yesno_field( $args );
	}
	
	public function add_settings_field_helper( $option_name, $title, $callback, $description = '', $default_value = '' ) {
		$settings_page_slug = AAL_Main::instance()->settings->slug();
		$handler_options = isset( $this->aal_options["handler_options_{$this->id}"] )
			? $this->aal_options["handler_options_{$this->id}"] : array();
		
		add_settings_field( 
			"notification_handler_{$this->id}_{$option_name}", 
			$title, 
			$callback, 
			$settings_page_slug, 
			"notification_{$this->id}",
			array(
				'name' 		=> $this->settings_field_name_attr( $option_name ),
				'value' 	=> isset( $handler_options[ $option_name ] ) ? $handler_options[ $option_name ] : $default_value,
				'desc' 		=> $description,
				'id'      	=> $option_name,
				'page'    	=> $settings_page_slug,
			) 
		);
	}
	
	public function _validate_options( $form_data, $aal_options ) {
		$post_key 	= "notification_handler_options_{$this->id}";
		$option_key = "handler_options_{$this->id}";
	
		if ( ! isset( $_POST[ $post_key ] ) )
			return $form_data;
	
		$input = $_POST[ $post_key ];
		$output = ( method_exists( $this, 'validate_options' ) ) ? $this->validate_options( $input ) : array();
		$form_data[ $option_key ] = $output;
	
		return $form_data;
	}
	
	public function get_handler_options() {
		$handler_options = array();
		$option_key = "handler_options_{$this->id}";
		
		if ( isset( $this->aal_options[ $option_key ] ) ) {
			$handler_options = (array) $this->aal_options[ $option_key ];
		}
		
		return $handler_options;
	}

	public function prep_notification_body( $args ) {
		$details_to_provide = array(
			'user_id'     => __( 'User', 'aryo-activity-log' ),
			'object_type' => __( 'Object Type', 'aryo-activity-log' ),
			'object_name' => __( 'Object Name', 'aryo-activity-log' ),
			'action'      => __( 'Action Type', 'aryo-activity-log' ),
			'hist_ip'     => __( 'IP Address', 'aryo-activity-log' ),
		);
		$message = '';

		foreach ( $details_to_provide as $detail_key => $detail_title ) {
			$detail_val = '';

			switch ( $detail_key ) {
				case 'user_id':
					if ( is_numeric( $args[ $detail_key ] ) ) {
						// this is a user ID
						$user = new WP_User( $args[ $detail_key ] );

						if ( ! is_wp_error( $user ) ) {
							$detail_val = sprintf( '<a href="%s">%s</a>', esc_url( get_edit_user_link( $user->ID ) ), esc_html( $user->display_name ) );
						}
					}
					break;
				default:
					$detail_val = isset( $args[ $detail_key ] ) ? $args[ $detail_key ] : __( 'N/A', 'aryo-activity-log' );
					break;
			}

			$message .= sprintf( "<strong>%s</strong> - %s\n", $detail_title, $detail_val );
		}

		return $message;
	}
}

function aal_register_notification_handler( $classname = '' ) {
	return AAL_Main::instance()->notifications->register_handler( $classname );
}