<?php
class EM_Emails_Admin {
    
    public static function init(){
        add_action('em_options_page_footer_emails', 'EM_Emails_Admin::reminder_options');
        add_action('em_options_page_footer_emails', 'EM_Emails_Admin::custom_email_options');
    }
	
    /*
     * --------------------------------------------
     * Email Reminders
     * --------------------------------------------
     */
	/**
	 * Generates meta box for settings page 
	 */
	public static function reminder_options(){
	    global $save_button;
	    ?>
		<div  class="postbox " id="em-opt-email-reminders" >
		<div class="handlediv" title="<?php __('Click to toggle', 'dbem'); ?>"><br /></div><h3><?php _e ( 'Event Email Reminders', 'em-pro' ); ?></h3>
		<div class="inside">
			<table class='form-table'>
				<tr><td colspan='2'>
					<p>
						<?php _e( 'Events Manager can send people that booked a place at your events a reminder email before it starts.', 'em-pro' );  ?>
						<?php echo sprintf(__('We use <a href="%s">WP Cron</a> for scheduling checks for future events, which relies on site visits to trigger these tasks to run. If you have low levels of site traffic, this may not happen frequently enough, so you may want to consider forcing WP-Cron to run every few minutes. For more information, <a href="%s">read this tutorial</a> on setting up WP Cron.','em-pro'),'#emails','#emails'); ?>
					</p>
					<p><?php _e('<strong>Important!</strong>, you should use SMTP as your email setup if you are sending automated emails in this way for optimal performance. Other methods are not suited to sending mass emails.', 'em-pro'); ?>
				</td></tr>
				<?php
				em_options_radio_binary ( sprintf(_x( 'Enable %s?', 'Enable a feature in settings page', 'em-pro' ), __('Event Email Reminders','em-pro')), 'dbem_cron_emails','');
				em_options_input_text ( __( 'Days before reminder', 'dbem' ), 'dbem_emp_emails_reminder_days',__('You can choose to send people attending your event x days before the event starts. Minimum is one day.'), 1);
				em_options_radio_binary ( __( 'Attach ical invite?', 'dbem' ), 'dbem_emp_emails_reminder_ical',__('If using SMTP in your email settings. You can automatically attach an ical file which some email clients (e.g. gmail) will render as an invitation they can add to their calendar.'));
				$days = get_option('dbem_emp_emails_reminder_days',1);
				?>
				<tr>
					<td><?php _e('WP Cron Time','em-pro'); ?></td>
					<td>
						<input class="em-time-input em-time-start" type="text" name="dbem_emp_emails_reminder_time" value="<?php echo get_option('dbem_emp_emails_reminder_time','12:00 AM'); ?>" /><br />
						<em><?php _e('Every day Events Manager automatically checks upcoming events in order to generate emails. You can choose at what time of day to run this check, if your site has a lot of traffic, it may help having this run at times of lower server loads.','em-pro'); ?></em>
					</td>
				</tr>
				<?php
				em_options_input_text ( __( 'Reminder subject', 'dbem' ), 'dbem_emp_emails_reminder_subject','');
				em_options_textarea ( __( 'Approved email', 'dbem' ), 'dbem_emp_emails_reminder_body','');
				?>
				<?php echo $save_button; ?>
			</table>
		</div> <!-- . inside -->
		</div> <!-- .postbox -->
	    <?php
	}
	
    /*
     * --------------------------------------------
     * Custom Event/Gateway Booking Emails
     * --------------------------------------------
     */
	
	/**
	 * Generates meta box for settings page 
	 */
	public static function custom_email_options(){
	    global $save_button;
	    ?>
		<div  class="postbox " id="em-opt-custom-emails" >
		<div class="handlediv" title="<?php __('Click to toggle', 'dbem'); ?>"><br /></div><h3><?php _e ( 'Custom Booking Email Templates', 'em-pro' ); ?> <em>(Beta)</em></h3>
		<div class="inside">
			<table class='form-table'>
				<tr><td colspan='2'>
					<p><?php _e( 'You can customize the email templates sent when users make a booking for one of your events.', 'em-pro' );  ?></p>
				</td></tr>
				<?php
				em_options_radio_binary ( sprintf(_x( 'Enable %s?', 'Enable a feature in settings page', 'em-pro' ), __('Custom Booking Email Templates','em-pro')), 'dbem_custom_emails','');
				?>
				<tbody class="dbem-js-custom-emails">
					<tr><th colspan="2"><?php __('Event Emails','em-pro'); ?></th></tr>
					<?php
					em_options_radio_binary ( __( 'Allow custom emails for events?', 'dbem' ), 'dbem_custom_emails_events',__('Allow custom booking email templates to be configured for individual events.','em-pro').' '.__('Users with the %s user capability will be able to do this when adding/editing events.','em-pro'));
					em_options_radio_binary ( __( 'Allow custom admin email addresses for events?', 'dbem' ), 'dbem_custom_emails_events_admins',__('Allow adding custom email addresses to be addded to individual events.','em-pro').' '.__('Users with the %s user capability will be able to do this when adding/editing events.','em-pro'));
					?>
					<tr><th colspan="2"><?php __('Gateway Emails','em-pro'); ?></th></tr>
					<?php
					em_options_radio_binary ( __( 'Allow custom emails for gateways?', 'dbem' ), 'dbem_custom_emails_gateways', sprintf(__('Allow administrators of this blog to configure custom booking email templates inside each %s settings page.','em-pro'),'<a href="'.admin_url('edit.php?post_type=event&page=events-manager-gateways').'">'.__('Payment Gateways','em-pro').'</a>') );
					em_options_radio_binary ( __( 'Allow custom admin email addresses for gateways?', 'dbem' ), 'dbem_custom_emails_gateways_admins', sprintf(__('Allow administrators of this blog to add additional admin email addresses for gateways inside each %s settings page.','em-pro'),'<a href="'.admin_url('edit.php?post_type=event&page=events-manager-gateways').'">'.__('Payment Gateways','em-pro').'</a>') );
					?>
				<?php echo $save_button; ?>
			</table>
		</div> <!-- . inside -->
		</div> <!-- .postbox -->
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('input:radio[name="dbem_custom_emails"]').change(function(){
					if( $('input:radio[name="dbem_custom_emails"]:checked').val() == 1 ){
						$('tbody.dbem-js-custom-emails').show();
					}else{
						$('tbody.dbem-js-custom-emails').hide();					
					}
				}).first().trigger('change');
				$('input:radio[name="dbem_custom_emails_events"], input:radio[name="dbem_custom_emails_gateways"]').change(function(){
					if( $('input:radio[name="'+this.name+'"]:checked').val() == 1 ){
						$('tr#'+this.name+'_admins_row').show();
					}else{
						$('tr#'+this.name+'_admins_row').hide();
					}
				}).filter('input:radio:checked').trigger('change');
			});
		</script>
	    <?php
	}
}
EM_Emails_Admin::init();