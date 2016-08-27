<?php
/**
 * @param boolean $result
 * @param EM_Event $EM_Event
 * @return boolean
 */
function em_event_submission_emails($result, $EM_Event){
    if( $result ){
		//if this is just published, we need to email the user about the publication, or send to pending mode again for review
		$cant_publish_event = $EM_Event->is_individual() && !user_can($EM_Event->get_contact()->ID, 'publish_events');
		$cant_publish_recurring_event = $EM_Event->is_recurring() && !user_can($EM_Event->get_contact()->ID, 'publish_recurring_events'); 
		$output_type = get_option('dbem_smtp_html') ? 'html':'email';
		if( $cant_publish_event || $cant_publish_recurring_event ){
		    if( $EM_Event->is_published() && !$EM_Event->get_previous_status() ){
		        //only send email to users that can't publish events themselves and that were previously unpublished
				$approvals_count = get_post_meta($EM_Event->post_id,'_event_approvals_count', true);
				$approvals_count = $approvals_count > 0 ? $approvals_count:0;
				if( $approvals_count == 1 ){
		        	$subject = $EM_Event->output(get_option('dbem_event_approved_email_subject'), 'raw');
		        	$body = $EM_Event->output(get_option('dbem_event_approved_email_body'), $output_type);
				}else{
		        	$subject = $EM_Event->output(get_option('dbem_event_reapproved_email_subject'), 'raw');
		        	$body = $EM_Event->output(get_option('dbem_event_reapproved_email_body'), $output_type);				    
				}
	       		if( $EM_Event->event_owner == "" ) return true;
	        	$EM_Event->email_send( $subject, $body, $EM_Event->get_contact()->user_email);
		    }elseif( $EM_Event->get_status() === 0 && get_option('dbem_event_submitted_email_admin') != '' && empty($EM_Event->duplicated)){
				$approvals_count = get_post_meta($EM_Event->post_id,'_event_approvals_count', true);
				$approvals_count = $approvals_count > 0 ? $approvals_count:0;
				update_post_meta($EM_Event->post_id, '_event_approvals_count', $approvals_count+1);
				$admin_emails = explode(',', str_replace(' ', '', get_option('dbem_event_submitted_email_admin'))); //admin emails are in an array, single or multiple
	        	if( empty($admin_emails) ) return true;
				if( $approvals_count > 1 ){
					$subject = $EM_Event->output(get_option('dbem_event_resubmitted_email_subject'), 'raw');
					$message = $EM_Event->output(get_option('dbem_event_resubmitted_email_body'), $output_type);
				}else{
					$subject = $EM_Event->output(get_option('dbem_event_submitted_email_subject'), 'raw');
					$message = $EM_Event->output(get_option('dbem_event_submitted_email_body'), $output_type);				
				}
				//Send email to admins
				$EM_Event->email_send( $subject,$message, $admin_emails);
			}
		}elseif( !current_user_can('manage_options') ){
		    if( $EM_Event->is_published() && !$EM_Event->get_previous_status() ){
	        	$admin_emails = explode(',', str_replace(' ', '', get_option('dbem_event_submitted_email_admin'))); //admin emails are in an array, single or multiple
	        	if( empty($admin_emails) ) return true;
	        	$subject = $EM_Event->output(get_option('dbem_event_published_email_subject'), 'raw');
	        	$body = $EM_Event->output(get_option('dbem_event_published_email_body'), $output_type);
		        $EM_Event->email_send( $subject, $body, $admin_emails);
		    }
		}
    }
    return $result;
}
add_filter('em_event_save','em_event_submission_emails',10,2);