<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration;

use PoP\BasicService\Component\AbstractComponent;

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
            \PoP\ComponentModel\Component::class,
        ];
    }
}
