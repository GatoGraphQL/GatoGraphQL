<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Hooks;

use PoP\Engine\FieldResolvers\ObjectType\OperatorGlobalObjectTypeFieldResolver;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;

class ApplicationStateHookSet extends AbstractHookSet
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

    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            'ApplicationState:addVars',
            [$this, 'setQueriedObject'],
            0,
            1
        );
        $this->getHooksAPI()->addAction(
            OperatorGlobalObjectTypeFieldResolver::HOOK_SAFEVARS,
            [$this, 'setSafeVars'],
            10,
            1
        );
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function setQueriedObject(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];

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

    /**
     * @param array<array> $vars_in_array
     */
    public function setSafeVars(array $vars_in_array): void
    {
        // Remove the queried object
        $safeVars = &$vars_in_array[0];
        unset($safeVars['routing-state']['queried-object']);
    }
}
