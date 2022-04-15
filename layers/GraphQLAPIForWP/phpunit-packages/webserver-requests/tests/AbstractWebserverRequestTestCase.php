<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use function getenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

abstract class AbstractWebserverRequestTestCase extends TestCase
{
    protected static ?Client $client = null;
    protected static bool $enableTests = false;

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
            return;
        }

        $client = static::getClient();
        try {
            $response = $client->request(
                'GET',
                static::getWebserverPingURL()
            );
            // if ($response->getStatusCode() === 200) {
            // The webserver is working
            self::$enableTests = true;
            // }
        } catch (GuzzleException | RuntimeException) {
            // The webserver is down
        }
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
            $this->markTestSkipped(
                sprintf(
                    'Webserver under "%s" is not running',
                    static::getWebserverDomain()
                )
            );
        }
    }

    // protected function tearDown(): void
    // {
    //     parent::tearDown();
    // }

    /**
     * Execute a request against the webserver.
     * If not successful (eg: because the server is not running)
     * then skip all tests.
     *
     * @return ResponseInterface|string The response if successful, or the error message otherwise
     */
    protected function request(string $endpoint, array $params = [], string $body = '', ?string $method = null): ResponseInterface|string
    {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        try {
            return $client->request(
                $method ?? $this->getMethod(),
                $endpointURL,
                [
                    'query' => $params,
                    'body' => $body,
                ]
            );
        } catch (GuzzleException | RuntimeException $e) {
            return $e->getMessage();
        }
    }

    protected function getMethod(): string
    {
        return 'GET';
    }
}
