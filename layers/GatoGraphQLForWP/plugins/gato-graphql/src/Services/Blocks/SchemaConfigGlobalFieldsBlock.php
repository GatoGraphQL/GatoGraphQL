<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Constants\GlobalFieldsSchemaExposure;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractSchemaConfigBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\OptionsBlockTrait;
use PoP\Root\App;

class SchemaConfigGlobalFieldsBlock extends AbstractSchemaConfigBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public final const ATTRIBUTE_NAME_SCHEMA_EXPOSURE = 'schemaExposure';

    protected function getBlockName(): string
    {
        return 'schema-config-global-fields';
    }

    public function getBlockPriority(): int
    {
        return 10115;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::GLOBAL_FIELDS;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

        $schemaExposureLabels = [
            GlobalFieldsSchemaExposure::DO_NOT_EXPOSE => \__('⚫️ Do not expose', 'gato-graphql-pro'),
            GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY => \__('🔵 Expose under the Root type only', 'gato-graphql-pro'),
            GlobalFieldsSchemaExposure::EXPOSE_IN_ALL_TYPES => \__('⚪️ Expose under all types', 'gato-graphql-pro'),
        ];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Schema exposure:', 'gato-graphql-pro'),
            $schemaExposureLabels[$attributes[self::ATTRIBUTE_NAME_SCHEMA_EXPOSURE] ?? ''] ?? $moduleConfiguration->getSettingsValueLabel()
        );

        $blockContentPlaceholder = <<<EOT
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
        EOT;
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            \__('Global Fields', 'gato-graphql-pro'),
            $blockContent
        );
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
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
}
