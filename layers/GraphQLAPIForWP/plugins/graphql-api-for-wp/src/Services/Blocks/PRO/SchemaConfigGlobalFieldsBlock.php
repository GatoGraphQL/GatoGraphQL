<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\OptionsBlockTrait;
use GraphQLAPI\GraphQLAPIPRO\Constants\GlobalFieldsSchemaExposure;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PRO\SchemaConfigurationFunctionalityModuleResolver;
use PoP\Root\App;

class SchemaConfigGlobalFieldsBlock extends AbstractSchemaConfigPlaceholderPROBlock
{
    use PROPluginBlockTrait;
    use OptionsBlockTrait;

    // public final const ATTRIBUTE_NAME_SCHEMA_EXPOSURE = 'schemaExposure';

    protected function getBlockName(): string
    {
        return 'schema-config-global-fields';
    }

    public function getBlockPriority(): int
    {
        return 3000;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::GLOBAL_FIELDS;
    }

    // /**
    //  * @param array<string,mixed> $attributes
    //  */
    // public function renderBlock(array $attributes, string $content): string
    // {
    //     // Append "-front" because this style must be used only on the client, not on the admin
    //     $className = $this->getBlockClassName() . '-front';

    //     $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

    //     $schemaExposureLabels = [
    //         GlobalFieldsSchemaExposure::DO_NOT_EXPOSE => \__('⚫️ Do not expose', 'graphql-api-pro'),
    //         GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY => \__('🔵 Expose under the Root type only', 'graphql-api-pro'),
    //         GlobalFieldsSchemaExposure::EXPOSE_IN_ALL_TYPES => \__('⚪️ Expose under all types', 'graphql-api-pro'),
    //     ];
    //     /** @var ModuleConfiguration */
    //     $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
    //     $blockContent = sprintf(
    //         $blockContentPlaceholder,
    //         \__('Schema exposure:', 'graphql-api-pro'),
    //         $schemaExposureLabels[$attributes[self::ATTRIBUTE_NAME_SCHEMA_EXPOSURE] ?? ''] ?? $moduleConfiguration->getSettingsValueLabel()
    //     );

    //     $blockContentPlaceholder = <<<EOT
    //         <div class="%s">
    //             <h3 class="%s">%s</h3>
    //             %s
    //         </div>
    //     EOT;
    //     return sprintf(
    //         $blockContentPlaceholder,
    //         $className . ' ' . $this->getAlignClassName(),
    //         $className . '__title',
    //         \__('Global Fields', 'graphql-api-pro'),
    //         $blockContent
    //     );
    // }

    // /**
    //  * Register style-index.css
    //  */
    // protected function registerCommonStyleCSS(): bool
    // {
    //     return true;
    // }

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
}
