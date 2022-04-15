<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use function getenv;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

abstract class AbstractWebserverRequestTestCase extends TestCase
{
    protected static ?Client $client = null;
    protected static ?CookieJar $cookieJar = null;
    protected static bool $enableTests = false;
    protected static ?string $skipTestsReason = null;
    protected static ?string $failTestsReason = null;

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
            self::$skipTestsReason = 'Test skipped for Continuous Integration';
            return;
        }

        $client = static::getClient();
        $options = static::getWebserverPingOptions();
        if (static::shareCookies()) {
            self::$cookieJar = static::createCookieJar();
            $options['cookies'] = self::$cookieJar;
        }        
        try {
            $response = $client->request(
                static::getWebserverPingMethod(),
                static::getWebserverPingURL(),
                $options
            );
            
            // If the user validation does not succeed, treat it as a failure
            $maybeErrorMessage = static::validateWebserverPingResponse($response, $options);
            if ($maybeErrorMessage !== null) {
                self::$failTestsReason = $maybeErrorMessage;
                return;
            }

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

    protected static function getWebserverPingMethod(): string
    {
        return 'GET';
    }

    /**
     * @param array<string,mixed> $options
     */
    protected static function validateWebserverPingResponse(
        ResponseInterface $response,
        array $options
    ): ?string {
        return null;
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getWebserverPingOptions(): array
    {
        return [];
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

    protected static function createCookieJar(): CookieJar
    {
        return CookieJar::fromArray(
            static::getCookies(),
            static::getWebserverDomain()
        );
    }

    /**
     * @return array<string,string>
     */
    protected static function getCookies(): array
    {
        return [];
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

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Skip the tests if the webserver is down.
         * Fail the tests if the user could not be authenticated.
         */
        if (!static::$enableTests) {
            if (self::$failTestsReason !== null) {
                $this->fail(self::$failTestsReason);
                return;
            }
            
            /** @var string self::$skipTestsReason */
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
        string $query = '',
        array $variables = [],
        string $expectedContentType = 'application/json',
        ?string $method = null,
    ): void {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];
        if ($params !== []) {
            $options['query'] = $params;
        }
        $body = '';
        if ($query !== '' || $variables !== []) {
            $body = json_encode([
                'query' => $query,
                'variables' => $variables,
            ]);
        }
        if ($body !== '') {
            $options['body'] = $body;
        }
        if (static::shareCookies()) {
            $options['cookies'] = self::$cookieJar;
        }        
        try {
            $response = $client->request(
                $method ?? $this->getMethod(),
                $endpointURL,
                $options
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
