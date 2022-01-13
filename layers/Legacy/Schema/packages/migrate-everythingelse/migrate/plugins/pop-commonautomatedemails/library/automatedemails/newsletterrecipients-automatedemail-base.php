<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_AE_NewsletterRecipientsBase extends PoP_SimpleProcessorAutomatedEmailsBase
{
    protected function getRecipients()
    {

        // Allow Gravity Forms to hook the recipients in
        return HooksAPIFacade::getInstance()->applyFilters('PoPTheme_Wassup_AE_NewsletterRecipientsBase:recipients', array());
    }

    protected function getFrame()
    {
        return POP_EMAILFRAME_NEWSLETTER;
    }
}
