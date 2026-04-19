<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeAPIs;

use PoPWPSchema\Blocks\ObjectModels\BlockType;

interface BlockTypeTypeAPIInterface
{
    /**
     * Retrieve a registered block type by name (e.g. "core/paragraph").
     */
    public function getBlockType(string $name): ?BlockType;

    /**
     * Retrieve all registered block types matching the given query.
     *
     * Supported query keys:
     *   - 'names'             : string[]   (limit results to these block names)
     *   - 'excludeNames'      : string[]   (exclude these block names)
     *   - 'nameSearch'        : string     (substring match on block name)
     *   - 'supports'          : array<string,mixed> (each key must equal-match in $blockType->supports)
     *   - 'hasRenderCallback' : bool       (require/forbid a render_callback)
     *
     * @param array<string,mixed> $query
     * @return BlockType[]
     */
    public function getBlockTypes(array $query = []): array;
}
