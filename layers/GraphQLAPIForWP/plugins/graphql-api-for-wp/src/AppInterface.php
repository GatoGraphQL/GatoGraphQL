<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use PoP\Root\AppInterface as UpstreamAppInterface;

interface AppInterface extends UpstreamAppInterface
{
    public static function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
    ): void;

    public static function getMainPluginManager(): MainPluginManager;
    public static function getExtensionManager(): ExtensionManager;
}
