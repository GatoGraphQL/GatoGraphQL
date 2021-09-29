<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\UserRoles\Constants\ModelInstanceComponentTypes;
use PoPSchema\UserRoles\Facades\UserRoleTypeAPIFacade;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPSchema\Users\Routing\RouteNatures;

class VarsHookSet extends AbstractHookSet
{
    protected UserRoleTypeAPIInterface $userRoleTypeAPI;

    #[Required]
    public function autowireVarsHookSet(
        UserRoleTypeAPIInterface $userRoleTypeAPI,
    ): void {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }

    protected function init(): void
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
                $component_types = $this->hooksAPI->applyFilters(
                    '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:userrole',
                    array(
                        ModelInstanceComponentTypes::USER_ROLE,
                    )
                );
                if (in_array(ModelInstanceComponentTypes::USER_ROLE, $component_types)) {
                    /** @var string */
                    $userRole = $this->userRoleTypeAPI->getTheUserRole($user_id);
                    $components[] = $this->translationAPI->__('user role:', 'pop-engine') . $userRole;
                }
                break;
        }
        return $components;
    }
}
