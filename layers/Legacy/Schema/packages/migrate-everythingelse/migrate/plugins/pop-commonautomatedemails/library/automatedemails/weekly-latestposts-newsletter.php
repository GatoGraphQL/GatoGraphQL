<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_AE_NewsletterWeeklyLatestContent extends PoPTheme_Wassup_AE_NewsletterRecipientsBase
{
    public function getRoute()
    {
        return POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY;
    }

    protected function getSubject()
    {
        $cmsService = CMSServiceFacade::getInstance();
        return sprintf(
            TranslationAPIFacade::getInstance()->__('Latest content — %s', 'pop-commonautomatedemails'),
            date($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')))
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AE_NewsletterWeeklyLatestContent();
