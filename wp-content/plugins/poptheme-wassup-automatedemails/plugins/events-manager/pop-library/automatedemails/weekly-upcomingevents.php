<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class PoPTheme_Wassup_EM_AE_WeeklyUpcomingEvents extends PoP_ThemeWassup_ProcessorAutomatedEmailsBase {
class PoPTheme_Wassup_EM_AE_WeeklyUpcomingEvents extends PoP_ThemeWassup_SimpleProcessorAutomatedEmailsBase {

    public function get_page() {
        
        return POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_UPCOMINGEVENTS_WEEKLY;
    }
    
    // protected function get_block_template() {
        
    //     return GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW;
    // }
    
    protected function get_recipients() {
        
        return PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS);
    }

    protected function get_subject() {
        
        return sprintf(
            __('Upcoming events — %s', 'poptheme-wassup-automatedemails'),
            date(get_option('date_format'))
        );
    }
    
    // protected function get_content() {

    //     return '<p>Qué fenómeno!!!!</p>';
    // }
    
    // public function get_emails() {
        
    //     // Emails is an array of arrays, each of which has the following format:
    //     $ret = array();
    //     $ret[] = array(
    //         'recipients' => array(851),
    //         'subject' => 'Hola tarola',
    //         'content' => '<p>Sos locura che!</p>',
    //     );
    //     return $ret;
    // }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_EM_AE_WeeklyUpcomingEvents();
