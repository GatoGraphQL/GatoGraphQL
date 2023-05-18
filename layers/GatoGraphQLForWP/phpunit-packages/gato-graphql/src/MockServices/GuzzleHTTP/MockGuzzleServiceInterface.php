<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\MockServices\GuzzleHTTP;

use GuzzleHttp\Handler\MockHandler;
use PoP\GuzzleHTTP\Services\GuzzleServiceInterface;

interface MockGuzzleServiceInterface extends GuzzleServiceInterface
{
    public function getMockHandler(): MockHandler;
}
