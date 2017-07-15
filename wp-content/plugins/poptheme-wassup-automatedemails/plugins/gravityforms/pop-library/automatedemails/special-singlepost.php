<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ThemeWassup_AE_GF_SpecialSinglePost extends PoP_ThemeWassup_AE_GF_NewsletterRecipientsBase {

    public function get_page() {
        
        return POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_SINGLEPOST_SPECIAL;
    }

    protected function get_subject() {
        
        // The post id is passed through param pid
        return get_the_title($_REQUEST['pid']);
    }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_AE_GF_SpecialSinglePost();
