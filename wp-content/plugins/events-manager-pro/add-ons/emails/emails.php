<?php
class EM_Emails {
	/**
	 * Sets up email cron and filters/actions
	 */
	function init() {
		//enable custom emails
		if( get_option('dbem_custom_emails') ){
			include('custom-emails.php');
		}
	    //email reminders
	    add_action('update_option_dbem_emp_emails_reminder_time', array('EM_Emails','clear_crons'));
		if( get_option('dbem_cron_emails', 1) ) {
			//set up cron for addint to email queue
			if( !wp_next_scheduled('emp_cron_emails_queue') ){
			    $todays_time_to_run = strtotime(date('Y-m-d', current_time('timestamp')).' '.  get_option('dbem_emp_emails_reminder_time'));
			    $tomorrows_time_to_run = strtotime(date('Y-m-d', current_time('timestamp')+(86400)).' '. get_option('dbem_emp_emails_reminder_time'));
			    $time = $todays_time_to_run > current_time('timestamp') ? $todays_time_to_run:$tomorrows_time_to_run;
				$result = wp_schedule_event( $time,'daily','emp_cron_emails_queue');
			}
			add_action('emp_cron_emails_queue', array('EM_Emails','queue_emails') );
			//set up cron for clearing email queue
			if( !wp_next_scheduled('emp_cron_emails_process_queue') ){
				$result = wp_schedule_event( time(),'em_minute','emp_cron_emails_process_queue');
			}
			add_action('emp_cron_emails_process_queue', array('EM_Emails','process_queue') );
			if( get_option('dbem_emp_emails_reminder_ical') ){
				//set up emails for ical cleaning
				if( !wp_next_scheduled('emp_cron_emails_ical_cleanup') ){
				    $todays_time_to_run = strtotime(date('Y-m-d', current_time('timestamp')).' '.  get_option('dbem_emp_emails_reminder_time'));
				    $tomorrows_time_to_run = strtotime(date('Y-m-d', current_time('timestamp')+(86400)).' '. get_option('dbem_emp_emails_reminder_time'));
				    $time = $todays_time_to_run > current_time('timestamp') ? $todays_time_to_run:$tomorrows_time_to_run;
					$result = wp_schedule_event( $time,'daily','emp_cron_emails_ical_cleanup');
				}
				add_action('emp_cron_emails_ical_cleanup', array('EM_Emails','clean_icals') );
			}else{
				wp_clear_scheduled_hook('emp_cron_emails_ical_cleanup');
			}
		}else{
			//unschedule the crons
			wp_clear_scheduled_hook('emp_cron_emails_process_queue');
			wp_clear_scheduled_hook('emp_cron_emails_queue');
			wp_clear_scheduled_hook('emp_cron_emails_ical_cleanup');
		}
		//admin area
		if( is_admin() ){
		    include('emails-admin.php');
		}
	}
	
	function clear_crons(){
	    wp_clear_scheduled_hook('emp_cron_emails_queue');
	    wp_clear_scheduled_hook('emp_cron_emails_ical_cleanup');
	}
	
