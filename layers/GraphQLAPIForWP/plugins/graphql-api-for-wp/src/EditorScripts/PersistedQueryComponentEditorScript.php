<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EditorScripts;

use GraphQLAPI\GraphQLAPI\Scripts\GraphQLByPoPScriptTrait;
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
