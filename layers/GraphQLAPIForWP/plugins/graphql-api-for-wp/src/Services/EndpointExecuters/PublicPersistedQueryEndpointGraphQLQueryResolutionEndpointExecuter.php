<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPublicPersistedQueryEndpointCustomPostType;

class PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter
{
    private ?GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLPublicPersistedQueryEndpointCustomPostType(GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPublicPersistedQueryEndpointCustomPostType = $graphQLPublicPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPublicPersistedQueryEndpointCustomPostType(): GraphQLPublicPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPublicPersistedQueryEndpointCustomPostType */
        return $this->graphQLPublicPersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPublicPersistedQueryEndpointCustomPostType::class);
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PUBLIC_PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLPublicPersistedQueryEndpointCustomPostType();
    }
}
