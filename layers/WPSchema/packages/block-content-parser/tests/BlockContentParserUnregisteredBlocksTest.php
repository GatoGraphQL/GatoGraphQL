<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * A block that WordPress no longer registers, but whose attributes are
 * known, is read from its saved HTML all the same.
 */
class BlockContentParserUnregisteredBlocksTest extends TestCase
{
    /**
     * @param array<string,mixed> $block
     * @return array<string,mixed>|null
     */
    private function sourceBlock(array $block): ?array
    {
        $parser = (new ReflectionClass(BlockContentParser::class))->newInstanceWithoutConstructor();
        $sourceBlock = new ReflectionMethod(BlockContentParser::class, 'source_block');
        /** @var array<string,mixed>|null */
        return $sourceBlock->invoke($parser, $block, []);
    }

    public function testTheTextOfASubheadIsRead(): void
    {
        $sourcedBlock = $this->sourceBlock([
            'blockName' => 'core/subhead',
            'attrs' => [],
            'innerHTML' => '<p class="wp-block-subhead">A subhead with <em>emphasis</em></p>',
            'innerBlocks' => [],
        ]);

        $this->assertNotNull($sourcedBlock);
        $this->assertSame('core/subhead', $sourcedBlock['name']);
        $this->assertSame(['content' => 'A subhead with <em>emphasis</em>'], $sourcedBlock['attributes']);
    }

    public function testTheAttributesInTheDelimiterAreKept(): void
    {
        $sourcedBlock = $this->sourceBlock([
            'blockName' => 'core/subhead',
            'attrs' => ['align' => 'center'],
            'innerHTML' => '<p class="wp-block-subhead has-text-align-center">Centered</p>',
            'innerBlocks' => [],
        ]);

        $this->assertNotNull($sourcedBlock);
        $this->assertSame(['align' => 'center', 'content' => 'Centered'], $sourcedBlock['attributes']);
    }
}
