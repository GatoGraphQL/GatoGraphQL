<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeAPIs;

use PoPWPSchema\Blocks\ObjectModels\BlockType;
use WP_Block_Type;
use WP_Block_Type_Registry;

class BlockTypeTypeAPI implements BlockTypeTypeAPIInterface
{
    public function getBlockType(string $name): ?BlockType
    {
        $blockType = WP_Block_Type_Registry::get_instance()->get_registered($name);
        if (!$blockType instanceof WP_Block_Type) {
            return null;
        }
        return new BlockType($blockType);
    }

    /**
     * @param array<string,mixed> $query
     * @return BlockType[]
     */
    public function getBlockTypes(array $query = []): array
    {
        $registered = WP_Block_Type_Registry::get_instance()->get_all_registered();

        /** @var string[]|null */
        $names = $query['names'] ?? null;
        if ($names !== null) {
            $registered = array_intersect_key($registered, array_flip($names));
        }

        /** @var string[]|null */
        $excludeNames = $query['excludeNames'] ?? null;
        if ($excludeNames !== null) {
            $registered = array_diff_key($registered, array_flip($excludeNames));
        }

        /** @var string|null */
        $nameSearch = $query['nameSearch'] ?? null;
        if ($nameSearch !== null && $nameSearch !== '') {
            $registered = array_filter(
                $registered,
                static fn (WP_Block_Type $blockType): bool => stripos($blockType->name, $nameSearch) !== false,
            );
        }

        /** @var array<string,mixed>|null */
        $supports = $query['supports'] ?? null;
        if (is_array($supports) && $supports !== []) {
            $registered = array_filter(
                $registered,
                static function (WP_Block_Type $blockType) use ($supports): bool {
                    $blockSupports = is_array($blockType->supports) ? $blockType->supports : [];
                    foreach ($supports as $supportKey => $expectedValue) {
                        if (!array_key_exists($supportKey, $blockSupports)) {
                            return false;
                        }
                        if ($blockSupports[$supportKey] !== $expectedValue) {
                            return false;
                        }
                    }
                    return true;
                },
            );
        }

        if (array_key_exists('hasRenderCallback', $query)) {
            $expected = (bool) $query['hasRenderCallback'];
            $registered = array_filter(
                $registered,
                static fn (WP_Block_Type $blockType): bool => is_callable($blockType->render_callback) === $expected,
            );
        }

        return array_values(array_map(
            static fn (WP_Block_Type $blockType): BlockType => new BlockType($blockType),
            $registered,
        ));
    }
}
