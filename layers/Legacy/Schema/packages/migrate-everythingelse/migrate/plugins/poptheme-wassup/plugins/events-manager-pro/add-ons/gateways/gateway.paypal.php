<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class EM_Gateway_Paypal extends EM_Gateway_Online
{
    //change these properties below if creating a new gateway, not advised to change this for PayPal
    public $gateway = 'paypal';
    public $title = 'PayPal';

    /**
     * Sets up gateaway and adds relevant actions/filters
     */
    /*
    public function __construct() {
    }
    */


    /*
     * --------------------------------------------------
     * PayPal Functions - functions specific to paypal payments
     * --------------------------------------------------
     */

    /**
     * Retreive the paypal vars needed to send to the gatway to proceed with payment
     * @param EM_Booking $EM_Booking
     */
    public function getGatewayVars($EM_Booking)
    {
        $cmsService = CMSServiceFacade::getInstance();
        global $wp_rewrite, $EM_Notices;
        $notify_url = $this->get_payment_return_url();
        $paypal_vars = array(
            'business' => $cmsService->getOption('em_'. $this->gateway . "_email"),
            'cmd' => '_cart',
            'upload' => 1,
            'currency_code' => $cmsService->getOption('dbem_bookings_currency', 'USD'),
            'notify_url' =>$notify_url,
            'custom' => $EM_Booking->booking_id.':'.$EM_Booking->event_id,
            'charset' => 'UTF-8'
        );
        if ($cmsService->getOption('em_'. $this->gateway . "_lc")) {
            $paypal_vars['lc'] = $cmsService->getOption('em_'. $this->gateway . "_lc");
        }
        if (!$cmsService->getOption('dbem_bookings_tax_auto_add') && is_numeric($cmsService->getOption('dbem_bookings_tax')) && $cmsService->getOption('dbem_bookings_tax') > 0) {
            //tax only added if auto_add is disabled, since it would be added to individual ticket prices
            $paypal_vars['tax_cart'] = round($EM_Booking->get_price(false, false, false) * ($cmsService->getOption('dbem_bookings_tax')/100), 2);
        }
        if ($cmsService->getOption('em_'. $this->gateway . "_return") != "") {
            $paypal_vars['return'] = $cmsService->getOption('em_'. $this->gateway . "_return");
        }
        if ($cmsService->getOption('em_'. $this->gateway . "_cancel_return") != "") {
            $paypal_vars['cancel_return'] = $cmsService->getOption('em_'. $this->gateway . "_cancel_return");
        }
        if ($cmsService->getOption('em_'. $this->gateway . "_format_logo") !== false) {
            $paypal_vars['cpp_logo_image'] = $cmsService->getOption('em_'. $this->gateway . "_format_logo");
        }
        if ($cmsService->getOption('em_'. $this->gateway . "_border_color") !== false) {
            $paypal_vars['cpp_cart_border_color'] = $cmsService->getOption('em_'. $this->gateway . "_format_border");
        }
        $count = 1;
        foreach ($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking) {
            $price = $EM_Ticket_Booking->get_ticket()->get_price();
            if ($price > 0) {
                $paypal_vars['item_name_'.$count] = wp_kses_data($EM_Ticket_Booking->get_ticket()->name);
                $paypal_vars['quantity_'.$count] = $EM_Ticket_Booking->get_spaces();
                $paypal_vars['amount_'.$count] = $price;
                $count++;
            }
        }
        return HooksAPIFacade::getInstance()->applyFilters('em_gateway_paypal_get_paypal_vars', $paypal_vars, $EM_Booking, $this);
    }

    /**
     * gets paypal gateway url (sandbox or live mode)
     * @returns string
     */
    public function getGatewayUrl()
    {
        $cmsService = CMSServiceFacade::getInstance();
        return ($cmsService->getOption('em_'. $this->gateway . "_status") == 'test') ? 'https://www.sandbox.paypal.com/cgi-bin/webscr':'https://www.paypal.com/cgi-bin/webscr';
    }



    /**
     * Runs when PayPal sends IPNs to the return URL provided during bookings and EM setup. Bookings are updated and transactions are recorded accordingly.
     */
    public function handlePaymentReturn()
    {
        // PayPal IPN handling code
        $cmsService = CMSServiceFacade::getInstance();
        if ((isset($_POST['payment_status']) || isset($_POST['txn_type'])) && isset($_POST['custom'])) {
            //Verify IPN request
            if ($cmsService->getOption('em_'. $this->gateway . "_status") == 'live') {
                $domain = 'https://www.paypal.com/cgi-bin/webscr';
            } else {
                $domain = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            }

            $req = 'cmd=_notify-validate';
            if (!isset($_POST)) {
                $_POST = $HTTP_POST_VARS;
            }
            foreach ($_POST as $k => $v) {
                if (get_magic_quotes_gpc()) {
                    $v = stripslashes($v);
                }
                $req .= '&' . $k . '=' . urlencode($v);
            }

            @set_time_limit(60);


            //add a CA certificate so that SSL requests always go through

            HooksAPIFacade::getInstance()->addAction('http_api_curl', 'EM_Gateway_Paypal::paymentReturnLocalCaCurl', 10, 1);
            //using WP's HTTP class
            $ipn_verification_result = wp_remote_get($domain.'?'.$req);
            HooksAPIFacade::getInstance()->removeAction('http_api_curl', 'EM_Gateway_Paypal::paymentReturnLocalCaCurl', 10, 1);

            if (!is_wp_error($ipn_verification_result) && $ipn_verification_result['body'] == 'VERIFIED') {
                //log ipn request if needed, then move on
                EM_Pro::log($_POST['payment_status']." successfully received for {$_POST['mc_gross']} {$_POST['mc_currency']} (TXN ID {$_POST['txn_id']}) - Custom Info: {$_POST['custom']}", 'paypal');
            } else {
                //log error if needed, send error header and exit
                EM_Pro::log(array('IPN Verification Error', 'WP_Error'=> $ipn_verification_result, '$_POST'=> $_POST), 'paypal');

                header('HTTP/1.0 502 Bad Gateway');

                exit;
            }
            //if we get past this, then the IPN went ok

            // handle cases that the system must ignore
            $new_status = false;
            //Common variables
            $amount = $_POST['mc_gross'];
            $currency = $_POST['mc_currency'];
            $timestamp = date('Y-m-d H:i:s', strtotime($_POST['payment_date']));
            $custom_values = explode(':', $_POST['custom']);
            $booking_id = $custom_values[0];
            $event_id = !empty($custom_values[1]) ? $custom_values[1]:0;
            $EM_Booking = new EM_Booking($booking_id);
            if (!empty($EM_Booking->booking_id) && count($custom_values) == 2) {
                //booking exists
                $EM_Booking->manage_override = true; //since we're overriding the booking ourselves.
                $user_id = $EM_Booking->person_id;

                // process PayPal response
                switch ($_POST['payment_status']) {
                    case 'Partially-Refunded':
                        break;

                    case 'Completed':
                    case 'Processed':
                        // case: successful payment
                        $this->record_transaction($EM_Booking, $amount, $currency, $timestamp, $_POST['txn_id'], $_POST['payment_status'], '');

                        //get booking metadata
                        $user_data = array();
                        if (!empty($EM_Booking->booking_meta['registration']) && is_array($EM_Booking->booking_meta['registration'])) {
                            foreach ($EM_Booking->booking_meta['registration'] as $fieldid => $field) {
                                if (trim($field) !== '') {
                                    $user_data[$fieldid] = $field;
                                }
                            }
                        }
                        if ($_POST['mc_gross'] >= $EM_Booking->get_price(false, false, true) && (!$cmsService->getOption('em_'.$this->gateway.'_manual_approval', false) || !$cmsService->getOption('dbem_bookings_approval'))) {
                            $EM_Booking->approve(true, true); //approve and ignore spaces
                        } else {
                            //TODO do something if pp payment not enough
                            $EM_Booking->setStatus(0); //Set back to normal "pending"
                        }
                        HooksAPIFacade::getInstance()->doAction('em_payment_processed', $EM_Booking, $this);
                        break;

                    case 'Reversed':
                        // case: charge back
                        $note = 'Last transaction has been reversed. Reason: Payment has been reversed (charge back)';
                        $this->record_transaction($EM_Booking, $amount, $currency, $timestamp, $_POST['txn_id'], $_POST['payment_status'], $note);

                        //We need to cancel their booking.
                        $EM_Booking->cancel();
                        HooksAPIFacade::getInstance()->doAction('em_payment_reversed', $EM_Booking, $this);

                        break;

                    case 'Refunded':
                        // case: refund
                        $note = 'Last transaction has been reversed. Reason: Payment has been refunded';
                        $this->record_transaction($EM_Booking, $amount, $currency, $timestamp, $_POST['txn_id'], $_POST['payment_status'], $note);
                        if ($EM_Booking->get_price() >= $amount) {
                            $EM_Booking->cancel();
                        } else {
                            $EM_Booking->setStatus(0); //Set back to normal "pending"
                        }
                        HooksAPIFacade::getInstance()->doAction('em_payment_refunded', $EM_Booking, $this);
                        break;

                    case 'Denied':
                        // case: denied
                        $note = 'Last transaction has been reversed. Reason: Payment Denied';
                        $this->record_transaction($EM_Booking, $amount, $currency, $timestamp, $_POST['txn_id'], $_POST['payment_status'], $note);

                        $EM_Booking->cancel();
                        HooksAPIFacade::getInstance()->doAction('em_payment_denied', $EM_Booking, $this);
                        break;


                    case 'In-Progress':
                    case 'Pending':
                        // case: payment is pending
                        $pending_str = array(
                            'address' => 'Customer did not include a confirmed shipping address',
                            'authorization' => 'Funds not captured yet',
                            'echeck' => 'eCheck that has not cleared yet',
                            'intl' => 'Payment waiting for aproval by service provider',
                            'multi-currency' => 'Payment waiting for service provider to handle multi-currency process',
                            'unilateral' => 'Customer did not register or confirm his/her email yet',
                            'upgrade' => 'Waiting for service provider to upgrade the PayPal account',
                            'verify' => 'Waiting for service provider to verify his/her PayPal account',
                            'paymentreview' => 'Paypal is currently reviewing the payment and will approve or reject within 24 hours',
                            '*' => ''
                        );
                        $reason = @$_POST['pending_reason'];
                        $note = 'Last transaction is pending. Reason: ' . (isset($pending_str[$reason]) ? $pending_str[$reason] : $pending_str['*']);

                        $this->record_transaction($EM_Booking, $amount, $currency, $timestamp, $_POST['txn_id'], $_POST['payment_status'], $note);

                        HooksAPIFacade::getInstance()->doAction('em_payment_pending', $EM_Booking, $this);
                        break;

                    default:
                        // case: various error cases
                }
            } else {
                if ($_POST['payment_status'] == 'Completed' || $_POST['payment_status'] == 'Processed') {
                    $message = HooksAPIFacade::getInstance()->applyFilters('em_gateway_paypal_bad_booking_email', "
A Payment has been received by PayPal for a non-existent booking.

Event Details : %event%

It may be that this user's booking has timed out yet they proceeded with payment at a later stage.

To refund this transaction, you must go to your PayPal account and search for this transaction:

Transaction ID : %transaction_id%
Email : %payer_email%

When viewing the transaction details, you should see an option to issue a refund.

If there is still space available, the user must book again.

Sincerely,
Events Manager
					", $booking_id, $event_id);
                    if (!empty($event_id)) {
                        $EM_Event = new EM_Event($event_id);
                        $event_details = $EM_Event->name . " - " . date_i18n($cmsService->getOption('date_format'), $EM_Event->start);
                    } else {
                        $event_details = TranslationAPIFacade::getInstance()->__('Unknown', 'em-pro');
                    }
                    $message  = str_replace(array('%transaction_id%','%payer_email%', '%event%'), array($_POST['txn_id'], $_POST['payer_email'], $event_details), $message);
                    wp_mail($cmsService->getOption('em_'. $this->gateway . "_email"), TranslationAPIFacade::getInstance()->__('Unprocessed payment needs refund'), $message);
                } else {
                    //header('Status: 404 Not Found');
                    echo 'Error: Bad IPN request, custom ID does not correspond with any pending booking.';
                    //echo "<pre>"; print_r($_POST); echo "</pre>";
                    exit;
                }
            }
            //fclose($log);
        } else {
            // Did not find expected POST variables. Possible access attempt from a non PayPal site.
            //header('Status: 404 Not Found');
            echo 'Error: Missing POST variables. Identification is not possible.';
            exit;
        }
    }

    /**
     * Fixes SSL issues with wamp and outdated server installations combined with curl requests by forcing a custom pem file, generated from - http://curl.haxx.se/docs/caextract.html
     * @param resource $handle
     */
    public static function paymentReturnLocalCaCurl($handle)
    {
        curl_setopt($handle, CURLOPT_CAINFO, dirname(__FILE__).DIRECTORY_SEPARATOR.'gateway.paypal.pem');
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
        global $EM_options;
        $cmsService = CMSServiceFacade::getInstance();

        parent::mysettings();                // Change Leo ?>

        <h3><?php echo sprintf(TranslationAPIFacade::getInstance()->__('%s Options', 'em-pro'), 'PayPal'); ?></h3>
        <p><?php echo TranslationAPIFacade::getInstance()->__('<strong>Important:</strong>In order to connect PayPal with your site, you need to enable IPN on your account.');
        echo " ". sprintf(TranslationAPIFacade::getInstance()->__('Your return url is %s', 'em-pro'), '<code>'.$this->get_payment_return_url().'</code>'); ?></p>
        <p><?php echo sprintf(TranslationAPIFacade::getInstance()->__('Please visit the <a href="%s">documentation</a> for further instructions.', 'em-pro'), 'http://wp-events-plugin.com/documentation/'); ?></p>
        <table class="form-table">
        <tbody>
          <tr valign="top">
              <th scope="row"><?php _e('PayPal Email', 'em-pro') ?></th>
                  <td><input type="text" name="paypal_email" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_email")); ?>" />
                  <br />
              </td>
          </tr>
          <tr valign="top">
              <th scope="row"><?php _e('Paypal Currency', 'em-pro') ?></th>
              <td><?php echo esc_html($cmsService->getOption('dbem_bookings_currency', 'USD')); ?><br /><i><?php echo sprintf(TranslationAPIFacade::getInstance()->__('Set your currency in the <a href="%s">settings</a> page.', 'dbem'), EM_ADMIN_URL.'&amp;page=events-manager-options#bookings'); ?></i></td>
          </tr>

          <tr valign="top">
              <th scope="row"><?php _e('PayPal Language', 'em-pro') ?></th>
              <td>
                <select name="paypal_lc">
                    <option value=""><?php _e('Default', 'em-pro'); ?></option>
                  <?php
                    $ccodes = em_get_countries();
                    $paypal_lc = $cmsService->getOption('em_'.$this->gateway.'_lc', 'US');
                    foreach ($ccodes as $key => $value) {
                        if ($paypal_lc == $key) {
                            echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
                        } else {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                    } ?>

                  </select>
                  <br />
                  <i><?php _e('PayPal allows you to select a default language users will see. This is also determined by PayPal which detects the locale of the users browser. The default would be US.', 'em-pro') ?></i>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row"><?php _e('PayPal Mode', 'em-pro') ?></th>
              <td>
                  <select name="paypal_status">
                      <option value="live" <?php if ($cmsService->getOption('em_'. $this->gateway . "_status") == 'live') {
                            echo 'selected="selected"';
                                            } ?>><?php _e('Live Site', 'em-pro') ?></option>
                      <option value="test" <?php if ($cmsService->getOption('em_'. $this->gateway . "_status") == 'test') {
                            echo 'selected="selected"';
                                            } ?>><?php _e('Test Mode (Sandbox)', 'em-pro') ?></option>
                  </select>
                  <br />
              </td>
          </tr>
          <tr valign="top">
              <th scope="row"><?php _e('PayPal Page Logo', 'em-pro') ?></th>
              <td>
                <input type="text" name="paypal_format_logo" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_format_logo")); ?>" style='width: 40em;' /><br />
                <em><?php _e('Add your logo to the PayPal payment page. It\'s highly recommended you link to a https:// address.', 'em-pro'); ?></em>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row"><?php _e('Border Color', 'em-pro') ?></th>
              <td>
                <input type="text" name="paypal_format_border" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_format_border")); ?>" style='width: 40em;' /><br />
                <em><?php _e('Provide a hex value color to change the color from the default blue to another color (e.g. #CCAAAA).', 'em-pro'); ?></em>
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
            $this->gateway . "_email" => $_REQUEST[ $this->gateway.'_email' ],
            $this->gateway . "_currency" => $_REQUEST[ 'currency' ],

            $this->gateway . "_lc" => $_REQUEST[ $this->gateway.'_lc' ],
            $this->gateway . "_status" => $_REQUEST[ $this->gateway.'_status' ],
            $this->gateway . "_format_logo" => $_REQUEST[ $this->gateway.'_format_logo' ],
            $this->gateway . "_format_border" => $_REQUEST[ $this->gateway.'_format_border' ]
        );
        foreach ($gateway_options as $key => $option) {
            update_option('em_'.$key, stripslashes($option));
        }
        //default action is to return true
        return true;
    }
}
//EM_Gateways::register_gateway('paypal', 'EM_Gateway_Paypal');
//HooksAPIFacade::getInstance()->addAction('emp_paypal_cron', 'emGatewayBookingTimeout');
?>
