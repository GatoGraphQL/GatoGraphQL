<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;

class ViewPersistedQueryEndpointSourceEndpointExecuter extends AbstractViewPersistedQueryEndpointSourceEndpointExecuter
{
    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        if ($this->graphQLPersistedQueryEndpointCustomPostType === null) {
            /** @var GraphQLPersistedQueryEndpointCustomPostType */
            $graphQLPersistedQueryEndpointCustomPostType = $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
            $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
        }
        return $this->graphQLPersistedQueryEndpointCustomPostType;
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLPersistedQueryEndpointCustomPostType();
    }
}
