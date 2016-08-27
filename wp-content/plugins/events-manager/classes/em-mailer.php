<?php
/**
 * phpmailer support
 *
 */
class EM_Mailer {
	
	/**
	 * if any errors crop up, here they are
	 * @var array
	 */
	var $errors = array();
	
	/**
	 * @param $subject
	 * @param $body
	 * @param $receiver
	 */
	function send($subject="no title",$body="No message specified", $receiver='', $attachments = array() ) {
		//TODO add an EM_Error global object, for this sort of error reporting. (@marcus like StatusNotice)
		global $smtpsettings, $phpmailer, $cformsSettings;
		$subject = html_entity_decode(wp_kses_data($subject)); //decode entities, but run kses first just in case users use placeholders containing html
		if( is_array($receiver) ){
			$receiver_emails = array();
			foreach($receiver as $receiver_email){
				$receiver_emails[] = is_email($receiver_email);
			}
			$emails_ok = !in_array(false, $receiver_emails);
		}else{
			$emails_ok = is_email($receiver);
		}
		if( get_option('dbem_smtp_html') && get_option('dbem_smtp_html_br') ){
			$body = nl2br($body);
		}
		if ( $emails_ok && get_option('dbem_rsvp_mail_send_method') == 'wp_mail' ){
			$from = get_option('dbem_mail_sender_address');
			$headers = get_option('dbem_mail_sender_name') ? 'From: '.get_option('dbem_mail_sender_name').' <'.$from.'>':'From: '.$from;
			if( get_option('dbem_smtp_html') ){ //create filter to change content type to html in wp_mail
				add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
			}
			$send = wp_mail($receiver, $subject, $body, $headers);
			if(!$send){
				global $phpmailer;
				$this->errors[] = $phpmailer->ErrorInfo;
			}
			return $send;
		}elseif ( $emails_ok && get_option('dbem_rsvp_mail_send_method') == 'mail' ){
			if(is_array($receiver)){
				$receiver = implode(', ', $receiver);
			}
			$headers = '';
			if( get_option('dbem_smtp_html') ){
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset="UTF-8"' . "\r\n";
			}else{
			    $headers = 'Content-Type: text/plain; charset="UTF-8"' . "\r\n";
			}
			$from = get_option('dbem_mail_sender_address');
			$headers .= get_option('dbem_mail_sender_name') ? 'From: '.get_option('dbem_mail_sender_name').' <'.$from.'>':'From: '.$from;
			$send = mail($receiver, $subject, $body, $headers);
			if(!$send){
				$this->errors[] = __('Could not send email.', 'events-manager');
			}
			return $send;
		}elseif( $emails_ok ){
			$this->load_phpmailer();
			$mail = new EM_PHPMailer();
			//$mail->SMTPDebug = true;
			if( get_option('dbem_smtp_html') ){
				$mail->isHTML();
			}
			$mail->ClearAllRecipients();
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			$mail->CharSet = 'utf-8';
		    $mail->SetLanguage('en', dirname(__FILE__).'/');
			$mail->PluginDir = dirname(__FILE__).'/phpmailer/';
			$mail->Host = get_option('dbem_smtp_host');
			$mail->port = get_option('dbem_rsvp_mail_port');
			$mail->Username = get_option('dbem_smtp_username');  
			$mail->Password = get_option('dbem_smtp_password');  
			$mail->From = get_option('dbem_mail_sender_address');			
			$mail->FromName = get_option('dbem_mail_sender_name'); // This is the from name in the email, you can put anything you like here
			$mail->Body = $body;
			$mail->Subject = $subject;
			//add attachments
			if( is_array($attachments) ){
				foreach($attachments as $attachment){
				    $att = array('name'=> '', 'encoding' => 'base64', 'type' => 'application/octet-stream');
				    if( is_array($attachment) ){
				        $att = array_merge($att, $attachment);
				    }else{
				        $att['path'] = $attachment;
				    }
				    $mail->AddAttachment($att['path'], $att['name'], $att['encoding'], $att['type']);
				}
			}			
			do_action('em_mailer', $mail); //$mail will still be modified  
			if(is_array($receiver)){
				foreach($receiver as $receiver_email){
					$mail->AddAddress($receiver_email);	
				}
			}else{
				$mail->AddAddress($receiver);	
			}
		
			//Protocols
		 	if( get_option('dbem_rsvp_mail_send_method') == 'qmail' ){       
				$mail->IsQmail();
			}else {
				$mail->Mailer = get_option('dbem_rsvp_mail_send_method');
			}                     
			if(get_option('dbem_rsvp_mail_SMTPAuth') == '1'){
				$mail->SMTPAuth = TRUE;
		 	}
		 	$send = $mail->Send();
			if(!$send){
				$this->errors[] = $mail->ErrorInfo;
			}
			do_action('em_mailer_sent', $mail, $send); //$mail can still be modified
			return $send;
		}else{
			$this->errors[] = __('Please supply a valid email format.', 'events-manager');
			return false;
		}
	}
	
	/**
	 * load phpmailer classes
	 */
	function load_phpmailer(){
		require_once(dirname(__FILE__) . '/phpmailer/class.phpmailer.php');
		require_once(dirname(__FILE__) . '/phpmailer/class.smtp.php');
	}
}
?>