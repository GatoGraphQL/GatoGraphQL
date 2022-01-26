<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\App;

use PoP\ComponentModel\App as ComponentModelApp;
use PoP\ComponentModel\App\AbstractRootAppProxy;
use PoP\ComponentModel\AppInterface as ComponentModelAppInterface;
use PoP\ComponentModel\Engine\EngineState;

/**
 * Decorator wrapping the single class App hosting all the top-level instances,
 * plus addition of properties needed for the plugin.
 *
 * Using composition instead of inheritance, so that the original PoP\Root\App
 * is the single source of truth
 */
abstract class AbstractComponentModelAppProxy extends AbstractRootAppProxy implements ComponentModelAppInterface
{
    public static function getEngineState(): EngineState
    {
        return ComponentModelApp::getEngineState();
    }

    public static function regenerateEngineState(): void
    {
        ComponentModelApp::regenerateEngineState();
    }
}
