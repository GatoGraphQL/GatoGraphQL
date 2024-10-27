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

    final protected function getAdminEndpointExecuter(): AdminEndpointExecuter
    {
        if ($this->adminEndpointExecuter === null) {
            /** @var AdminEndpointExecuter */
            $adminEndpointExecuter = $this->instanceManager->getInstance(AdminEndpointExecuter::class);
            $this->adminEndpointExecuter = $adminEndpointExecuter;
        }
        return $this->adminEndpointExecuter;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
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
