<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait ResponseHeaderWebserverRequestTestTrait
{
    protected function getHeaderName(): string
    {
        return 'Access-Control-Allow-Headers';
    }
}
