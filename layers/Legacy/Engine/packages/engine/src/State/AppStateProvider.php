<?php

declare(strict_types=1);

namespace PoP\Engine\State;

use PoP\ComponentModel\ComponentFilters\ComponentPaths;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface;
use PoP\Engine\Configuration\Request;
use PoP\Engine\ComponentFilters\HeadComponent;
use PoP\Engine\ComponentFilters\MainContentComponent;
use PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?HeadComponent $headComponent = null;
    private ?ComponentPaths $componentPaths = null;
    private ?MainContentComponent $mainContentComponent = null;
    private ?ComponentRoutingProcessorManagerInterface $routeComponentProcessorManager = null;
    private ?ComponentPathHelpersInterface $componentPathHelpers = null;
    private ?ComponentHelpersInterface $componentHelpers = null;

    final public function setHeadComponent(HeadComponent $headComponent): void
    {
        $this->headComponent = $headComponent;
    }
    final protected function getHeadComponent(): HeadComponent
    {
        if ($this->headComponent === null) {
            /** @var HeadComponent */
            $headComponent = $this->instanceManager->getInstance(HeadComponent::class);
            $this->headComponent = $headComponent;
        }
        return $this->headComponent;
    }    
    final public function setComponentPaths(ComponentPaths $componentPaths): void
    {
        $this->componentPaths = $componentPaths;
    }
    final protected function getComponentPaths(): ComponentPaths
    {
        if ($this->componentPaths === null) {
            /** @var ComponentPaths */
            $componentPaths = $this->instanceManager->getInstance(ComponentPaths::class);
            $this->componentPaths = $componentPaths;
        }
        return $this->componentPaths;
    }
    final public function setMainContentComponent(MainContentComponent $mainContentComponent): void
    {
        $this->mainContentComponent = $mainContentComponent;
    }
    final protected function getMainContentComponent(): MainContentComponent
    {
        if ($this->mainContentComponent === null) {
            /** @var MainContentComponent */
            $mainContentComponent = $this->instanceManager->getInstance(MainContentComponent::class);
            $this->mainContentComponent = $mainContentComponent;
        }
        return $this->mainContentComponent;
    }
    final public function setComponentRoutingProcessorManager(ComponentRoutingProcessorManagerInterface $routeComponentProcessorManager): void
    {
        $this->routeComponentProcessorManager = $routeComponentProcessorManager;
    }
    final protected function getComponentRoutingProcessorManager(): ComponentRoutingProcessorManagerInterface
    {
        if ($this->routeComponentProcessorManager === null) {
            /** @var ComponentRoutingProcessorManagerInterface */
            $routeComponentProcessorManager = $this->instanceManager->getInstance(ComponentRoutingProcessorManagerInterface::class);
            $this->routeComponentProcessorManager = $routeComponentProcessorManager;
        }
        return $this->routeComponentProcessorManager;
    }
    final public function setComponentPathHelpers(ComponentPathHelpersInterface $componentPathHelpers): void
    {
        $this->componentPathHelpers = $componentPathHelpers;
    }
    final protected function getComponentPathHelpers(): ComponentPathHelpersInterface
    {
        if ($this->componentPathHelpers === null) {
            /** @var ComponentPathHelpersInterface */
            $componentPathHelpers = $this->instanceManager->getInstance(ComponentPathHelpersInterface::class);
            $this->componentPathHelpers = $componentPathHelpers;
        }
        return $this->componentPathHelpers;
    }
    final public function setComponentHelpers(ComponentHelpersInterface $componentHelpers): void
    {
        $this->componentHelpers = $componentHelpers;
    }
    final protected function getComponentHelpers(): ComponentHelpersInterface
    {
        if ($this->componentHelpers === null) {
            /** @var ComponentHelpersInterface */
            $componentHelpers = $this->instanceManager->getInstance(ComponentHelpersInterface::class);
            $this->componentHelpers = $componentHelpers;
        }
        return $this->componentHelpers;
    }

    /**
     * @param array<string,mixed> $state
     */
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
                if ($headComponent = Request::getHeadComponent()) {
                    $state['headComponent'] = $this->getComponentHelpers()->getComponentFromOutputName($headComponent);
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
