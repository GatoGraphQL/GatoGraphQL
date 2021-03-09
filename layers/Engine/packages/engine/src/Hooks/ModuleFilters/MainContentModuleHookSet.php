<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ModuleFilters;

use PoP\Engine\ModuleFilters\MainContentModule;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;
use PoP\Translation\TranslationAPIInterface;

class MainContentModuleHookSet extends AbstractHookSet
{
    protected MainContentModule $mainContentModule;

    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        MainContentModule $mainContentModule
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI
        );
        $this->mainContentModule = $mainContentModule;
    }

    protected function init()
    {
        $this->hooksAPI->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            PHP_INT_MAX,
            1
        );
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;

        // Function `getRouteModuleByMostAllmatchingVarsProperties` actually needs to access all values in $vars
        // Hence, calculate only at the very end
        // If filtering module by "maincontent", then calculate which is the main content module
        if (isset($vars['modulefilter']) && $vars['modulefilter'] == $this->mainContentModule->getName()) {
            $vars['maincontentmodule'] = RouteModuleProcessorManagerFacade::getInstance()->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
        }
    }
}
