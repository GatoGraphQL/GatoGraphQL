<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function getenv;

abstract class AbstractWebserverRequestTestCase extends TestCase
{
    protected static ?Client $client = null;
    protected static bool $enableTests = false;
    protected static string $skipTestsReason = '';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::setUpWebserverRequestTests();
    }

    /**
     * Execute a request against the webserver.
     * If not successful (eg: because the server is not running)
     * then skip all tests.
     */
    protected static function setUpWebserverRequestTests(): void
    {
        // Skip running tests in Continuous Integration?
        if (static::isContinuousIntegration() && static::skipTestsInContinuousIntegration()) {
            self::$skipTestsReason = sprintf(
                'Test skipped for Continuous Integration',
                static::getWebserverDomain()
            );
            return;
        }

        $client = static::getClient();
        try {
            $response = $client->request(
                'GET',
                static::getWebserverPingURL()
            );
            // The webserver is working
            self::$enableTests = true;
            return;
        } catch (GuzzleException | RuntimeException) {
            // The webserver is down
        }

        self::$skipTestsReason = sprintf(
            'Webserver under "%s" is not running',
            static::getWebserverDomain()
        );
    }

    /**
     * Check if running the test on Continuous Integration.
     *
     * Currently supported:
     *
     * - GitHub Actions
     *
     * @see https://docs.github.com/en/enterprise-cloud@latest/actions/learn-github-actions/environment-variables
     */
    protected static function isContinuousIntegration(): bool
    {
        // GitHub Actions
        if (getenv('GITHUB_ACTION') !== false) {
            return true;
        }
        return false;
    }

    /**
     * Indicate to not run tests on CI (eg: GitHub)
     */
    protected static function skipTestsInContinuousIntegration(): bool
    {
        return true;
    }

    protected static function getWebserverPingURL(): string
    {
        return static::getWebserverHomeURL();
    }

    protected static function getWebserverHomeURL(): string
    {
        return (static::useSSL() ? 'https' : 'http') . '://' . static::getWebserverDomain();
    }

    protected static function useSSL(): bool
    {
        return false;
    }

    abstract protected static function getWebserverDomain(): string;

    protected static function getClient(): Client
    {
        if (self::$client === null) {
            self::$client = static::createClient();
        }
        return self::$client;
    }

    protected static function createClient(): Client
    {
        return new Client(
            [
                'cookies' => static::shareCookies(),
            ]
        );
    }

    /**
     * Indicate if to share cookies across requests (Cookie Jar)
     *
     * @see https://docs.guzzlephp.org/en/stable/quickstart.html#cookies
     */
    protected static function shareCookies(): bool
    {
        return false;
    }

    public static function tearDownAfterClass(): void
    {
        static::tearDownWebserverRequestTests();
        parent::tearDownAfterClass();
    }

    protected static function tearDownWebserverRequestTests(): void
    {
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Skip the tests if the webserver is down
        if (!static::$enableTests) {
            $this->markTestSkipped(self::$skipTestsReason);
        }
    }

    /**
     * @dataProvider provideEndpointEntries
     */
    public function testEndpoints(
        string $expectedResponseBody,
        string $endpoint,
        array $params = [],
        string $body = '',
        string $expectedContentType = 'application/json',
        ?string $method = null,
    ): void {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        try {
            $response = $client->request(
                $method ?? $this->getMethod(),
                $endpointURL,
                [
                    'query' => $params,
                    'body' => $body,
                ]
            );
        } catch (ClientException $e) {
            /**
             * A 404 is a Client Exception.
             * It's a failure, not an error.
             */
            $this->fail($e->getMessage());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedContentType, $response->getHeaderLine('content-type'));
        $this->assertJsonStringEqualsJsonString($expectedResponseBody, $response->getBody()->__toString());
    }

    /**
     * @return array<string,array<mixed>>
     */
    abstract protected function provideEndpointEntries(): array;

    protected function getMethod(): string
    {
        return 'POST';
    }
}
