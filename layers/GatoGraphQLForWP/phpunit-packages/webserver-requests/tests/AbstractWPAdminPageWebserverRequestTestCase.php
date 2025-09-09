<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractWPAdminPageWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    #[DataProvider('providePageEntries')]
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
    abstract public static function providePageEntries(): array;

    protected function executeAsserts(
        ResponseInterface $response,
    ): void {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringStartsWith('text/html', $response->getHeaderLine('content-type'));
    }
}
