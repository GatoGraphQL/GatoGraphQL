<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting;

use PHPUnit\Framework\TestCase;

/**
 * Base class that does nothing, but that it allows
 * the plugin to be considered for PHPStan,
 * since the /tests folder is required
 */
abstract class AbstractPluginTestCase extends TestCase
{
}
