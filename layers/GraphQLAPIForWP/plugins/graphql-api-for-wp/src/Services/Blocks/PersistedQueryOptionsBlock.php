<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\PersistedQueryBlockCategory;

/**
 * Persisted Query Options block
 */
class PersistedQueryOptionsBlock extends AbstractQueryExecutionOptionsBlock implements PersistedQueryEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public const ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS = 'acceptVariablesAsURLParams';

    protected function getBlockName(): string
    {
        return 'persisted-query-options';
    }

    public function getBlockPriority(): int
    {
        return 160;
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

        return $blockContent;
    }
}
