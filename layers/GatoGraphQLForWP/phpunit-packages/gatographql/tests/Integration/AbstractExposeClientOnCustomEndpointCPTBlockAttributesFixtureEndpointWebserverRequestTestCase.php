<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractRequestClientCPTBlockAttributesWebserverRequestTestCase;

abstract class AbstractExposeClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTestCase extends AbstractRequestClientCPTBlockAttributesWebserverRequestTestCase
{
    protected function getClientURL(): string
    {
        return sprintf(
            '%s?%s=%s',
            static::getEndpoint(),
            RequestParams::VIEW,
            $this->getClientName(),
        );
    }

    abstract protected static function getEndpoint(): string;

    abstract protected function getClientName(): string;
}
