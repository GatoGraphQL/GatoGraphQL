<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ThemeWassup_AE_NewsletterWeeklyLatestPosts extends PoP_ThemeWassup_AE_NewsletterRecipientsBase {

    public function get_page() {
        
        return POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY;
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
new PoP_ThemeWassup_AE_NewsletterWeeklyLatestPosts();
