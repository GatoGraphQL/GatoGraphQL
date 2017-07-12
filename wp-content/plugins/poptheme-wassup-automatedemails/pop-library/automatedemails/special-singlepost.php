<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ThemeWassup_AE_SpecialSinglePost extends PoP_ThemeWassup_SimpleProcessorAutomatedEmailsBase {

    public function get_page() {
        
        return POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_SINGLEPOST_SPECIAL;
    }
    
    protected function get_recipients() {
        
        return PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILDIGESTS_SPECIALPOSTS);
    }

    protected function get_subject() {
        
        // The post id is passed through param pid
        return get_the_title($_REQUEST['pid']);
    }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_AE_SpecialSinglePost();
