<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\State;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointResolvers\AdminEndpointResolver;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\State\AbstractEndpointExecuterAppStateProvider;

class AdminEndpointResolverAppStateProvider extends AbstractEndpointExecuterAppStateProvider
{
    private ?AdminEndpointResolver $adminEndpointResolver = null;
    private ?UserAuthorizationInterface $userAuthorization = null;

    final public function setAdminEndpointResolver(AdminEndpointResolver $adminEndpointResolver): void
    {
        $this->adminEndpointResolver = $adminEndpointResolver;
    }
    final protected function getAdminEndpointResolver(): AdminEndpointResolver
    {
        return $this->adminEndpointResolver ??= $this->instanceManager->getInstance(AdminEndpointResolver::class);
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }

    protected function getEndpointExecuter(): EndpointExecuterInterface
    {
        return $this->getAdminEndpointResolver();
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
