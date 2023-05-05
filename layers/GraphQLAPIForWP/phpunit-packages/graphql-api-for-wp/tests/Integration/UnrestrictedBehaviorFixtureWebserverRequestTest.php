<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class UnrestrictedBehaviorFixtureWebserverRequestTest extends AbstractUnrestrictedBehaviorFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-unrestricted-behavior';
    }
}
