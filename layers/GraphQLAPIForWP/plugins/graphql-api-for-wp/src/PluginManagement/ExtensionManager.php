<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtension;

class ExtensionManager
{
    /**
     * @var array<string, AbstractExtension>
     */
    private static array $extensionClassInstances = [];

    public static function register(AbstractExtension $extension): AbstractExtension
    {
        self::$extensionClassInstances[get_class($extension)] = $extension;
        return $extension;
    }
}
