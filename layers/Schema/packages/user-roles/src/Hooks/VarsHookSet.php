<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\UserRoles\Constants\ModelInstanceComponentTypes;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPSchema\Users\Routing\RouteNatures;

class VarsHookSet extends AbstractHookSet
{
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;

    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        return $this->userRoleTypeAPI ??= $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
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
                $component_types = $this->getHooksAPI()->applyFilters(
                    '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:userrole',
                    array(
                        ModelInstanceComponentTypes::USER_ROLE,
                    )
                );
                if (in_array(ModelInstanceComponentTypes::USER_ROLE, $component_types)) {
                    /** @var string */
                    $userRole = $this->getUserRoleTypeAPI()->getTheUserRole($user_id);
                    $components[] = $this->getTranslationAPI()->__('user role:', 'pop-engine') . $userRole;
                }
                break;
        }
        return $components;
    }
}
