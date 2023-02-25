<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class BehaviorHelpers
{
    public static function getDefaultBehavior(): string
    {
        return PluginEnvironment::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }
}
