<?php

// don't use this form - direct to WP public forums
exit;

// access wp functions externally
require_once('lib-bootstrap.php');
include_once(ABSPATH . 'wp-includes/pluggable.php'); // required for wp_mail

if ( ! function_exists('gde_activate') ) {
	// no access if parent plugin is disabled
	wp_die('<p>'.__('You do not have sufficient permissions to access this page.').'</p>');
} elseif ( empty( $_POST ) || ! isset( $_POST['email'] ) || empty( $_POST['email'] ) ) {
	// fail submit if email not completed
	echo "fail";
	exit;
} else {
	
	function gde_change_phpmailer( $phpmailer ) {
		// gather settings and profiles
		$datasrc = GDE_PLUGIN_URL . 'libs/lib-service.php?json=all';
		$response = wp_remote_get( $datasrc );
		if ( !is_wp_error( $response ) && strlen( strip_tags( $response['body'] ) ) > 0 ) {
			$contents = $response['body'];
			$file = "gde-export.txt";
		} else {
			$contents = "Error attaching export data.";
			$file = "export-error.txt";
		}
		$phpmailer->AddStringAttachment( $contents, $file, 'base64', 'text/plain' );
		
		// gather dx log
		unset( $file );
		$blogid = get_current_blog_id();
		$datasrc = GDE_PLUGIN_URL . 'libs/lib-service.php?viewlog=all&blogid=' . $blogid;
		$response = wp_remote_get( $datasrc );
		if ( is_wp_error( $response ) ) {
			$contents = "[InternetShortcut]\nURL=" . $datasrc ."\n";
			$file = "remote-dx-log.url";
		} else if ( strlen( $response['body'] ) > 0 ){
			$contents = $response['body'];
			$file = "dx-log.txt";
		}
		if ( isset( $file ) ) {
			$phpmailer->AddStringAttachment( $contents, $file, 'base64', 'text/plain' );
		}
	}
	
	function gde_change_mail( $mail ) {
		return $_POST['email'];
    }
    
    function gde_change_sender( $sendername ) {
		if ($_POST['name']) {
			return $_POST['name'];
		} else {
			return "GDE User";
		}
    }
	
    add_filter( 'wp_mail_from', 'gde_change_mail', 1 );
    add_filter( 'wp_mail_from_name', 'gde_change_sender', 1 );
	
	/* 
	 * Note to self: wp_mail doesn't deliver to Google Apps (at least in this config).
	 * It does deliver to regular Gmail, if necessary. Why? Hours wasted.
	 * Instead, deliver to POP account and let GA pick it up. Boo.
	 */
	$to = "wpp@dev.davismetro.com";
	
	$subject = "GDE Support Request [#" . uniqid() . "]";
	
	$headers = "";
	if ($_POST['cc'] == "yes") {
		$headers .= "CC: " . $_POST['email'] . "\n";
	}
	$headers .= "Reply-To: <" . $_POST['email'] . ">\n";
	
	$message = "A request was sent from the GDE Support Form.\n\n";
	if ( $_POST['msg'] ) {
		$message .= stripslashes( $_POST['msg'] ) . "\n\n";
	} else {
		$message .= "No message was included.\n\n";
	}
	
	if ( $_POST['sc'] ) {
		$message .= "Shortcode: " . stripslashes( $_POST['sc'] ) . "\n\n";
	} else {
		$message .= "No shortcode was included.\n\n";
	}
	
	if ( $_POST['url'] ) {
		$message .= "URL: " . stripslashes( $_POST['url'] ) . "\n\n";
	} else {
		$message .= "No URL was included.\n\n";
	}
	
	if ( $_POST['senddb'] ) {
		$message .= $_POST['senddb'];
		
		// add debug attachment
		add_filter( 'phpmailer_init', 'gde_change_phpmailer' );
	} else {
		$message .= "No debug info was included.";
	}
	
	if (wp_mail( $to, $subject, $message, $headers )) {
		echo "success";
	} else {
		echo "fail";
	}
}

?>
