<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractRequestClientCPTBlockAttributesWebserverRequestTest;

abstract class AbstractExposeClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractRequestClientCPTBlockAttributesWebserverRequestTest
{
    protected function getClientURL(): string
    {
        return sprintf(
            '%s?%s=%s',
            $this->getEndpoint(),
            RequestParams::VIEW,
            $this->getClientName(),
        );
    }

    abstract protected function getEndpoint(): string;

    abstract protected function getClientName(): string;
}
