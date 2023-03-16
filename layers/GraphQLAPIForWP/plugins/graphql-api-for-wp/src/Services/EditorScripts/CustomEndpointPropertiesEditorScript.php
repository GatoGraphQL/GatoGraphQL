<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Scripts\MainPluginScriptTrait;

class CustomEndpointPropertiesEditorScript extends AbstractEditorScript
{
    use MainPluginScriptTrait;

    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    protected function getScriptName(): string
    {
        return 'custom-endpoint-properties';
    }

    public function getEnablingModule(): ?string
    {
        return UserInterfaceFunctionalityModuleResolver::CUSTOM_ENDPOINT_PROPERTIES;
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
                $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType(),
            ]
        );
    }

    protected function registerStyleIndexCSS(): bool
    {
        return true;
    }
}
