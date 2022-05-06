<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Execute the operations with a user other than the "admin"
 */
abstract class AbstractChangeLoggedInUserModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static ?string $differentUsername = null;
    protected static ?string $differentPassword = null;
    protected static array $differentWebserverPingOptions = [];
    protected static array $differentRequestBasicOptions = [];
    protected ?int $differentExpectedResponseStatusCode = null;

    /**
     * After the initial set-up, log the "admin" out,
     * and log a different user in
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Login a different user
        static::$differentUsername = $this->getDifferentLoginUsername();
        static::$differentPassword = $this->getDifferentLoginPassword();
        static::$differentWebserverPingOptions = $this->getDifferentWebserverPingOptions();
        static::$differentRequestBasicOptions = $this->getDifferentRequestBasicOptions();
        $this->differentExpectedResponseStatusCode = $this->getDifferentExpectedResponseStatusCode();
        static::setUpWebserverRequestTests();
    }

    protected static function getLoginUsername(): string
    {
        if (static::$differentUsername !== null) {
            return static::$differentUsername;
        }
        return parent::getLoginUsername();
    }

    protected static function getLoginPassword(): string
    {
        if (static::$differentPassword !== null) {
            return static::$differentPassword;
        }
        return parent::getLoginPassword();
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getWebserverPingOptions(): array
    {
        $options = parent::getWebserverPingOptions();
        foreach (static::$differentWebserverPingOptions as $key => $value) {
            // Merge arrays, or assign other values directly
            if (is_array($value)) {
                $options[$key] = array_merge(
                    $options[$key] ?? [],
                    $value
                );
                continue;
            }
            $options[$key] = $value;
        }
        return $options;
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getRequestBasicOptions(): array
    {
        $options = parent::getRequestBasicOptions();
        foreach (static::$differentRequestBasicOptions as $key => $value) {
            // Merge arrays, or assign other values directly
            if (is_array($value)) {
                $options[$key] = array_merge(
                    $options[$key] ?? [],
                    $value
                );
                continue;
            }
            $options[$key] = $value;
        }
        return $options;
    }

    protected function getExpectedResponseStatusCode(): int
    {
        if ($this->differentExpectedResponseStatusCode !== null) {
            return $this->differentExpectedResponseStatusCode;
        }
        return parent::getExpectedResponseStatusCode();
    }

    abstract protected function getDifferentLoginUsername(): string;

    abstract protected function getDifferentLoginPassword(): string;

    protected function getDifferentWebserverPingOptions(): array
    {
        return [];
    }

    protected function getDifferentRequestBasicOptions(): array
    {
        return [];
    }

    protected function getDifferentExpectedResponseStatusCode(): ?int
    {
        return null;
    }

    /**
     * Log the different user out, and again the "admin" in,
     * and then continue the original set-up
     */
    protected function tearDown(): void
    {
        // Login the "admin" user again
        static::$differentUsername = null;
        static::$differentPassword = null;
        static::$differentWebserverPingOptions = [];
        static::$differentRequestBasicOptions = [];
        $this->differentExpectedResponseStatusCode = null;
        static::setUpWebserverRequestTests();

        parent::tearDown();
    }
}
