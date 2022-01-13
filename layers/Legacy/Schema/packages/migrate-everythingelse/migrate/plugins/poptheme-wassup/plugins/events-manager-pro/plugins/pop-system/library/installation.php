<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_EM_Installation
{
    public function __construct()
    {

        // Add Events Manager Pro Bank Transfer Gateway options
        \PoP\Root\App::getHookManager()->addAction('PoP:system-build', 'addOptions');
    }

    public function addOptions()
    {

        //banktransfer
        add_option('em_banktransfer_option_name', TranslationAPIFacade::getInstance()->__('Bank Transfer', 'poptheme-wassup'));
        add_option('em_banktransfer_booking_feedback', TranslationAPIFacade::getInstance()->__('Booking successful.', 'dbem'));
        add_option('em_banktransfer_button', TranslationAPIFacade::getInstance()->__('Bank Transfer', 'poptheme-wassup'));
        add_option('em_banktransfer_form_message', TranslationAPIFacade::getInstance()->__('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Once the payment has been done, please send an email to <a href="mailto:info@greendrinks.cn?subject=Bank Transfer">info@greendrinks.cn</a> with the Order ID and the Event Booking will be confirmed.', 'poptheme-wassup'));
        add_option('em_banktransfer_form_account_details', TranslationAPIFacade::getInstance()->__('Account details', 'poptheme-wassup'));
        
        // Alipay
        add_option('em_alipay_option_name', TranslationAPIFacade::getInstance()->__('Alipay', 'poptheme-wassup'));
        add_option('em_alipay_form', '<p>'.TranslationAPIFacade::getInstance()->__("Pay via Alipay, if you don't have an Alipay account, you can also pay with your debit card or credit card", 'poptheme-wassup').'</p><img src="'.POPPROCESSORS_URI_LIB.'/plugins/events-manager-pro/includes/images/alipay/alipay.gif" width="135" height="45" />');
        add_option('em_alipay_booking_feedback', TranslationAPIFacade::getInstance()->__('Please wait whilst you are redirected to Alipay to proceed with payment.', 'em-pro'));
        add_option('em_alipay_booking_feedback_free', TranslationAPIFacade::getInstance()->__('Booking successful.', 'dbem'));
        add_option('em_alipay_button', POPPROCESSORS_URI_LIB.'/plugins/events-manager-pro/includes/images/alipay/alipaybutton.jpg');
        add_option('em_alipay_booking_feedback_thanks', TranslationAPIFacade::getInstance()->__('Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you along with a seperate email containing account details to access your booking information on this site. You may log into your account at www.paypal.com to view details of this transaction.', 'em-pro'));
        
        //offline
        add_option('em_offline_form', '<p>'.TranslationAPIFacade::getInstance()->__("Simply come and pay at the Event ;)", 'poptheme-wassup').'</p>');
        
        // Add Select options
        add_option('dbem_gateway_use_please_select', 0);
        add_option('dbem_gateway_please_select_label', TranslationAPIFacade::getInstance()->__('Please select', 'em-pro'));
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_Installation();
