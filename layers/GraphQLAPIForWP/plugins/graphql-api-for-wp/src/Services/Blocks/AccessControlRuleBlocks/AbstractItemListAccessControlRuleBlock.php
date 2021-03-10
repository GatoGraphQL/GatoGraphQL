<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;

/**
 * Access Control User Capabilities block
 */
abstract class AbstractItemListAccessControlRuleBlock extends AbstractAccessControlRuleBlock
{
    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            %s
        </div>
EOF;
        $values = $attributes[self::ATTRIBUTE_NAME_VALUE] ?? [];
        return sprintf(
            $blockContentPlaceholder,
            $this->getBlockClassName(),
            $values ?
                sprintf(
                    '<p><strong>%s</strong></p><ul><li><code>%s</code></li></ul>',
                    $this->getHeader(),
                    implode('</code></li><li><code>', $values)
                ) :
                sprintf(
                    '<em>%s</em>',
                    \__('(not set)', 'graphql-api')
                )
        );
    }

    abstract protected function getHeader(): string;
}
