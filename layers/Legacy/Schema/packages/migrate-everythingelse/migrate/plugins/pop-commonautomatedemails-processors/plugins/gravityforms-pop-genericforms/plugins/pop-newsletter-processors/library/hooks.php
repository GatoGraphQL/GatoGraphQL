<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_AE_GF_NewsletterRecipientsHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoPTheme_Wassup_AE_NewsletterRecipientsBase:recipients',
            array($this, 'getRecipients')
        );
    }

    public function getRecipients($recipients)
    {
        if ($form_id = PoP_Newsletter_GFHelpers::getNewsletterFormId()) {
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
                    'email' => rgar($entry, '1'/*POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_EMAIL_ID*/),
                    'name' => rgar($entry, '2'/*POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_NAME_ID*/),
                );
            }
        }
        
        return $recipients;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AE_GF_NewsletterRecipientsHooks();
