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

    abstract protected function getDifferentLoginUsername(): string;

    abstract protected function getDifferentLoginPassword(): string;

    /**
     * Log the different user out, and again the "admin" in,
     * and then continue the original set-up
     */
    protected function tearDown(): void
    {
        // Login the "admin" user
        static::$differentUsername = null;
        static::$differentPassword = null;
        static::setUpWebserverRequestTests();

        parent::tearDown();
    }
}
