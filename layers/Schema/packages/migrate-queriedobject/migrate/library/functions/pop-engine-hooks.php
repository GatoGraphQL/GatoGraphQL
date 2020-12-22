<?php
namespace PoPSchema\QueriedObject;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\FieldResolvers\OperatorGlobalFieldResolver;

class PoP_QueriedObject_VarsHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'ApplicationState:addVars',
            [$this, 'setQueriedObject'],
            0,
            1
        );
        HooksAPIFacade::getInstance()->addAction(
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
        $cmsqueriedobjectrouting = CMSRoutingStateFactory::getInstance();

        // Allow to override the queried object, eg: by the AppShell
        list($queried_object, $queried_object_id) = HooksAPIFacade::getInstance()->applyFilters(
            'ApplicationState:queried-object',
            [
                $cmsqueriedobjectrouting->getQueriedObject(),
                $cmsqueriedobjectrouting->getQueriedObjectId()
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

/**
 * Initialization
 */
new PoP_QueriedObject_VarsHooks();
