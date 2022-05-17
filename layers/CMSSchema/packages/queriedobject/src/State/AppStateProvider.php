<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\State;

use PoP\Root\App;
use PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootComponentConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?CMSRoutingStateServiceInterface $cmsRoutingStateService = null;

    final public function setCMSRoutingStateService(CMSRoutingStateServiceInterface $cmsRoutingStateService): void
    {
        $this->cmsRoutingStateService = $cmsRoutingStateService;
    }
    final protected function getCMSRoutingStateService(): CMSRoutingStateServiceInterface
    {
        return $this->cmsRoutingStateService ??= $this->instanceManager->getInstance(CMSRoutingStateServiceInterface::class);
    }

    public function initialize(array &$state): void
    {
        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootModule::class)->getConfiguration();
        if ($rootComponentConfiguration->enablePassingStateViaRequest()) {
            $state['routing']['queried-object'] = $this->getCMSRoutingStateService()->getQueriedObject();
            $state['routing']['queried-object-id'] = $this->getCMSRoutingStateService()->getQueriedObjectId();
        } else {
            $state['routing']['queried-object'] = null;
            $state['routing']['queried-object-id'] = null;
        }
    }
}
