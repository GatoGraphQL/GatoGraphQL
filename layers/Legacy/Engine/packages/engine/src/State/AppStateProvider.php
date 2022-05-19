<?php

declare(strict_types=1);

namespace PoP\Engine\State;

use PoP\ComponentModel\ComponentFilters\ComponentPaths;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\Modules\ComponentHelpersInterface;
use PoP\Engine\Configuration\Request;
use PoP\Engine\ComponentFilters\HeadModule;
use PoP\Engine\ComponentFilters\MainContentModule;
use PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?HeadModule $headComponent = null;
    private ?ComponentPaths $componentPaths = null;
    private ?MainContentModule $mainContentComponent = null;
    private ?ComponentRoutingProcessorManagerInterface $routeComponentProcessorManager = null;
    private ?ComponentPathHelpersInterface $componentPathHelpers = null;
    private ?ComponentHelpersInterface $componentHelpers = null;

    final public function setHeadModule(HeadModule $headComponent): void
    {
        $this->headComponent = $headComponent;
    }
    final protected function getHeadModule(): HeadModule
    {
        return $this->headComponent ??= $this->instanceManager->getInstance(HeadModule::class);
    }    
    final public function setComponentPaths(ComponentPaths $componentPaths): void
    {
        $this->componentPaths = $componentPaths;
    }
    final protected function getComponentPaths(): ComponentPaths
    {
        return $this->componentPaths ??= $this->instanceManager->getInstance(ComponentPaths::class);
    }
    final public function setMainContentModule(MainContentModule $mainContentComponent): void
    {
        $this->mainContentComponent = $mainContentComponent;
    }
    final protected function getMainContentModule(): MainContentModule
    {
        return $this->mainContentComponent ??= $this->instanceManager->getInstance(MainContentModule::class);
    }
    final public function setComponentRoutingProcessorManager(ComponentRoutingProcessorManagerInterface $routeComponentProcessorManager): void
    {
        $this->routeComponentProcessorManager = $routeComponentProcessorManager;
    }
    final protected function getComponentRoutingProcessorManager(): ComponentRoutingProcessorManagerInterface
    {
        return $this->routeComponentProcessorManager ??= $this->instanceManager->getInstance(ComponentRoutingProcessorManagerInterface::class);
    }
    final public function setComponentPathHelpers(ComponentPathHelpersInterface $componentPathHelpers): void
    {
        $this->componentPathHelpers = $componentPathHelpers;
    }
    final protected function getComponentPathHelpers(): ComponentPathHelpersInterface
    {
        return $this->componentPathHelpers ??= $this->instanceManager->getInstance(ComponentPathHelpersInterface::class);
    }
    final public function setComponentHelpers(ComponentHelpersInterface $componentHelpers): void
    {
        $this->componentHelpers = $componentHelpers;
    }
    final protected function getComponentHelpers(): ComponentHelpersInterface
    {
        return $this->componentHelpers ??= $this->instanceManager->getInstance(ComponentHelpersInterface::class);
    }

    public function augment(array &$state): void
    {
        if ($state['componentFilter'] === null) {
            return;
        }

        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        $enablePassingStateViaRequest = $rootModuleConfiguration->enablePassingStateViaRequest();

        if ($state['componentFilter'] === $this->headComponent->getName()) {
            if ($enablePassingStateViaRequest) {
                if ($headComponent = Request::getHeadModule()) {
                    $state['headComponent'] = $this->getComponentHelpers()->getModuleFromOutputName($headComponent);
                }
            }
        }
        if ($state['componentFilter'] === $this->componentPaths->getName()) {
            if ($enablePassingStateViaRequest) {
                $state['componentPaths'] = $this->getComponentPathHelpers()->getComponentPaths();
            }
        }
        // Function `getRoutingComponentByMostAllMatchingStateProperties` actually needs to access all values in $state
        // Hence, calculate only at the very end
        // If filtering component by "maincontent", then calculate which is the main content component
        if ($state['componentFilter'] === $this->mainContentComponent->getName()) {
            $state['mainContentComponent'] = $this->getComponentRoutingProcessorManager()->getRoutingComponentByMostAllMatchingStateProperties(\POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT);
        }
    }
}
