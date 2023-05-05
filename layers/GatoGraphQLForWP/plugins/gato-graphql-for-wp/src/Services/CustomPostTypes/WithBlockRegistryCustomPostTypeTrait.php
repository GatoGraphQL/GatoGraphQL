<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\CustomPostTypes;

use GatoGraphQL\GatoGraphQL\Registries\BlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EditorBlockInterface;

trait WithBlockRegistryCustomPostTypeTrait
{
    abstract protected function getBlockRegistry(): BlockRegistryInterface;

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array<string[]> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        $template = [];
        /** @var EditorBlockInterface[] */
        $blocks = $this->getBlockRegistry()->getEnabledBlocks();
        // Order them by priority
        uasort(
            $blocks,
            function (EditorBlockInterface $a, EditorBlockInterface $b): int {
                return $b->getBlockPriority() <=> $a->getBlockPriority();
            }
        );
        foreach ($blocks as $block) {
            $template[] = [$block->getBlockFullName()];
        }
        return $template;
    }
}
