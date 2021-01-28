<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\Hooks;

use PoPSchema\UserRoles\Constants\ModelInstanceComponentTypes;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Users\Routing\RouteNatures;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;

class VarsHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = ApplicationState::getVars();
        switch ($vars['nature']) {
            case RouteNatures::USER:
                $user_id = $vars['routing-state']['queried-object-id'];

                // Author: it may depend on its role
                $component_types = HooksAPIFacade::getInstance()->applyFilters(
                    '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:userrole',
                    array(
                        ModelInstanceComponentTypes::USER_ROLE,
                    )
                );
                if (in_array(ModelInstanceComponentTypes::USER_ROLE, $component_types)) {
                    $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
                    $components[] = TranslationAPIFacade::getInstance()->__('user role:', 'pop-engine') . $userRoleTypeDataResolver->getTheUserRole($user_id);
                }
                break;
        }
        return $components;
    }
}
