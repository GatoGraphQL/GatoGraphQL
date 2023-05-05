<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\BlockCategories;

interface BlockCategoryInterface
{
    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array;
}
