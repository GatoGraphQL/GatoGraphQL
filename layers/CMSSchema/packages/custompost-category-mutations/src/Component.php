<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations;

use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostMutations\Component::class,
            \PoPCMSSchema\Categories\Component::class,
        ];
    }
}
