<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * This class is a parent class which online gateways may extend. There are various variables and functions that are automatically taken care of by
 * EM_Gateway_Online, which will reduce redundant code and unecessary errors across all online gateways. You can override any function you want on your gateway,
 * but it's advised you read through before doing so.
 */
if (class_exists("EM_Gateway")) {
    class EM_Gateway_Online extends EM_Gateway
    {
        //change these properties below if creating a new gateway, not advised to change this for PayPal
        public $gateway;
        public $title;
        public $status = 4;
        public $status_txt = 'Awaiting Online Payment';        // Change Leo
        public $button_enabled = true;
        public $payment_return = true;
        public $count_pending_spaces = false;

        /**
         * Sets up gateaway and adds relevant actions/filters
         */
        public function __construct()
        {
            //Booking Interception
            $cmsService = CMSServiceFacade::getInstance();
            if ($this->is_active() && absint($cmsService->getOption('em_'.$this->gateway.'_booking_timeout')) > 0) {
                $this->count_pending_spaces = true;
            }
            parent::__construct();
            $this->status_txt = sprintf(TranslationAPIFacade::getInstance()->__('Awaiting %s Payment', 'poptheme-wassup'), TranslationAPIFacade::getInstance()->__($this->title, 'poptheme-wassup'));            // Change Leo
            $cron = 'emp_'.$this->gateway.'_cron';            // Change Leo
            if ($this->is_active()) {
                HooksAPIFacade::getInstance()->addAction('em_gateway_js', array($this,'emGatewayJs'));
                //Gateway-Specific
                HooksAPIFacade::getInstance()->addAction('em_template_my_bookings_header', array($this,'sayThanks')); //say thanks on my_bookings page
                HooksAPIFacade::getInstance()->addFilter('em_bookings_table_booking_actions_4', array($this,'bookingsTableActions'), 1, 2);
                HooksAPIFacade::getInstance()->addFilter('em_my_bookings_booking_actions', array($this,'emMyBookingsBookingActions'), 1, 2);
                //set up cron
                if (has_action($cron, 'em_gateway_booking_timeout')) {             // Change Leo
                    $timestamp = wp_next_scheduled($cron);
                    if (absint($cmsService->getOption('em_'.$this->gateway.'_booking_timeout')) > 0 && !$timestamp) {
                        $result = wp_schedule_event(time(), 'em_minute', $cron, array('gateway' => $this->gateway));             // Change Leo
                    } elseif (!$timestamp) {
                        wp_unschedule_event($timestamp, $cron);
                    }
                }
            } else {
                if (has_action($cron, 'em_gateway_booking_timeout')) {             // Change Leo
                    //unschedule the cron
                    $timestamp = wp_next_scheduled($cron);
                    wp_unschedule_event($timestamp, $cron);
                }
            }
        }


        /*
        * --------------------------------------------------
        * Booking Interception - functions that modify booking object behaviour
        * --------------------------------------------------
        */

        /**
         * Intercepts return data after a booking has been made and adds paypal vars, modifies feedback message.
         *
         * @param  array      $return
         * @param  EM_Booking $EM_Booking
         * @return array
         */
        public function bookingFormFeedback($return, $EM_Booking = false)
        {
            //Double check $EM_Booking is an EM_Booking object and that we have a booking awaiting payment.
            $cmsService = CMSServiceFacade::getInstance();
            if (is_object($EM_Booking) && $this->uses_gateway($EM_Booking)) {
                if (!empty($return['result']) && $EM_Booking->get_price() > 0 && $EM_Booking->booking_status == $this->status) {
                    $return['message'] = $cmsService->getOption('em_'.$this->gateway.'_booking_feedback');
                    $gateway_url = $this->getGatewayUrl();                // Change Leo
                    $gateway_vars = $this->getGatewayVars($EM_Booking);                    // Change Leo
                    $gateway_return = array('gateway_url'=>$gateway_url, 'gateway_vars'=>$gateway_vars);            // Change Leo
                    $return = array_merge($return, $gateway_return);
                } else {
                    //returning a free message
                    $return['message'] = $cmsService->getOption('em_'.$this->gateway.'_booking_feedback_free');
                }
            }
            return $return;
        }


        /**
         * Called if AJAX isn't being used, i.e. a javascript script failed and forms are being reloaded instead.
         *
         * @param  string $feedback
         * @return string
         */
        public function bookingFormFeedbackFallback($feedback)
        {
            global $EM_Booking;
            if (is_object($EM_Booking)) {
                $feedback .= "<br />" . sprintf(TranslationAPIFacade::getInstance()->__('To finalize your booking, please click the following button to proceed to %s.', 'em-pro'), $this->title) . $this->emMyBookingsBookingActions('', $EM_Booking);            // Change Leo
            }
            return $feedback;
        }

        /**
         * Triggered by the em_booking_add_yourgateway action, hooked in EM_Gateway. Overrides EM_Gateway to account for non-ajax bookings (i.e. broken JS on site).
         *
         * @param EM_Event   $EM_Event
         * @param EM_Booking $EM_Booking
         * @param boolean    $post_validation
         */
        public function bookingAdd($EM_Event, $EM_Booking, $post_validation = false)
        {
            parent::bookingAdd($EM_Event, $EM_Booking, $post_validation);
            if (!defined('DOING_AJAX')) { //we aren't doing ajax here, so we should provide a way to edit the $EM_Notices ojbect.
                HooksAPIFacade::getInstance()->addAction('option_dbem_booking_feedback', array($this, 'bookingFormFeedbackFallback'));
            }
        }

        /*
        * --------------------------------------------------
        * Booking UI - modifications to booking pages and tables containing paypal bookings
        * --------------------------------------------------
        */

        /**
         * Instead of a simple status string, a resume payment button is added to the status message so user can resume booking from their my-bookings page.
         *
         * @param  string     $message
         * @param  EM_Booking $EM_Booking
         * @return string
         */
        public function emMyBookingsBookingActions($message, $EM_Booking)
        {
            global $wpdb;
            if ($this->uses_gateway($EM_Booking) && $EM_Booking->booking_status == $this->status) {
                //first make sure there's no pending payments
                $pending_payments = $wpdb->get_var('SELECT COUNT(*) FROM '.EM_TRANSACTIONS_TABLE. " WHERE booking_id='{$EM_Booking->booking_id}' AND transaction_gateway='{$this->gateway}' AND transaction_status='Pending'");
                if ($pending_payments == 0) {
                    //user owes money!
                    $gateway_vars = $this->getGatewayVars($EM_Booking);            // Change Leo
                    $form = '<form action="'.$this->getGatewayUrl().'" method="post">';            // Change Leo
                    foreach ($gateway_vars as $key => $value) {
                        $form .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
                    }
                    $form .= '<input type="submit" value="'.TranslationAPIFacade::getInstance()->__('Resume Payment', 'em-pro').'">';
                    $form .= '</form>';
                    $message .= $form;
                }
            }
            return $message;
        }

        /**
         * Outputs extra custom content e.g. the PayPal logo by default.
         */
        public function bookingForm()
        {
            $cmsService = CMSServiceFacade::getInstance();
            echo $cmsService->getOption('em_'.$this->gateway.'_form');
        }

        /**
         * Outputs some JavaScript during the emGatewayJs action, which is run inside a script html tag, located in gateways/gateway.paypal.js
         */
        public function emGatewayJs()
        {
            // Change Leo
            $file = dirname(__FILE__).'/gateway.'.$this->gateway.'.js';
            if (file_exists($file)) {
                include $file;
            }
        }

        /**
         * Adds relevant actions to booking shown in the bookings table
         *
         * @param EM_Booking $EM_Booking
         */
        public function bookingsTableActions($actions, $EM_Booking)
        {
            return array(
                'approve' => '<a class="em-bookings-approve em-bookings-approve-offline" href="'.em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'bookings_approve', 'booking_id'=>$EM_Booking->booking_id)).'">'.TranslationAPIFacade::getInstance()->__('Approve', 'dbem').'</a>',
                'delete' => '<span class="trash"><a class="em-bookings-delete" href="'.em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id)).'">'.TranslationAPIFacade::getInstance()->__('Delete', 'dbem').'</a></span>',
                'edit' => '<a class="em-bookings-edit" href="'.em_add_get_params($EM_Booking->getEvent()->get_bookings_url(), array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null)).'">'.TranslationAPIFacade::getInstance()->__('Edit/View', 'dbem').'</a>',
            );
        }

        /*
        * --------------------------------------------------
        * Gateway Specific Functions - these functions MUST be overridden by each online gateway class
        * --------------------------------------------------
        */

        /**
         * Retreive the paypal vars needed to send to the gatway to proceed with payment
         *
         * @param EM_Booking $EM_Booking
         */
        public function getGatewayVars($EM_Booking)
        {
        }

        /**
         * gets paypal gateway url (sandbox or live mode)
         *
         * @returns string
         */
        public function getGatewayUrl()
        {
        }


        public function sayThanks()
        {
            $cmsService = CMSServiceFacade::getInstance();
            if ($_REQUEST['thanks'] == 1) {
                echo "<div class='em-booking-message em-booking-message-success'>".$cmsService->getOption('em_'.$this->gateway.'_booking_feedback_thanks').'</div>';
            }
        }

        /**
         * Runs when PayPal sends IPNs to the return URL provided during bookings and EM setup. Bookings are updated and transactions are recorded accordingly.
         */
        public function handlePaymentReturn()
        {
        }



        /*
        * --------------------------------------------------
        * Gateway Settings Functions
        * --------------------------------------------------
        */

        /**
         * Outputs custom PayPal setting fields in the settings page
         */
        public function mysettings()
        {
            // Change Leo
            $cmsService = CMSServiceFacade::getInstance();
            global $EM_options; ?>
            <table class="form-table">
            <tbody>
              <tr valign="top">
                  <th scope="row"><?php _e('Success Message', 'em-pro') ?></th>
                  <td>
                    <input type="text" name="<?php echo $this->gateway ?>_booking_feedback" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_booking_feedback")); ?>" style='width: 40em;' /><br />
                    <em><?php _e('The message that is shown to a user when a booking is successful whilst being redirected to PayPal for payment.', 'em-pro'); ?></em>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Success Free Message', 'em-pro') ?></th>
                  <td>
                    <input type="text" name="<?php echo $this->gateway ?>_booking_feedback_free" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_booking_feedback_free")); ?>" style='width: 40em;' /><br />
                    <em><?php _e('If some cases if you allow a free ticket (e.g. pay at gate) as well as paid tickets, this message will be shown and the user will not be redirected to PayPal.', 'em-pro'); ?></em>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Thank You Message', 'em-pro') ?></th>
                  <td>
                    <input type="text" name="<?php echo $this->gateway ?>_booking_feedback_thanks" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_booking_feedback_thanks")); ?>" style='width: 40em;' /><br />
                    <em><?php _e('If you choose to return users to the default Events Manager thank you page after a user has paid on PayPal, you can customize the thank you message here.', 'em-pro'); ?></em>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Return URL', 'em-pro') ?></th>
                  <td>
                    <input type="text" name="<?php echo $this->gateway ?>_return" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_return")); ?>" style='width: 40em;' /><br />
                    <em><?php _e('Once a payment is completed, users will be offered a link to this URL which confirms to the user that a payment is made. If you would to customize the thank you page, create a new page and add the link here. For automatic redirect, you need to turn auto-return on in your PayPal settings.', 'em-pro'); ?></em>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Cancel URL', 'em-pro') ?></th>
                  <td>
                    <input type="text" name="<?php echo $this->gateway ?>_cancel_return" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_cancel_return")); ?>" style='width: 40em;' /><br />
                    <em><?php _e('Whilst paying on PayPal, if a user cancels, they will be redirected to this page.', 'em-pro'); ?></em>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Delete Bookings Pending Payment', 'em-pro') ?></th>
                  <td>
                    <input type="text" name="<?php echo $this->gateway ?>_booking_timeout" style="width:50px;" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_booking_timeout")); ?>" style='width: 40em;' /> <?php _e('minutes', 'em-pro'); ?><br />
                    <em><?php _e('Once a booking is started and the user is taken to PayPal, Events Manager stores a booking record in the database to identify the incoming payment. These spaces may be considered reserved if you enable <em>Reserved unconfirmed spaces?</em> in your Events &gt; Settings page. If you would like these bookings to expire after x minutes, please enter a value above (note that bookings will be deleted, and any late payments will need to be refunded manually via PayPal).', 'em-pro'); ?></em>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Manually approve completed transactions?', 'em-pro') ?></th>
                  <td>
                    <input type="checkbox" name="<?php echo $this->gateway ?>_manual_approval" value="1" <?php echo ($cmsService->getOption('em_'. $this->gateway . "_manual_approval")) ? 'checked="checked"':''; ?> /><br />
                    <em><?php _e('By default, when someone pays for a booking, it gets automatically approved once the payment is confirmed. If you would like to manually verify and approve bookings, tick this box.', 'em-pro'); ?></em><br />
                    <em><?php echo sprintf(TranslationAPIFacade::getInstance()->__('Approvals must also be required for all bookings in your <a href="%s">settings</a> for this to work properly.', 'em-pro'), EM_ADMIN_URL.'&amp;page=events-manager-options'); ?></em>
                  </td>
              </tr>
            </tbody>
            </table>

            <?php
        }

        /*
        * Run when saving PayPal settings, saves the settings available in EM_Gateway_Paypal::mysettings()
        */
        public function update()
        {
            parent::update();
            $gateway_options = array(

                $this->gateway . "_site" => $_REQUEST[ $this->gateway.'_site' ],
                $this->gateway . "_tax" => $_REQUEST[ $this->gateway.'_button' ],
                $this->gateway . "_manual_approval" => $_REQUEST[ $this->gateway.'_manual_approval' ],
                $this->gateway . "_booking_feedback" => wp_kses_data($_REQUEST[ $this->gateway.'_booking_feedback' ]),
                $this->gateway . "_booking_feedback_free" => wp_kses_data($_REQUEST[ $this->gateway.'_booking_feedback_free' ]),
                $this->gateway . "_booking_feedback_thanks" => wp_kses_data($_REQUEST[ $this->gateway.'_booking_feedback_thanks' ]),
                $this->gateway . "_booking_timeout" => $_REQUEST[ $this->gateway.'_booking_timeout' ],
                $this->gateway . "_return" => $_REQUEST[ $this->gateway.'_return' ],
                $this->gateway . "_cancel_return" => $_REQUEST[ $this->gateway.'_cancel_return' ],
                $this->gateway . "_form" => $_REQUEST[ $this->gateway.'_form' ]
            );
            foreach ($gateway_options as $key => $option) {
                update_option('em_'.$key, stripslashes($option));
            }
            //default action is to return true
            return true;
        }
    }


    /**
     * Deletes bookings pending payment that are more than x minutes old, defined by paypal options.
     */
    function emGatewayBookingTimeout($args)
    {
        global $wpdb;
        $gateway = $args['gateway'];

        //Get a time from when to delete
        $cmsService = CMSServiceFacade::getInstance();
        $minutes_to_subtract = absint($cmsService->getOption('em_'.$gateway.'_booking_timeout'));
        if ($minutes_to_subtract > 0) {
            //get booking IDs without pending transactions
            $booking_ids = $wpdb->get_col('SELECT b.booking_id FROM '.EM_BOOKINGS_TABLE.' b LEFT JOIN '.EM_TRANSACTIONS_TABLE." t ON t.booking_id=b.booking_id  WHERE booking_date < TIMESTAMPADD(MINUTE, -{$minutes_to_subtract}, NOW()) AND booking_status=4 AND transaction_id IS NULL");
            if (count($booking_ids) > 0) {
                //first delete ticket_bookings with expired bookings
                $sql = "DELETE FROM ".EM_TICKETS_BOOKINGS_TABLE." WHERE booking_id IN (".implode(',', $booking_ids).");";
                $wpdb->query($sql);
                //then delete the bookings themselves
                $sql = "DELETE FROM ".EM_BOOKINGS_TABLE." WHERE booking_id IN (".implode(',', $booking_ids).");";
                $wpdb->query($sql);
            }
        }
    }
}
?>
