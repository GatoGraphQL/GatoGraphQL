<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\CacheControlBlockCategory;

/**
 * Cache Control block
 */
class CacheControlBlock extends AbstractControlBlock
{
    use MainPluginBlockTrait;

    public final const ATTRIBUTE_NAME_CACHE_CONTROL_MAX_AGE = 'cacheControlMaxAge';

    private ?CacheControlBlockCategory $cacheControlBlockCategory = null;

    final public function setCacheControlBlockCategory(CacheControlBlockCategory $cacheControlBlockCategory): void
    {
        $this->cacheControlBlockCategory = $cacheControlBlockCategory;
    }
    final protected function getCacheControlBlockCategory(): CacheControlBlockCategory
    {
        return $this->cacheControlBlockCategory ??= $this->instanceManager->getInstance(CacheControlBlockCategory::class);
    }

    protected function getBlockName(): string
    {
        return 'cache-control';
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getCacheControlBlockCategory();
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

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

    protected function getBlockDataTitle(): string
    {
        return \__('Cache-control header for:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Max-age:', 'graphql-api');
    }
    /**
     * @param array<string, mixed> $attributes
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            %s
        </div>
EOF;
        $cacheControlMaxAge = $attributes[self::ATTRIBUTE_NAME_CACHE_CONTROL_MAX_AGE] ?? null;
        if (is_null($cacheControlMaxAge) || $cacheControlMaxAge < 0) {
            $cacheControlMaxAgeText = sprintf(
                '<em>%s</em>',
                \__('(not set)', 'graphql-api')
            );
        } elseif ($cacheControlMaxAge === 0) {
            $cacheControlMaxAgeText = sprintf(
                \__('%s seconds (<code>no-store</code>)', 'graphql-api'),
                $cacheControlMaxAge
            );
        } else {
            $cacheControlMaxAgeText = sprintf(
                \__('%s seconds', 'graphql-api'),
                $cacheControlMaxAge
            );
        }
        return sprintf(
            $blockContentPlaceholder,
            $this->getBlockClassName() . '__content',
            $cacheControlMaxAgeText
        );
    }
}
