<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;

    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\ConvertCaseDirectives\Component::class,
        ];
    }
}
