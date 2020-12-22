<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSitesWassup\GravityFormsMutations\MutationResolverBridges\GravityFormsAddEntryToFormMutationResolverBridge;

class MutationResolverBridgeHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction(
            GravityFormsAddEntryToFormMutationResolverBridge::HOOK_FORM_FIELDNAMES,
            array($this, 'getFieldnames'),
            10,
            2
        );
    }

    public function getFieldnames($fieldnames, $form_id)
    {
        switch ($form_id) {
            case \PoP_ContactUs_GFHelpers::getContactusFormId():
                return \PoP_ContactUs_GFHelpers::getContactusFormFieldNames();

            case \PoP_ContentCreation_GFHelpers::getFlagFormId():
                return \PoP_ContentCreation_GFHelpers::getFlagFormFieldNames();

            case \PoP_Newsletter_GFHelpers::getNewsletterFormId():
                return \PoP_Newsletter_GFHelpers::getNewsletterFormFieldNames();

            case \PoP_Share_GFHelpers::getSharebyemailFormId():
                return \PoP_Share_GFHelpers::getSharebyemailFormFieldNames();

            case \PoP_SocialNetwork_GFHelpers::getContactuserFormId():
                return \PoP_SocialNetwork_GFHelpers::getContactuserFormFieldNames();

            case \PoP_Volunteering_GFHelpers::getVolunteerFormId():
                return \PoP_Volunteering_GFHelpers::getVolunteerFormFieldNames();
        }

        return $fieldnames;
    }
}
