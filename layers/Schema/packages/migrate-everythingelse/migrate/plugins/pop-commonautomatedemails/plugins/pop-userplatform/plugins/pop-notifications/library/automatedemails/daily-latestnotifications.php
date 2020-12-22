<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

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
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return sprintf(
            TranslationAPIFacade::getInstance()->__('Your daily notifications — %s', 'pop-commonautomatedemails-processors'),
            date($cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')))
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AAL_AE_DailyLatestNotifications();
