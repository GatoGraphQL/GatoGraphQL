<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class AbstractWebserverRequestTestCase extends TestCase
{
    protected static ?Client $client;

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
    }

    

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
}
