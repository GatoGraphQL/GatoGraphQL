<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;

class SchemaConfigMutationSchemeBlock extends AbstractSchemaConfigBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public final const ATTRIBUTE_NAME_MUTATION_SCHEME = 'mutationScheme';

    protected function getBlockName(): string
    {
        return 'schema-config-mutation-scheme';
    }

    public function getBlockPriority(): int
    {
        return 10110;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

        $mutationSchemeLabels = [
            MutationSchemes::STANDARD => \__('❌ Do not enable nested mutations', 'graphql-api'),
            MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('✅ Nested mutations enabled, keeping all mutation fields in the root type', 'graphql-api'),
            MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('✳️ Nested mutations enabled, removing the redundant mutation fields from the root type', 'graphql-api'),
        ];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Mutation Scheme', 'graphql-api'),
            $mutationSchemeLabels[$attributes[self::ATTRIBUTE_NAME_MUTATION_SCHEME] ?? ''] ?? $moduleConfiguration->getSettingsValueLabel()
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
            \__('Mutation Scheme', 'graphql-api'),
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
