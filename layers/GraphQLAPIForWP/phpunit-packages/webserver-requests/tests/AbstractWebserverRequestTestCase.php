<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class AbstractWebserverRequestTestCase extends TestCase
{
    protected static ?Client $client = null;
    protected static bool $skipTests = false;

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
        $client = static::getClient();
        $response = $client->request(
            static::getMethod(),
            static::getWebserverPingURL()
        );
        if ($response->getStatusCode() === 200) {
            // The webserver is working
            return;
        }
        // The webserver is down. Mark all tests to be skipped
        self::$skipTests = true;
    }

    protected static function getMethod(): string
    {
        return 'GET';
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
        if (static::$skipTests) {
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
}
