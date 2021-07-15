<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\PersistedQueryBlockCategory;

/**
 * Persisted Query Options block
 */
class PersistedQueryOptionsBlock extends AbstractQueryExecutionOptionsBlock
{
    use MainPluginBlockTrait;

    public const ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS = 'acceptVariablesAsURLParams';
    public const ATTRIBUTE_NAME_INHERIT_QUERY = 'inheritQuery';

    protected function getBlockName(): string
    {
        return 'persisted-query-options';
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

    protected function getBlockCategoryClass(): ?string
    {
        return PersistedQueryBlockCategory::class;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Accept variables as URL params:', 'graphql-api'),
            $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? true)
        );
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Inherit query from ancestor(s):', 'graphql-api'),
            $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false)
        );

        return $blockContent;
    }
}
