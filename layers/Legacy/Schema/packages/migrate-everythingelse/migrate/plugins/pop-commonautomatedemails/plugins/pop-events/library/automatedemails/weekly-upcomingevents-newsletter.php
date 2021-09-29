<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

class PoPTheme_Wassup_EM_AE_NewsletterWeeklyUpcomingEvents extends PoPTheme_Wassup_AE_NewsletterRecipientsBase
{
    public function getRoute()
    {
        return POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY;
    }

    protected function getSubject()
    {
        $cmsService = CMSServiceFacade::getInstance();
        return sprintf(
            TranslationAPIFacade::getInstance()->__('Upcoming events — %s', 'pop-commonautomatedemails'),
            date($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')))
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_AE_NewsletterWeeklyUpcomingEvents();
