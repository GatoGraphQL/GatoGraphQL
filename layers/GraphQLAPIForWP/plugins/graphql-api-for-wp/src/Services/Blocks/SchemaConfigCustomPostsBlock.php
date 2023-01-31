<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Constants\ConfigurationDefaultValues;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface;

class SchemaConfigCustomPostsBlock extends AbstractSchemaConfigBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public final const ATTRIBUTE_NAME_INCLUDED_CUSTOM_POST_TYPES = 'includedCustomPostTypes';

    private ?WPDataModelProviderInterface $wpDataModelProvider = null;

    final public function setWPDataModelProvider(WPDataModelProviderInterface $wpDataModelProvider): void
    {
        $this->wpDataModelProvider = $wpDataModelProvider;
    }
    final protected function getWPDataModelProvider(): WPDataModelProviderInterface
    {
        /** @var WPDataModelProviderInterface */
        return $this->wpDataModelProvider ??= $this->instanceManager->getInstance(WPDataModelProviderInterface::class);
    }

    protected function getBlockName(): string
    {
        return 'schema-config-customposts';
    }

    public function getBlockPriority(): int
    {
        return 10090;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS;
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            [
                'possibleCustomPostTypes' => $this->getWPDataModelProvider()->getFilteredNonGraphQLAPIPluginCustomPostTypes(),
                'defaultCustomPostTypes' => ConfigurationDefaultValues::DEFAULT_CUSTOMPOST_TYPES,
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

        $values = $attributes[self::ATTRIBUTE_NAME_INCLUDED_CUSTOM_POST_TYPES] ?? [];
        $blockContent = $values ?
            sprintf(
                '<p><strong>%s</strong></p><ul><li><code>%s</code></li></ul>',
                $this->__('Included custom post types', 'graphql-api'),
                implode('</code></li><li><code>', $values)
            ) :
            sprintf(
                '<em>%s</em>',
                \__('(not set)', 'graphql-api')
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
            \__('Custom Posts', 'graphql-api'),
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
     * Register index.css
     */
    protected function registerEditorCSS(): bool
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