	/**
	 * Run on cron and prep emails to go out
	 */
	function queue_emails(){
	    global $EM_Event, $wpdb;
	    $old_EM_Event = !empty($EM_Event) ? clone($EM_Event):null; //save old event in case already set
		//disable the current events are past rule
	    add_filter('option_pre_dbem_events_current_are_past', 'em_emails_return_false', create_function('$a', 'return false;'));
	    //For each event x days on
	    $days = get_option('dbem_emp_emails_reminder_days',1);
	    $scope = ($days > 0) ? date('Y-m-d', current_time('timestamp') + (86400*$days)):date('Y-m-d', current_time('timestamp')+86400);
	    //make sure we don't get past events, only events starting that specific date
	    $events_are_past = get_option('dbem_events_current_are_past');
	    update_option('dbem_events_current_are_past', true);
		$output_type = get_option('dbem_smtp_html') ? 'html':'email';
	    foreach( EM_Events::get(array('scope'=>$scope,'private'=>1,'blog'=>false)) as $EM_Event ){
	        /* @var $EM_Event EM_Event */
	        $emails = array();
	    	//get ppl attending
	    	foreach( $EM_Event->get_bookings()->get_bookings()->bookings as $EM_Booking ){ //get confirmed bookings
	    	    /* @var $EM_Booking EM_Booking */
	    	    if( is_email($EM_Booking->get_person()->user_email) ){
			    	$subject = $EM_Booking->output(get_option('dbem_emp_emails_reminder_subject'),'raw');
			    	$message = $EM_Booking->output(get_option('dbem_emp_emails_reminder_body'),$output_type);
		    	    $emails[] = array($EM_Booking->get_person()->user_email, $subject, $message, $EM_Booking->booking_id);
	    	    }
	    	}
	    	if(count($emails) > 0){
	    	    $attachments = serialize(array());
	    	    if( get_option('dbem_emp_emails_reminder_ical') ){
		    	    //create invite ical
		    	    $upload_dir = wp_upload_dir();
		    	    $icalfilename = trailingslashit($upload_dir['basedir'])."em-cache/invite_".$EM_Event->event_id.".ics";
		    	    $icalfile = fopen($icalfilename,'w+');
		    	    if( $icalfile ){
						ob_start();
						em_locate_template('templates/ical-event.php', true);
						$icalcontent = preg_replace("/([^\r])\n/", "$1\r\n", ob_get_clean());
						fwrite($icalfile, $icalcontent);
						fclose($icalfile);
						$ical_file_array = array('name'=>'invite.ics', 'type'=>'text/calendar','path'=>$icalfilename);
						$attachments = serialize(array($ical_file_array));
		    	    }
	    	    }
	    	    foreach($emails as $email){
			    	$wpdb->insert(EM_EMAIL_QUEUE_TABLE, array('email'=>$email[0],'subject'=>$email[1],'body'=>$email[2],'attachment'=>$attachments,'event_id'=>$EM_Event->event_id,'booking_id'=>$email[3]));
	    	    }
	    	}
	    	
	    }
	    //cleanup
	    update_option('dbem_events_current_are_past', $events_are_past); //reset previous current events are past setting
	    $EM_Event = $old_EM_Event; //reset global
	    remove_filter('option_pre_dbem_events_current_are_past', 'em_emails_return_false');
	}
	
	function process_queue(){
	    //init phpmailer
		global $EM_Mailer, $wpdb;
		if( !is_object($EM_Mailer) ){
			$EM_Mailer = new EM_Mailer();
		}
    	add_action('em_mailer', array('EM_Emails','em_mailer_mod'), 10, 1);
    	add_action('em_mailer_sent', array('EM_Emails','em_mailer_mod_cleanup'), 10, 1);
		//get queue
		$limit = get_option('emp_cron_emails_limit', 100);
		$results = $wpdb->get_results("SELECT * FROM ".EM_EMAIL_QUEUE_TABLE." ORDER BY queue_id  ASC LIMIT $limit");
		$ids_to_delete = array();
		foreach($results as $email){
		    $ids_to_delete[] = $email->queue_id;
		    $EM_Mailer->send($email->subject, $email->body, $email->email, unserialize($email->attachment));
		}
    	//cleanup
    	if( count($ids_to_delete) > 0 ){
			$wpdb->query("DELETE FROM ".EM_EMAIL_QUEUE_TABLE.' WHERE queue_id IN ('.implode(',',$ids_to_delete).')');
		}
    	remove_action('em_mailer', array('EM_Emails','em_mailer_mod'), 10, 1);
    	remove_action('em_mailer_sent', array('EM_Emails','em_mailer_mod_cleanup'), 10, 1);
	}

	/**
	 * Cleans unused ical files 
	 */
	function clean_icals(){
	    global $wpdb;
	    //get theme CSS files
	    $upload_dir = wp_upload_dir();
	    $icalsearch = trailingslashit($upload_dir['basedir'])."em-cache/invite_*.ics";
	    foreach( glob( $icalsearch ) as $css_file ){
	        if( preg_match('/invite_([0-9]+)\.ics$/', $css_file, $matches) ){
		        $event_id = $matches[1];
		        //count number of matches
		        $count = $wpdb->get_var("SELECT COUNT(*) FROM ".EM_EMAIL_QUEUE_TABLE." WHERE event_id=$event_id");
		        if($count == 0){
		            unlink($css_file);
		        }
	        }
	    }
	}

	/**
	 * Modifies the PHPMailer class to keep SMTP connections alive.
	 * @param EM_PHPMailer $mail
	 */
	function em_mailer_mod($mail){
	    $mail->SMTPKeepAlive = true;
	}
	
	/**
	 * Cleans up actions taken in em_mailer_mod, currently closing the SMTP connection kept alive.
	 * @param EM_PHPMailer $mail 
	 */
	function em_mailer_mod_cleanup($mail){
	    $mail->SmtpClose();
	}

}
add_action('init',array('EM_Emails','init'));