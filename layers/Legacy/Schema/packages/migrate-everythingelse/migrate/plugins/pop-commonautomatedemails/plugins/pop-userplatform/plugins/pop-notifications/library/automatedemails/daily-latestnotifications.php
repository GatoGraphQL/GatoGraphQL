<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_AAL_AE_DailyLatestNotifications extends PoP_LoopUsersProcessorAutomatedEmailsBase
{
    public function getRoute()
    {
        return POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY;
    }

    protected function getPreferenceonUsersValue()
    {
        return POP_USERPREFERENCES_EMAILDIGESTS_DAILYNOTIFICATIONS;
    }

    protected function getSubject($user_id)
    {
        $cmsService = CMSServiceFacade::getInstance();
        return sprintf(
            TranslationAPIFacade::getInstance()->__('Your daily notifications — %s', 'pop-commonautomatedemails-processors'),
            date($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')))
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AAL_AE_DailyLatestNotifications();
