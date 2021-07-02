<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\MutationResolvers;

use PoPSitesWassup\NewsletterMutations\MutationResolvers\NewsletterUnsubscriptionMutationResolver;

class GravityFormsNewsletterUnsubscriptionMutationResolver extends NewsletterUnsubscriptionMutationResolver
{
    protected function validateData(&$errors, $newsletter_data)
    {
        parent::validateData($errors, $newsletter_data);

        if (empty($newsletter_data['entry-id'])) {
            $errors[] = $this->translationAPI->__('Your email is not subscribed to our newsletter.', 'gravityforms-pop-genericforms');
        }
    }

    protected function getNewsletterData($form_data)
    {
        $ret = parent::getNewsletterData($form_data);

        // Find the entry_id from the email (let's assume there is only one. If there is more than one, that is the user subscribed more than once, so will have to unsubscribe more than once. HOhohoho)
        $search_criteria = array(
            'status' => 'active',
            'field_filters' => array(
                array(
                    'key' => '1'/*POP_GENERICFORMS_NEWSLETTER_FIELDNAME_EMAIL_ID*/,
                    'value' => $form_data['email'],
                ),
            ),
        );
        $entries = \GFAPI::get_entries(\PoP_Newsletter_GFHelpers::getNewsletterFormId(), $search_criteria);
        if (!$entries) {
            return array();
        }
        $entry = $entries[0];
        $ret['entry-id'] = $entry['id'];
        return $ret;
    }

    protected function doExecute(array $newsletter_data)
    {
        return \GFAPI::delete_entry($newsletter_data['entry-id']);
    }
}
