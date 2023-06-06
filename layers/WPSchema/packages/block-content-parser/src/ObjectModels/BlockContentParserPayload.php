<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser\ObjectModels;

use stdClass;

class BlockContentParserPayload
{
    /**
     * @param array<stdClass> $blocks
     * @param string[]|null $warnings
     */
    public function __construct(
        public readonly array $blocks,
        public readonly ?array $warnings,
    ) {
    }
}
