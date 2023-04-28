<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Constants\ConfigurationDefaultValues;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface;

class SchemaConfigSchemaCategoriesBlock extends AbstractSchemaConfigCustomizableConfigurationBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public final const ATTRIBUTE_NAME_INCLUDED_CATEGORY_TAXONOMIES = 'includedCategoryTaxonomies';

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
        return 'schema-config-schema-categories';
    }

    public function getBlockPriority(): int
    {
        return 9070;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CATEGORIES;
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
                'possibleCategoryTaxonomies' => $this->getWPDataModelProvider()->getFilteredNonGraphQLAPIPluginCategoryTaxonomies(),
                'defaultCategoryTaxonomies' => ConfigurationDefaultValues::DEFAULT_CATEGORY_TAXONOMIES,
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     */
    protected function doRenderBlock(array $attributes, string $content): string
    {
        $values = $attributes[self::ATTRIBUTE_NAME_INCLUDED_CATEGORY_TAXONOMIES] ?? [];
        return sprintf(
            '<p><strong>%s</strong></p>%s',
            $this->__('Included category taxonomies', 'graphql-api'),
            $values ?
                sprintf(
                    '<ul><li><code>%s</code></li></ul>',
                    implode('</code></li><li><code>', $values)
                ) :
                sprintf(
                    '<p><em>%s</em></p>',
                    \__('(not set)', 'graphql-api')
                )
        );
    }

    protected function getBlockTitle(): string
    {
        return \__('Categories', 'graphql-api');
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
