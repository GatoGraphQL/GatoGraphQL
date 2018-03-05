<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ThemeWassup_AE_NewsletterRecipientsBase extends PoP_ThemeWassup_SimpleProcessorAutomatedEmailsBase {

    protected function get_recipients() {

        // Allow Gravity Forms to hook the recipients in
        return apply_filters('PoP_ThemeWassup_AE_NewsletterRecipientsBase:recipients', array());
    }

    protected function get_frame() {
        
        return GD_EMAIL_FRAME_NEWSLETTER;
    }
}