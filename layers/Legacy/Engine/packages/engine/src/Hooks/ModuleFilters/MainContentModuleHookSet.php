<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ModuleFilters;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\AbstractHookSet;
use PoP\Engine\ModuleFilters\MainContentModule;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

class MainContentModuleHookSet extends AbstractHookSet
{
    protected MainContentModule $mainContentModule;
    
    #[Required]
    public function autowireMainContentModuleHookSet(
        MainContentModule $mainContentModule
    ): void {
        $this->mainContentModule = $mainContentModule;
    }

    protected function init(): void
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
