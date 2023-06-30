<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserRoles\Constants\ModelInstanceComponentTypes;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\Users\Routing\RequestNature;

class VarsHookSet extends AbstractHookSet
{
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;

    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        if ($this->userRoleTypeAPI === null) {
            /** @var UserRoleTypeAPIInterface */
            $userRoleTypeAPI = $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
            $this->userRoleTypeAPI = $userRoleTypeAPI;
        }
        return $this->userRoleTypeAPI;
    }

    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_ELEMENTS_RESULT,
            $this->getModelInstanceElementsFromAppState(...)
        );
    }

    /**
     * @return string[]
     * @param string[] $elements
     */
    public function getModelInstanceElementsFromAppState(array $elements): array
    {
        switch (App::getState('nature')) {
            case RequestNature::USER:
                $user_id = App::getState(['routing', 'queried-object-id']);
                // Author: it may depend on its role
                // @todo convert the hook from string to const, then re-enable
                // $component_types = App::applyFilters(
                //     '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:userrole',
                //     array(
                //         ModelInstanceComponentTypes::USER_ROLE,
                //     )
                // );
                $component_types = array(
                    ModelInstanceComponentTypes::USER_ROLE,
                );
                if (in_array(ModelInstanceComponentTypes::USER_ROLE, $component_types)) {
                    /** @var string */
                    $userRole = $this->getUserRoleTypeAPI()->getTheUserRole($user_id);
                    $elements[] = $this->__('user role:', 'pop-engine') . $userRole;
                }
                break;
        }
        return $elements;
    }
}
