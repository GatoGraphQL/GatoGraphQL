<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Hooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
use PoP\Engine\FieldResolvers\OperatorGlobalFieldResolver;

class ApplicationStateHookSet extends AbstractHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        protected CMSRoutingStateServiceInterface $cmsRoutingStateService,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
        );
    }
    
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            [$this, 'setQueriedObject'],
            0,
            1
        );
        $this->hooksAPI->addAction(
            OperatorGlobalFieldResolver::HOOK_SAFEVARS,
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
        list($queried_object, $queried_object_id) = $this->hooksAPI->applyFilters(
            'ApplicationState:queried-object',
            [
                $this->cmsRoutingStateService->getQueriedObject(),
                $this->cmsRoutingStateService->getQueriedObjectId()
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
