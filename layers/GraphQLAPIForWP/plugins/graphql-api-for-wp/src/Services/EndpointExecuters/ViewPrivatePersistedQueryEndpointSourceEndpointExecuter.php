<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPrivatePersistedQueryEndpointCustomPostType;

class ViewPrivatePersistedQueryEndpointSourceEndpointExecuter extends AbstractViewPersistedQueryEndpointSourceEndpointExecuter
{
    private ?GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLPrivatePersistedQueryEndpointCustomPostType(GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPrivatePersistedQueryEndpointCustomPostType = $graphQLPrivatePersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPrivatePersistedQueryEndpointCustomPostType(): GraphQLPrivatePersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPrivatePersistedQueryEndpointCustomPostType */
        return $this->graphQLPrivatePersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPrivatePersistedQueryEndpointCustomPostType::class);
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PRIVATE_PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLPrivatePersistedQueryEndpointCustomPostType();
    }
}
