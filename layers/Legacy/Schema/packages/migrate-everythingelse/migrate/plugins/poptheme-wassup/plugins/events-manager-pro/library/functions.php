<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

// Remove Events Manager Pro "Further information" in the My profile page
HooksAPIFacade::getInstance()->removeAction('show_user_profile', array('EM_User_Fields','show_profile_fields'), 1);
HooksAPIFacade::getInstance()->removeAction('edit_user_profile', array('EM_User_Fields','show_profile_fields'), 1);


HooksAPIFacade::getInstance()->addFilter('em_booking_output_placeholder', 'gdEmBookingOutputPlaceholder', 10, 4);
function gdEmBookingOutputPlaceholder($result, $EM_Booking, $placeholder, $target = 'html')
{
    $cmsService = CMSServiceFacade::getInstance();
    if ($placeholder == "#_GATEWAYINFORMATION" && !empty($EM_Booking->booking_meta['gateway'])) {
        $result = TranslationAPIFacade::getInstance()->__($cmsService->getOption('em_'. $EM_Booking->booking_meta['gateway'] . "_form"), 'poptheme-wassup');
    }

    return $result;
}

/**
 * Order the Gateway Payments
 */
/*
HooksAPIFacade::getInstance()->addFilter('em_payment_gateways', 'gdEmPaymentGatewaysReorderGateways');
function gdEmPaymentGatewaysReorderGateways($gateways) {

    $ordered_gateways = array();
    $order = array("alipay", "paypal", "banktransfer", "offline");
    foreach ($order as $gateway) {

        if ($gateways[$gateway])
            $ordered_gateways[$gateway] = $gateways[$gateway];
    }

    return $ordered_gateways;
}
*/

/**
 * Order the Gateway Payments
 */

HooksAPIFacade::getInstance()->addAction('after_setup_theme', 'gdPluginsLoadedReorderGateways');
function gdPluginsLoadedReorderGateways()
{
    global $EM_Gateways;

    $ordered_gateways = array();
    $order = array("alipay", "paypal", "banktransfer", "offline");
    foreach ($order as $gateway) {
        if ($EM_Gateways[$gateway]) {
            $ordered_gateways[$gateway] = $EM_Gateways[$gateway];
        }
    }

    $EM_Gateways = $ordered_gateways;
}


HooksAPIFacade::getInstance()->addFilter('emp_forms_output_field', 'gdEmpFormsOutputFieldTranslateLabel', 10, 3);
function gdEmpFormsOutputFieldTranslateLabel($content, $EM_Form, $field)
{
    $content = str_replace($field['label'], HooksAPIFacade::getInstance()->applyFilters("gd_translate", $field['label']), $content);

    return $content;
}

/**
 * Coupons or Booking form must not be shown on the webplatform. So take away if not in back-end
 */
if (!is_admin()) {
    // Remove Coupons from My Events
    HooksAPIFacade::getInstance()->removeAction('em_events_admin_bookings_footer', array('EM_Coupons', 'admin_meta_box'), 20, 1);
    HooksAPIFacade::getInstance()->removeAction('em_events_admin_bookings_footer', array('EM_Booking_Form', 'event_bookings_meta_box'), 20, 1);

    // Gateway Offline
    global $EM_Gateways;
    if ($EM_Gateways && $EM_Gateways['offline']) {
        HooksAPIFacade::getInstance()->removeAction('em_admin_event_booking_options_buttons', array($EM_Gateways['offline'], 'event_booking_options_buttons'), 10);
        HooksAPIFacade::getInstance()->removeAction('em_admin_event_booking_options', array($EM_Gateways['offline'], 'event_booking_options'), 10);
        HooksAPIFacade::getInstance()->removeAction('em_bookings_single_metabox_footer', array($EM_Gateways['offline'], 'add_payment_form'), 1, 1); //add payment to booking
    }

    // Remove options to export or filter
    HooksAPIFacade::getInstance()->removeFilter('em_bookings_table_cols_template', array('EM_Booking_Form','em_bookings_table_cols_template'), 10, 2);
    HooksAPIFacade::getInstance()->removeAction('em_bookings_table_cols_template', array('EM_Coupons', 'em_bookings_table_cols_template'), 10, 1);

    global $EM_Gateways_Transactions;
    if ($EM_Gateways_Transactions) {
        HooksAPIFacade::getInstance()->removeFilter('em_bookings_table_cols_template', array($EM_Gateways_Transactions, 'em_bookings_table_cols_template'), 10, 2);
    }
}
