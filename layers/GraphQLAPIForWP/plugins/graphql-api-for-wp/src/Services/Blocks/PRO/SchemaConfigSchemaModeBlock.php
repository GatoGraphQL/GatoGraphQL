<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\OptionsBlockTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PRO\SchemaConfigurationFunctionalityModuleResolver;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\Root\App;

class SchemaConfigSchemaModeBlock extends AbstractSchemaConfigPlaceholderPROBlock
{
    use PROPluginBlockTrait;
    use OptionsBlockTrait;

    // public final const ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE = 'defaultSchemaMode';

    protected function getBlockName(): string
    {
        return 'schema-config-schema-mode';
    }

    public function getBlockPriority(): int
    {
        return 2800;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA;
    }

    // /**
    //  * @param array<string,mixed> $attributes
    //  */
    // public function renderBlock(array $attributes, string $content): string
    // {
    //     // Append "-front" because this style must be used only on the client, not on the admin
    //     $className = $this->getBlockClassName() . '-front';

    //     $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

    //     $schemaModeLabels = [
    //         SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
    //         SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
    //     ];
    //     /** @var ModuleConfiguration */
    //     $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
    //     $blockContent = sprintf(
    //         $blockContentPlaceholder,
    //         \__('Public/Private Schema Mode:', 'graphql-api'),
    //         $schemaModeLabels[$attributes[self::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE] ?? ''] ?? $moduleConfiguration->getSettingsValueLabel()
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
    //         \__('Public/Private Schema', 'graphql-api'),
    //         $blockContent
    //     );
    // }

    // /**
    //  * Register index.css
    //  */
    // protected function registerEditorCSS(): bool
    // {
    //     return true;
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
