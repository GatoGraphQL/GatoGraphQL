<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class PoPTheme_Wassup_AAL_AE_DailyLatestNotifications extends PoP_ThemeWassup_ProcessorAutomatedEmailsBase {
class PoPTheme_Wassup_AAL_AE_DailyLatestNotifications extends PoP_ThemeWassup_LoopUsersProcessorAutomatedEmailsBase {

    public function get_page() {
        
        return POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTNOTIFICATIONS_DAILY;
    }

    protected function get_recipients() {
        
        return PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNOTIFICATIONS);
    }

    protected function get_subject($user_id) {
        
        return sprintf(
            __('Your daily notifications — %s', 'poptheme-wassup-automatedemails'),
            date(get_option('date_format'))
        );
    }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AAL_AE_DailyLatestNotifications();
