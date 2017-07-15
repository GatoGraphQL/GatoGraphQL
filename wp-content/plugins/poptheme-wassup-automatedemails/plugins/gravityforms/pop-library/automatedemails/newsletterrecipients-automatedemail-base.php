<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Automated Emails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ThemeWassup_AE_GF_NewsletterRecipientsBase extends PoP_ThemeWassup_SimpleProcessorAutomatedEmailsBase {

    protected function get_recipients() {

        $recipients = array();
        if ($form_id = GD_Template_Helper_GFForm::get_newsletter_form_id()) {

            // API documentation: https://www.gravityhelp.com/documentation/article/api-functions/#get_entries
            // First get the number of users (yeah, gotta do this, passing 0 or -1 doesn't work, and nothing caps to 20)
            $search_criteria = array(
                'status' => 'active',
            );
            $users_count = GFAPI::count_entries($form_id, $search_criteria);

            // Bring all active results, no limit.
            $paging = array(
                'offset' => 0, 
                'page_size' => $users_count,
            );
            $entries = GFAPI::get_entries($form_id, $search_criteria, null, $paging);
            foreach ($entries as $entry) {

                // Function rgar is so weird, instead of passing the name of the input, must pass the number under which they are saved
                // Then, debugging, I found out that '1' is email and '2' is name...
                $recipients[] = array(
                    'email' => rgar($entry, '1'/*POPTHEME_WASSUP_GF_NEWSLETTER_FIELDNAME_EMAIL_ID*/),
                    'name' => rgar($entry, '2'/*POPTHEME_WASSUP_GF_NEWSLETTER_FIELDNAME_NAME_ID*/),
                );
            }
        }
        
        return $recipients;
    }

    protected function get_frame() {
        
        return GD_EMAIL_FRAME_NEWSLETTER;
    }
}