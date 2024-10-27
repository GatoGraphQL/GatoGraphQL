<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\State;

use PoP\Root\App;
use PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?CMSRoutingStateServiceInterface $cmsRoutingStateService = null;

    final protected function getCMSRoutingStateService(): CMSRoutingStateServiceInterface
    {
        if ($this->cmsRoutingStateService === null) {
            /** @var CMSRoutingStateServiceInterface */
            $cmsRoutingStateService = $this->instanceManager->getInstance(CMSRoutingStateServiceInterface::class);
            $this->cmsRoutingStateService = $cmsRoutingStateService;
        }
        return $this->cmsRoutingStateService;
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $state['routing']['queried-object'] = $this->getCMSRoutingStateService()->getQueriedObject();
            $state['routing']['queried-object-id'] = $this->getCMSRoutingStateService()->getQueriedObjectID();
        } else {
            $state['routing']['queried-object'] = null;
            $state['routing']['queried-object-id'] = null;
        }
    }
}
