<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\State;

use PoP\Root\App;
use PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
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
        [$queried_object, $queried_object_id] = [
            $this->getCMSRoutingStateService()->getQueriedObject(),
            $this->getCMSRoutingStateService()->getQueriedObjectId()
        ];
        $state['routing']['queried-object'] = $queried_object;
        $state['routing']['queried-object-id'] = $queried_object_id;
    }
}
