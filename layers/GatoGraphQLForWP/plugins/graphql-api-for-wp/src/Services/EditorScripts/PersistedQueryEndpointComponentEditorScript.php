<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EditorScripts;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Scripts\MainPluginScriptTrait;

/**
 * Components required to edit a GraphQL Persisted Query CPT
 */
class PersistedQueryEndpointComponentEditorScript extends AbstractEditorScript
{
    use MainPluginScriptTrait;

    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        return $this->graphQLPersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
    }

    /**
     * Block name
     */
    protected function getScriptName(): string
    {
        return 'persisted-query-editor-components';
    }

    public function getEnablingModule(): ?string
    {
        return UserInterfaceFunctionalityModuleResolver::WELCOME_GUIDES;
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

    /**
     * In what languages is the documentation available
     *
     * @return string[]
     */
    protected function getDocLanguages(): array
    {
        return array_merge(
            parent::getDocLanguages(),
            [
                'es', // Spanish
            ]
        );
    }

    /**
     * Post types for which to register the script
     *
     * @return string[]
     */
    protected function getAllowedPostTypes(): array
    {
        return array_merge(
            parent::getAllowedPostTypes(),
            [
                $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType(),
            ]
        );
    }
}
