<?php

class PoPThemeWassupCommonPages_GenericForms_DataLoad_FilterHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'FilterInnerModuleProcessor:inputmodules',
            array(PoP_VolunteeringProcessors_FilterUtils::class, 'maybeAddVolunteerFilterinput'),
            10,
            2
        );
    }
}

/**
 * Initialize
 */
new PoPThemeWassupCommonPages_GenericForms_DataLoad_FilterHooks();
