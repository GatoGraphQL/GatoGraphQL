<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Notification_Email extends AAL_Notification_Base {
	
	/**
	 * Store options in a class locally
	 */
	protected $options = array();
	
	public function __construct() {
		parent::__construct();
		
		$this->id = 'email';
		$this->name = __( 'Email', 'aryo-activity-log' );
		$this->description = __( 'Get notified by Email.', 'aryo-activity-log' );
	}
	
	public function init() {
		$this->options = array_merge( array(
			'from_email'   => get_option( 'admin_email' ),
//			'message_format' => __( "Hi there!\n\nA notification condition on [sitename] was matched. Here are the details:\n\n[action-details]\n\nSent by ARYO Activity Log", 'aryo-activity-log' )
		), $this->get_handler_options() );
	}
	
	public function trigger( $args ) {
		$from_email = isset( $this->options['from_email'] ) && is_email( $this->options['from_email'] ) ? $this->options['from_email'] : '';
		$to_email   = isset( $this->options['to_email'] ) && is_email( $this->options['to_email'] ) ? $this->options['to_email'] : '';

		// if no from email or to email provided, quit.
		if ( ! ( $from_email || $to_email ) )
			return;

		$format = isset( $this->options['message_format'] ) ? $this->options['message_format'] : '';
		$body = $this->prep_notification_body( $args );
		$site_name = get_bloginfo( 'name' );
		$site_name_link = sprintf( '<a href="%s">%s</a>', home_url(), $site_name );

		$email_contents = strtr( $format, array(
			'[sitename]' => $site_name_link,
			'[action-details]' => $body,
		) );

		// set the content type
		add_filter( 'wp_mail_content_type', array( &$this, 'email_content_type' ) );

		wp_mail(
			$to_email,
			__( 'New notification from Activity Log', 'aryo-activity-log' ),
			nl2br( $email_contents ),
			array(
				"From: Activity Log @ $site_name <$from_email>"
			)
		);

		// reset back to how it was before
		remove_filter( 'wp_mail_content_type', array( &$this, 'email_content_type' ) );
	}

	public function email_content_type() {
		return apply_filters( 'aal_notification_email_content_type', 'text/html' );
	}
	
	public function settings_fields() {
		$default_email_message = __( "Hi there!\n\nA notification condition on [sitename] was matched. Here are the details:\n\n[action-details]\n\nSent by ARYO Activity Log", 'aryo-activity-log' );

		$this->add_settings_field_helper( 'from_email', __( 'From Email', 'aryo-activity-log' ), array( 'AAL_Settings_Fields', 'text_field' ), __( 'The source Email address', 'aryo-activity-log' ) );
		$this->add_settings_field_helper( 'to_email', __( 'To Email', 'aryo-activity-log' ), array( 'AAL_Settings_Fields', 'text_field' ), __( 'The Email address notifications will be sent to', 'aryo-activity-log' ) );
		$this->add_settings_field_helper( 'message_format', __( 'Message', 'aryo-activity-log' ), array( 'AAL_Settings_Fields', 'textarea_field' ), sprintf( __( 'Customize the message using the following placeholders: %s', 'aryo-activity-log' ), '[sitename], [action-details]' ), $default_email_message );
	}
	
	public function validate_options( $input ) {
		$output = array();
		$email_fields = array( 'to_email', 'from_email' );

		foreach ( $email_fields as $email_field ) {
			if ( isset( $input[ $email_field ] ) && is_email( $input[ $email_field ] ) )
				$output[ $email_field ] = $input[ $email_field ];
		}

		// email template message
		if ( ! empty( $input['message_format'] ) ) {
			$output['message_format'] = $input['message_format'];
		}

		return $output;
	}
}

// Register this handler, creates an instance of this class when necessary.
aal_register_notification_handler( 'AAL_Notification_Email' );