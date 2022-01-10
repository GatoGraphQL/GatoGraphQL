<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\State;

use PoPSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
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
        // Allow to override the queried object, eg: by the AppShell
        list($queried_object, $queried_object_id) = $this->getHooksAPI()->applyFilters(
            'ApplicationState:queried-object',
            [
                $this->getCMSRoutingStateService()->getQueriedObject(),
                $this->getCMSRoutingStateService()->getQueriedObjectId()
            ]
        );

        $vars['routing-state']['queried-object'] = $queried_object;
        $vars['routing-state']['queried-object-id'] = $queried_object_id;
    }
}
