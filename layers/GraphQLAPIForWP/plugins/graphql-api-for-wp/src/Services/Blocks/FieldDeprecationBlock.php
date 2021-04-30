<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\FieldDeprecationBlockCategory;

/**
 * Field Deprecation block
 */
class FieldDeprecationBlock extends AbstractControlBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_DEPRECATION_REASON = 'deprecationReason';

    protected function getBlockName(): string
    {
        return 'field-deprecation';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        /**
         * @var FieldDeprecationBlockCategory
         */
        $blockCategory = $this->instanceManager->getInstance(FieldDeprecationBlockCategory::class);
        return $blockCategory;
    }

    protected function disableDirectives(): bool
    {
        return true;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function registerEditorCSS(): bool
    {
        return true;
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Fields to deprecate:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Deprecation reason:', 'graphql-api');
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
        $deprecationReason = $attributes[self::ATTRIBUTE_NAME_DEPRECATION_REASON] ?? null;
        if (!$deprecationReason) {
            $deprecationReason = sprintf(
                '<em>%s</em>',
                \__('(not set)', 'graphql-api')
            );
        }
        return sprintf(
            $blockContentPlaceholder,
            $this->getBlockClassName() . '__content',
            $deprecationReason
        );
    }
}
