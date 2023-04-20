<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPublicPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPrivatePersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Scripts\MainPluginScriptTrait;

class PersistedQueryEndpointPropertiesEditorScript extends AbstractEditorScript
{
    use MainPluginScriptTrait;

    private ?GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType = null;
    private ?GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLPublicPersistedQueryEndpointCustomPostType(GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPublicPersistedQueryEndpointCustomPostType = $graphQLPublicPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPublicPersistedQueryEndpointCustomPostType(): GraphQLPublicPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPublicPersistedQueryEndpointCustomPostType */
        return $this->graphQLPublicPersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPublicPersistedQueryEndpointCustomPostType::class);
    }
    final public function setGraphQLPrivatePersistedQueryEndpointCustomPostType(GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPrivatePersistedQueryEndpointCustomPostType = $graphQLPrivatePersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPrivatePersistedQueryEndpointCustomPostType(): GraphQLPrivatePersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPrivatePersistedQueryEndpointCustomPostType */
        return $this->graphQLPrivatePersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPrivatePersistedQueryEndpointCustomPostType::class);
    }

    protected function getScriptName(): string
    {
        return 'persisted-query-endpoint-properties';
    }

    public function getEnablingModule(): ?string
    {
        return UserInterfaceFunctionalityModuleResolver::PERSISTED_QUERY_ENDPOINT_PROPERTIES;
    }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }

    protected function getAllowedPostTypes(): array
    {
        return array_merge(
            parent::getAllowedPostTypes(),
            [
                $this->getGraphQLPublicPersistedQueryEndpointCustomPostType()->getCustomPostType(),
                $this->getGraphQLPrivatePersistedQueryEndpointCustomPostType()->getCustomPostType(),
            ]
        );
    }

    protected function registerStyleIndexCSS(): bool
    {
        return true;
    }
}
