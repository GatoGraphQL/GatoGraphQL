<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class PoP_ThemeWassup_AE_WeeklyLatestPosts extends PoP_ThemeWassup_ProcessorAutomatedEmailsBase {
class PoP_ThemeWassup_AE_WeeklyLatestPosts extends PoP_ThemeWassup_SimpleProcessorAutomatedEmailsBase {

    public function get_page() {
        
        return POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY;
    }
    
    protected function get_users() {
        
        return PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILDIGESTS_WEEKLYLATESTPOSTS);
    }

    protected function get_subject() {
        
        return sprintf(
            __('Latest content — %s', 'poptheme-wassup-automatedemails'),
            date(get_option('date_format'))
        );
    }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_AE_WeeklyLatestPosts();
