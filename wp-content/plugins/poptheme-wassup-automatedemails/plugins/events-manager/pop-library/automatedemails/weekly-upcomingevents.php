<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_EM_AE_WeeklyUpcomingEvents extends PoP_ThemeWassup_SimpleProcessorAutomatedEmailsBase {

    public function get_page() {
        
        return POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_UPCOMINGEVENTS_WEEKLY;
    }
    
    protected function get_users() {
        
        return PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS);
    }

    protected function get_subject() {
        
        return sprintf(
            __('Upcoming events — %s', 'poptheme-wassup-automatedemails'),
            date(get_option('date_format'))
        );
    }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_EM_AE_WeeklyUpcomingEvents();
