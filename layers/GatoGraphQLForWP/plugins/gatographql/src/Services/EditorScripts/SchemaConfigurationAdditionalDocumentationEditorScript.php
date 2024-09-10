<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EditorScripts;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Scripts\MainPluginScriptTrait;

class SchemaConfigurationAdditionalDocumentationEditorScript extends AbstractEditorScript
{
    use MainPluginScriptTrait;

    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        if ($this->graphQLSchemaConfigurationCustomPostType === null) {
            /** @var GraphQLSchemaConfigurationCustomPostType */
            $graphQLSchemaConfigurationCustomPostType = $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
            $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
        }
        return $this->graphQLSchemaConfigurationCustomPostType;
    }

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableSchemaConfiguration()) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    protected function getScriptName(): string
    {
        return 'schema-configuration-additional-documentation';
    }

    public function getEnablingModule(): ?string
    {
        return UserInterfaceFunctionalityModuleResolver::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION;
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
                $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType(),
            ]
        );
    }

    protected function registerStyleIndexCSS(): bool
    {
        return true;
    }
}
