<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// To integrate qtranslate in the Event Manager's Ajax calls
HooksAPIFacade::getInstance()->addFilter('em_wp_localize_script', 'gdEmWpLocalizeScript');
function gdEmWpLocalizeScript($em_localized_js)
{
    $em_localized_js['ajaxurl'] = admin_url('admin-ajax.php?lang='.qtranxf_getLanguage());
    $em_localized_js['bookingajaxurl'] = admin_url('admin-ajax.php?lang='.qtranxf_getLanguage());
    $em_localized_js['locationajaxurl'] = admin_url('admin-ajax.php?action=locations_search&lang='.qtranxf_getLanguage());

    return $em_localized_js;
}


// To translate the email from Event Manager
HooksAPIFacade::getInstance()->addFilter('em_booking_email_messages', 'gdEmBookingEmailMessages', 10, 2);
function gdEmBookingEmailMessages($msg, $booking)
{
    if ($msg['user'] ?? null) {
        $msg['user']['subject'] = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($msg['user']['subject']);
        $msg['user']['body'] = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($msg['user']['body']);
    }

    return $msg;
}




// Translate all the Messages before being sent by email
HooksAPIFacade::getInstance()->addFilter('em_booking_email_messages', 'gdEmBookingEmailMessagesTranslate', 10, 2);
function gdEmBookingEmailMessagesTranslate($msg, $EM_Booking = null)
{

    // User Message: translate to his language
    $msg['user'] = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($msg['user']);

    // Admin Message: translate always to the default language
    $msg['admin'] = qtranxf_useDefaultLanguage($msg['admin']);

    return $msg;
}
