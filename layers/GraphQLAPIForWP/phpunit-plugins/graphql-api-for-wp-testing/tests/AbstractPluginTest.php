<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting;

use PHPUnit\Framework\TestCase;

/**
 * Base class that does nothing, but that it allows
 * the plugin to be considered for PHPStan,
 * since the /tests folder is required
 */
abstract class AbstractPluginTest extends TestCase
{
}
