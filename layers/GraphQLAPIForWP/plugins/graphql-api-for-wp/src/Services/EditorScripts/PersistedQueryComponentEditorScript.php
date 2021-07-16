<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Scripts\MainPluginScriptTrait;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;

/**
 * Components required to edit a GraphQL Persisted Query CPT
 */
class PersistedQueryEndpointComponentEditorScript extends AbstractEditorScript
{
    use MainPluginScriptTrait;

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
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        return array_merge(
            parent::getAllowedPostTypes(),
            [
                $customPostTypeService->getCustomPostType(),
            ]
        );
    }
}
