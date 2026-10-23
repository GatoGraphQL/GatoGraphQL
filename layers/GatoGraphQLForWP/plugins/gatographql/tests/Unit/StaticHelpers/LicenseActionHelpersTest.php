<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use PHPUnit\Framework\TestCase;

class LicenseActionHelpersTest extends TestCase
{
    private const STORED = ['pro' => 'KEY-PRO', 'access-control' => 'KEY-ACL', 'cache-control' => ''];

    public function testActivateActsOnTheTypedKeyOfThatLicenseOnly(): void
    {
        $this->assertSame(
            [
                'previous' => ['cache-control' => ''],
                'submitted' => ['cache-control' => 'KEY-NEW'],
                'store' => ['pro' => 'KEY-PRO', 'access-control' => 'KEY-ACL', 'cache-control' => 'KEY-NEW'],
            ],
            LicenseActionHelpers::getLicenseKeysForAction(
                LicenseActionHelpers::getActionValue(LicenseActionHelpers::ACTION_ACTIVATE, 'cache-control'),
                self::STORED,
                ['pro' => 'TYPED-BUT-NOT-SAVED', 'access-control' => '', 'cache-control' => ' KEY-NEW '],
            )
        );
    }

    public function testValidateKeepsTheStoredKey(): void
    {
        $this->assertSame(
            [
                'previous' => ['access-control' => 'KEY-ACL'],
                'submitted' => ['access-control' => 'KEY-ACL'],
                'store' => self::STORED,
            ],
            LicenseActionHelpers::getLicenseKeysForAction('validate:access-control', self::STORED, self::STORED)
        );
    }

    public function testDeactivateClearsTheKeyOfThatLicenseOnly(): void
    {
        $this->assertSame(
            [
                'previous' => ['pro' => 'KEY-PRO'],
                'submitted' => ['pro' => ''],
                'store' => ['pro' => '', 'access-control' => 'KEY-ACL', 'cache-control' => ''],
            ],
            LicenseActionHelpers::getLicenseKeysForAction('deactivate:pro', self::STORED, self::STORED)
        );
    }

    public function testSavingActsOnTheEditedKeysOnly(): void
    {
        $this->assertSame(
            [
                'previous' => ['access-control' => 'KEY-ACL', 'cache-control' => '', 'woocommerce' => 'KEY-WOO'],
                'submitted' => ['access-control' => '', 'cache-control' => 'KEY-NEW', 'woocommerce' => 'KEY-WOO-2'],
            ],
            LicenseActionHelpers::getLicenseKeysToSave(
                ['pro' => 'KEY-PRO', 'access-control' => 'KEY-ACL', 'cache-control' => '', 'woocommerce' => 'KEY-WOO'],
                ['pro' => ' KEY-PRO ', 'access-control' => '', 'cache-control' => 'KEY-NEW', 'woocommerce' => 'KEY-WOO-2'],
                ['pro', 'access-control', 'woocommerce'],
            )
        );
    }

    public function testSavingActivatesAnUnchangedKeyThatIsNotActiveYet(): void
    {
        $this->assertSame(
            [
                'previous' => ['pro' => 'KEY-PRO'],
                'submitted' => ['pro' => 'KEY-PRO'],
            ],
            LicenseActionHelpers::getLicenseKeysToSave(
                ['pro' => 'KEY-PRO', 'access-control' => 'KEY-ACL'],
                ['pro' => 'KEY-PRO', 'access-control' => 'KEY-ACL'],
                ['access-control'],
            )
        );
    }

    public function testSavingWithNoEditedKeyActsOnNone(): void
    {
        $this->assertSame(
            ['previous' => [], 'submitted' => []],
            LicenseActionHelpers::getLicenseKeysToSave(self::STORED, self::STORED, ['pro', 'access-control'])
        );
    }

    public function testAValueThatIsNoActionDoesNothing(): void
    {
        $this->assertNull(LicenseActionHelpers::getLicenseKeysForAction('delete:pro', self::STORED, self::STORED));
        $this->assertNull(LicenseActionHelpers::getLicenseKeysForAction('activate:', self::STORED, self::STORED));
        $this->assertNull(LicenseActionHelpers::getLicenseKeysForAction('activate', self::STORED, self::STORED));
        $this->assertNull(LicenseActionHelpers::getLicenseKeysForAction(['activate:pro'], self::STORED, self::STORED));
    }
}
