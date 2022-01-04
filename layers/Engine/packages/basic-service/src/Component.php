<?php

declare(strict_types=1);

namespace PoP\BasicService;

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
            \PoP\Hooks\Component::class,
            \PoP\Translation\Component::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getDevDependedComponentClasses(): array
    {
        return [
            \PoP\HooksPHP\Component::class,
            \PoP\TranslationMock\Component::class,
        ];
    }
}
