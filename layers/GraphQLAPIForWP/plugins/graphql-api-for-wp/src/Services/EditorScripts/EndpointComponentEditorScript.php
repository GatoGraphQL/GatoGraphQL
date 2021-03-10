<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Scripts\GraphQLByPoPScriptTrait;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLEndpointPostType;

/**
 * Components required to edit a GraphQL endpoint CPT
 */
class EndpointComponentEditorScript extends AbstractEditorScript
{
    use GraphQLByPoPScriptTrait;

    /**
     * Block name
     *
     * @return string
     */
    protected function getScriptName(): string
    {
        return 'endpoint-editor-components';
    }

    public function getEnablingModule(): ?string
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
                GraphQLEndpointPostType::POST_TYPE,
            ]
        );
    }
}
