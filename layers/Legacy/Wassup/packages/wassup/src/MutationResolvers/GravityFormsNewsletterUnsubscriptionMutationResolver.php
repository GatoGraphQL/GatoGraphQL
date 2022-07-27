<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\MutationResolvers;

use GFAPI;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP_Newsletter_GFHelpers;
use PoPSitesWassup\NewsletterMutations\MutationResolvers\NewsletterUnsubscriptionMutationResolver;

class GravityFormsNewsletterUnsubscriptionMutationResolver extends NewsletterUnsubscriptionMutationResolver
{
    protected function validateData(array $newsletter_data, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore): void
    {
        parent::validateData($newsletter_data, $objectTypeFieldResolutionFeedbackStore);

        if (empty($newsletter_data['entry-id'])) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('Your email is not subscribed to our newsletter.', 'gravityforms-pop-genericforms');
        }
    }

    protected function getNewsletterData(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $ret = parent::getNewsletterData($fieldDataAccessor);

        // Find the entry_id from the email (let's assume there is only one. If there is more than one, that is the user subscribed more than once, so will have to unsubscribe more than once. HOhohoho)
        $search_criteria = array(
            'status' => 'active',
            'field_filters' => array(
                array(
                    'key' => '1'/*POP_GENERICFORMS_NEWSLETTER_FIELDNAME_EMAIL_ID*/,
                    'value' => $fieldDataAccessor->getValue('email'),
                ),
            ),
        );
        $entries = GFAPI::get_entries(PoP_Newsletter_GFHelpers::getNewsletterFormId(), $search_criteria);
        if (!$entries) {
            return array();
        }
        $entry = $entries[0];
        $ret['entry-id'] = $entry['id'];
        return $ret;
    }

    protected function doExecute(array $newsletter_data)
    {
        return GFAPI::delete_entry($newsletter_data['entry-id']);
    }
}
