<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractWPAdminPageWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * @dataProvider providePageEntries
     */
    public function testPagesExist(
        string $endpoint,
    ): void {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = static::getRequestBasicOptions();
        $response = $client->get(
            $endpointURL,
            $options
        );

        $this->executeAsserts($response);
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function providePageEntries(): array;

    protected function executeAsserts(
        ResponseInterface $response,
    ): void {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringStartsWith('text/html', $response->getHeaderLine('content-type'));
    }
}
