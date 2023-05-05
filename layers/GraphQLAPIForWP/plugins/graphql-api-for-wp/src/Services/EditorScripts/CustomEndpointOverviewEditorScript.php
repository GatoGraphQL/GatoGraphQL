<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EditorScripts;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Scripts\MainPluginScriptTrait;

class CustomEndpointOverviewEditorScript extends AbstractEditorScript
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
        return 'custom-endpoint-overview';
    }

    public function getEnablingModule(): ?string
    {
        return UserInterfaceFunctionalityModuleResolver::CUSTOM_ENDPOINT_OVERVIEW;
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
