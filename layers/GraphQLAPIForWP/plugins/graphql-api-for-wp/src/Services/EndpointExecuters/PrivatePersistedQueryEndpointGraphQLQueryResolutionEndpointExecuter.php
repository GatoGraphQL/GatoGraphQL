<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPrivatePersistedQueryEndpointCustomPostType;

class PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter
{
    private ?GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType = null;
    private ?UserAuthorizationInterface $userAuthorization = null;

    final public function setGraphQLPrivatePersistedQueryEndpointCustomPostType(GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPrivatePersistedQueryEndpointCustomPostType = $graphQLPrivatePersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPrivatePersistedQueryEndpointCustomPostType(): GraphQLPrivatePersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPrivatePersistedQueryEndpointCustomPostType */
        return $this->graphQLPrivatePersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPrivatePersistedQueryEndpointCustomPostType::class);
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

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PRIVATE_PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLPrivatePersistedQueryEndpointCustomPostType();
    }

    /**
     * Only enable the service if the schema editor user is
     * accessing the private persisted query
     */
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return false;
        }

        return true;
    }
}
