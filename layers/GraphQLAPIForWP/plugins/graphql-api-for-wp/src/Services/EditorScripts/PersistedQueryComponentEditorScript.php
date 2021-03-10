<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Scripts\GraphQLByPoPScriptTrait;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLPersistedQueryPostType;

/**
 * Components required to edit a GraphQL Persisted Query CPT
 */
class PersistedQueryComponentEditorScript extends AbstractEditorScript
{
    use GraphQLByPoPScriptTrait;

    /**
     * Block name
     *
     * @return string
     */
    protected function getScriptName(): string
    {
        return 'persisted-query-editor-components';
    }

    protected function getEnablingModule(): ?string
    {
        return UserInterfaceFunctionalityModuleResolver::WELCOME_GUIDES;
    }

    /**
     * Add the locale language to the localized data?
     *
     * @return bool
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
                GraphQLPersistedQueryPostType::POST_TYPE,
            ]
        );
    }
}
