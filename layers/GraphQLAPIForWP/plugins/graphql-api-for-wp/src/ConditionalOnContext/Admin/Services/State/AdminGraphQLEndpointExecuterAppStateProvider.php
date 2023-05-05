<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\Services\State;

use GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuter;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GatoGraphQL\GatoGraphQL\State\AbstractGraphQLEndpointExecuterAppStateProvider;

class AdminGraphQLEndpointExecuterAppStateProvider extends AbstractGraphQLEndpointExecuterAppStateProvider
{
    private ?AdminEndpointExecuter $adminEndpointExecuter = null;
    private ?UserAuthorizationInterface $userAuthorization = null;

    final public function setAdminEndpointExecuter(AdminEndpointExecuter $adminEndpointExecuter): void
    {
        $this->adminEndpointExecuter = $adminEndpointExecuter;
    }
    final protected function getAdminEndpointExecuter(): AdminEndpointExecuter
    {
        /** @var AdminEndpointExecuter */
        return $this->adminEndpointExecuter ??= $this->instanceManager->getInstance(AdminEndpointExecuter::class);
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }

    protected function getGraphQLEndpointExecuter(): GraphQLEndpointExecuterInterface
    {
        return $this->getAdminEndpointExecuter();
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }
        parent::initialize($state);
    }

    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(array &$state): void
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }
        parent::consolidate($state);
    }
}
