<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

require_once "gateway.alipay.core.php";

if (class_exists("EM_Gateway_Online")) {
    class EM_Gateway_Alipay extends EM_Gateway_Online
    {
        //change these properties below if creating a new gateway, not advised to change this for PayPal
        public $gateway = 'alipay';
        public $title = 'Alipay';


        /*
        * --------------------------------------------------
        * Alipay Functions - functions specific to paypal payments
        * --------------------------------------------------
        */

        /**
         * Retrieve the alipay vars needed to send to the gatway to proceed with payment
         *
         * @param EM_Booking $EM_Booking
         */
        public function getGatewayVars($EM_Booking)
        {
            $cmsService = CMSServiceFacade::getInstance();
            /*
            // Payment method: 'direct' (if also escrow or dualfun are allowed, uncomment these lines

            // Obtain the Service type from the Payment method
            $payment_method = $cmsService->getOption('em_'.$this->gateway.'_payment_method');
            $services = array(
              'direct'     => 'create_direct_pay_by_user',
              'dualfun'     => 'trade_create_by_buyer',
              'escrow'     => 'create_partner_trade_by_buyer'
            );
            $service = $services[$payment_method];
            */


            $alipay_vars = array(
                "service"            => 'create_direct_pay_by_user',
                "payment_type"        => '1',

                "partner"            => trim($cmsService->getOption('em_'. $this->gateway . "_partner_id")),
                "seller_email"        => trim($cmsService->getOption('em_'. $this->gateway . "_alipay_account")),
                "return_url"        => $cmsService->getOption('em_'. $this->gateway . "_return"),
                "notify_url"        => $this->get_payment_return_url(),

                "_input_charset"    => 'utf-8',

                "out_trade_no"        => $EM_Booking->booking_id.':'.$EM_Booking->event_id,
                "subject"            => $EM_Booking->output('#_BOOKINGNAME'),
                "body"                => $EM_Booking->output('#_BOOKINGTICKETNAME : #_BOOKINGTICKETDESCRIPTION'),
                "price"                => $EM_Booking->get_price(),
                "quantity"            => $EM_Booking->output('#_BOOKINGSPACES')
            );

            $mysign = $this->buildSign($alipay_vars);
            $alipay_vars['sign'] = $mysign;
            $alipay_vars['sign_type'] = 'MD5';

            return \PoP\Root\App::applyFilters('em_gateway_alipay_get_paypal_vars', $alipay_vars, $EM_Booking, $this);
        }

        public function buildSign($vars)
        {

            // Filter and sort
            $vars = paraFilter($vars);
            $vars = argSort($vars);

            // Encrypt the variables using the security key
            $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
            $key = trim($cmsService->getOption('em_'. $this->gateway . "_security_key"));
            $mysign = buildMysign($vars, $key, 'MD5');

            return $mysign;
        }


        /**
         * gets paypal gateway url (sandbox or live mode)
         *
         * @returns string
         */
        public function getGatewayUrl()
        {
            return 'https://mapi.alipay.com/gateway.do';
        }



        /**
         * Runs when Alipay sends IPNs to the return URL provided during bookings and EM setup. Bookings are updated and transactions are recorded accordingly.
         */
        public function handlePaymentReturn()
        {
            // Alipay IPN handling code

            $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
            if (isset($_POST['seller_id']) && trim($_POST['seller_id']) == trim($cmsService->getOption('em_'. $this->gateway . "_partner_id"))) {
                EM_Pro::log('Received notification from Alipay, booking ID and event ID are '.$_POST['out_trade_no'], $this->gateway);
                //@ob_clean();

                //Verify alipay's notification
                $mysign = $this->buildSign($_POST);

                $responseTxt = '';
                if (!empty($_POST["notify_id"])) {
                    $veryfy_url = 'http://notify.alipay.com/trade/notify_query.do?';
                    $veryfy_url .= "partner=" . $cmsService->getOption('em_'. $this->gateway . "_partner_id");
                    $verify_url .= "&notify_id=" . $_POST["notify_id"];
                    $responseTxt = getHttpResponse($veryfy_url);
                }

                EM_Pro::log("[verifyNotify] responseTxt=".$responseTxt."\n notify_url_log:sign=".$_POST["sign"]."&mysign=".$mysign.",".createLinkString($_POST), $this->gateway);

                $verification_success = preg_match("/true$/i", $responseTxt) && $mysign == $_POST["sign"];

                if (!$verification_success) {
                    EM_Pro::log(array('IPN Verification Error', '$_POST'=> $_POST), $this->gateway);
                    header('HTTP/1.0 502 Bad Gateway');
                    exit;
                }

                //if we get past this, then the IPN went ok

                // handle cases that the system must ignore
                $new_status = false;
                //Common variables
                $amount = $_POST['price'];
                $currency = '';
                $timestamp = date('Y-m-d H:i:s');
                $custom_values = explode(':', $_POST['out_trade_no']);
                $booking_id = $custom_values[0];
                $event_id = !empty($custom_values[1]) ? $custom_values[1]:0;
                $EM_Booking = new EM_Booking($booking_id);

                if (!empty($EM_Booking->booking_id) && count($custom_values) == 2) {
                    //booking exists
                    $EM_Booking->manage_override = true; //since we're overriding the booking ourselves.
                    $user_id = $EM_Booking->person_id;

                    // process Alipay response
                    if ($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                        // case: successful payment
                        $this->record_transaction($EM_Booking, $amount, $currency, $timestamp, '', $_POST['trade_status'], '');

                        //get booking metadata
                        $user_data = array();
                        if (!empty($EM_Booking->booking_meta['registration']) && is_array($EM_Booking->booking_meta['registration'])) {
                            foreach ($EM_Booking->booking_meta['registration'] as $fieldid => $field) {
                                if (trim($field) !== '') {
                                    $user_data[$fieldid] = $field;
                                }
                            }
                        }
                        if ($amount >= $EM_Booking->get_price(false, false, true) && (!$cmsService->getOption('em_'.$this->gateway.'_manual_approval', false) || !$cmsService->getOption('dbem_bookings_approval'))) {
                            $EM_Booking->approve(true, true); //approve and ignore spaces
                        } else {
                            //TODO do something if pp payment not enough
                            $EM_Booking->setStatus(0); //Set back to normal "pending"
                        }
                        \PoP\Root\App::doAction('em_payment_processed', $EM_Booking, $this);
                    }
                } else {
                    if ($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                        $message = \PoP\Root\App::applyFilters(
                            'em_gateway_alipay_bad_booking_email',
                            "
	A Payment has been received by Alipay for a non-existent booking.

	Event Details : %event%

	It may be that this user's booking has timed out yet they proceeded with payment at a later stage.

	To refund this transaction, you must go to your Alipay account and search for this transaction:

	Email : %seller_email%

	When viewing the transaction details, you should see an option to issue a refund.

	If there is still space available, the user must book again.

	Sincerely,
	Events Manager
						",
                            $booking_id,
                            $event_id
                        );
                        if (!empty($event_id)) {
                            $EM_Event = new EM_Event($event_id);
                            $event_details = $EM_Event->name . " - " . date_i18n($cmsService->getOption('date_format'), $EM_Event->start);
                        } else {
                            $event_details = TranslationAPIFacade::getInstance()->__('Unknown', 'poptheme-wassup');
                        }
                        $message  = str_replace(array('%seller_email%', '%event%'), array($_POST['seller_email'], $event_details), $message);
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
                // Did not find expected POST variables. Possible access attempt from a non Alipay site.
                //header('Status: 404 Not Found');
                echo 'Error: Missing POST variables. Identification is not possible.';
                exit;
            }
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
            $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

            parent::mysettings(); ?>

            <h3><?php echo sprintf(TranslationAPIFacade::getInstance()->__('%s Options', 'poptheme-wassup'), $this->title); ?></h3>
            <p><?php echo TranslationAPIFacade::getInstance()->__('<strong>Important:</strong>In order to connect Alipay with your site, you need to configure the return URL.');
            echo " ". sprintf(TranslationAPIFacade::getInstance()->__('Your return url is %s', 'poptheme-wassup'), '<code>'.$this->get_payment_return_url().'</code>'); ?></p>
            <p><?php echo sprintf(TranslationAPIFacade::getInstance()->__('Please visit the <a href="%s">documentation</a> for further instructions.', 'poptheme-wassup'), 'http://wp-events-plugin.com/documentation/'); ?></p>
            <table class="form-table">
            <tbody>
              <tr valign="top">
                  <th scope="row"><?php _e('Alipay account', 'poptheme-wassup') ?></th>
                      <td><input type="text" name="<?php echo $this->gateway ?>_account" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_account")); ?>" style="width: 40em;"/>
                      <br />
                      <i><?php _e('Please enter your Alipay Email; this is needed in order to take payment.', 'alipay') ?></i>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Alipay partner ID', 'poptheme-wassup') ?></th>
                      <td><input type="text" name="<?php echo $this->gateway ?>_partner_id" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_partner_id")); ?>" style="width: 40em;"/>
                      <br />
                      <i><?php _e('Please enter the partner ID. If you don\'t have one, <a href="https://b.alipay.com/newIndex.htm" target="_blank">click here</a> to get.', 'alipay') ?></i>
                  </td>
              </tr>
              <tr valign="top">
                  <th scope="row"><?php _e('Alipay security key', 'poptheme-wassup') ?></th>
                      <td><input type="text" name="<?php echo $this->gateway ?>_security_key" value="<?php esc_attr_e($cmsService->getOption('em_'. $this->gateway . "_security_key")); ?>" style="width: 40em;"/>
                      <br />
                      <i><?php _e('Please enter the security key. If you don\'t have one, <a href="https://b.alipay.com/newIndex.htm" target="_blank">click here</a> to get.', 'alipay') ?></i>
                  </td>
              </tr>
              <!--tr valign="top">
                  <th scope="row"><?php _e('Payment method', 'poptheme-wassup') ?></th>
                  <td>
                    <select name="<?php echo $this->gateway ?>_payment_method">
            <?php
            $payment_method = $cmsService->getOption('em_'.$this->gateway.'_payment_method');
            $options = array(    'direct' => 'Direct Payment',
                'escrow' => 'Escrow Payment',
                'dualfun' => 'Dual (Direct Payment + Escrow payment)'
            );
            foreach ($options as $key => $value) {
                $selected = ($key == $payment_method) ? ' selected="selected"' : '';
                echo '<option value="'.$key.'"'.$selected.'>'.TranslationAPIFacade::getInstance()->__($value, 'poptheme-wassup').'</option>';
            } ?>
                      </select>
                      <br />
                  </td>
              </tr-->
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
                $this->gateway . "_account" => $_REQUEST[ $this->gateway.'_account' ],
                $this->gateway . "_partner_id" => $_REQUEST[ $this->gateway.'_partner_id' ],
                $this->gateway . "_security_key" => $_REQUEST[ $this->gateway.'_security_key' ]

                // payment_method: always direct, so this is commented
                // $this->gateway . "_payment_method" => $_REQUEST[ $this->gateway.'_payment_method' ]

            );
            foreach ($gateway_options as $key => $option) {
                update_option('em_'.$key, stripslashes($option));
            }
            //default action is to return true
            return true;
        }
    }
    EM_Gateways::register_gateway('alipay', 'EM_Gateway_Alipay');

    //\PoP\Root\App::addAction('emp_alipay_cron', 'emGatewayBookingTimeout');
}
?>
