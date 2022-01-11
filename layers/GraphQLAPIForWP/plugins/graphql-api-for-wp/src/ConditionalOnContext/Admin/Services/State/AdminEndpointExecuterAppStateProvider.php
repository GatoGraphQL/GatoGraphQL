<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\State;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuter;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\State\AbstractEndpointExecuterAppStateProvider;

class AdminEndpointExecuterAppStateProvider extends AbstractEndpointExecuterAppStateProvider
{
    private ?AdminEndpointExecuter $adminEndpointExecuter = null;
    private ?UserAuthorizationInterface $userAuthorization = null;

    final public function setAdminEndpointExecuter(AdminEndpointExecuter $adminEndpointExecuter): void
    {
        $this->adminEndpointExecuter = $adminEndpointExecuter;
    }
    final protected function getAdminEndpointExecuter(): AdminEndpointExecuter
    {
        return $this->adminEndpointExecuter ??= $this->instanceManager->getInstance(AdminEndpointExecuter::class);
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }

    protected function getEndpointExecuter(): GraphQLEndpointExecuterInterface
    {
        return $this->getAdminEndpointExecuter();
    }

    public function initialize(array &$state): void
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }
        parent::initialize($state);
    }

    public function consolidate(array &$state): void
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }
        parent::consolidate($state);
    }
}
