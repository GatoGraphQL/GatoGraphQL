<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback;

use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\ConfigurableSchemaFeedback\Component::class,
        ];
    }
}
