<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\ComponentModel\App\AbstractComponentModelAppProxy;

/**
 * Keep all state in the application stored and accessible
 * through this class, so that regenerating this class
 * provides a new state.
 *
 * Needed for PHPUnit.
 */
class App extends AbstractComponentModelAppProxy implements AppInterface
{
}
