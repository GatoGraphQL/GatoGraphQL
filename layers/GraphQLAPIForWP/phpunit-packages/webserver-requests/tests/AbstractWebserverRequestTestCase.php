<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\Environment;
use PHPUnitForGraphQLAPI\WebserverRequests\Exception\UnauthenticatedUserException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

use function getenv;

abstract class AbstractWebserverRequestTestCase extends TestCase
{
    protected static ?Client $client = null;
    protected static ?CookieJar $cookieJar = null;
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
        // Skip running tests if the domain has not been configured
        if (static::getWebserverDomain() === '') {
            self::$skipTestsReason = 'Webserver domain not configured';
            return;
        }

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
                throw new UnauthenticatedUserException($maybeErrorMessage);
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

    protected static function getWebserverDomain(): string
    {
        return Environment::getIntegrationTestsWebserverDomain();
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
         */
        if (!static::$enableTests) {
            $this->markTestSkipped(self::$skipTestsReason);
        }
    }
}
