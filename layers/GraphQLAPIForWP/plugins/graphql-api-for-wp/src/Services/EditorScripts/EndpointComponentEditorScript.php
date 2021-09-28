<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use GraphQLAPI\GraphQLAPI\Services\Scripts\MainPluginScriptTrait;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

/**
 * Components required to edit a GraphQL endpoint CPT
 */
class EndpointComponentEditorScript extends AbstractEditorScript
{
    use MainPluginScriptTrait;

    protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType;

    #[Required]
    public function autowireEndpointComponentEditorScript(
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ) {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }

    /**
     * Block name
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
                $this->graphQLCustomEndpointCustomPostType->getCustomPostType(),
            ]
        );
    }
}
