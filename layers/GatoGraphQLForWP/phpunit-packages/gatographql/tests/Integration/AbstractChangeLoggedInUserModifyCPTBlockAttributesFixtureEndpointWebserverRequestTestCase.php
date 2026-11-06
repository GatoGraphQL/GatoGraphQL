<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Execute the operations with a user other than the "admin"
 */
abstract class AbstractChangeLoggedInUserModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase extends AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    use ChangeLoggedInUserWebserverRequestTestCaseTrait;
}
