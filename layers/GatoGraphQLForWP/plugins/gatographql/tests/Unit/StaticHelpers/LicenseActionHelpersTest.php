<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use PHPUnit\Framework\TestCase;

class LicenseActionHelpersTest extends TestCase
{
    private const STORED = ['pro' => 'KEY-PRO', 'translations' => 'KEY-AIT', 'one-click' => ''];

    public function testActivateActsOnTheTypedKeyOfThatLicenseOnly(): void
    {
        $this->assertSame(
            [
                'previous' => ['one-click' => ''],
                'submitted' => ['one-click' => 'KEY-NEW'],
                'store' => ['pro' => 'KEY-PRO', 'translations' => 'KEY-AIT', 'one-click' => 'KEY-NEW'],
            ],
            LicenseActionHelpers::getLicenseKeysForAction(
                LicenseActionHelpers::getActionValue(LicenseActionHelpers::ACTION_ACTIVATE, 'one-click'),
                self::STORED,
                ['pro' => 'TYPED-BUT-NOT-SAVED', 'translations' => '', 'one-click' => ' KEY-NEW '],
            )
        );
    }

    public function testValidateKeepsTheStoredKey(): void
    {
        $this->assertSame(
            [
                'previous' => ['translations' => 'KEY-AIT'],
                'submitted' => ['translations' => 'KEY-AIT'],
                'store' => self::STORED,
            ],
            LicenseActionHelpers::getLicenseKeysForAction('validate:translations', self::STORED, self::STORED)
        );
    }

    public function testDeactivateClearsTheKeyOfThatLicenseOnly(): void
    {
        $this->assertSame(
            [
                'previous' => ['pro' => 'KEY-PRO'],
                'submitted' => ['pro' => ''],
                'store' => ['pro' => '', 'translations' => 'KEY-AIT', 'one-click' => ''],
            ],
            LicenseActionHelpers::getLicenseKeysForAction('deactivate:pro', self::STORED, self::STORED)
        );
    }

    public function testSavingActsOnTheEditedKeysOnly(): void
    {
        $this->assertSame(
            [
                'previous' => ['translations' => 'KEY-AIT', 'one-click' => '', 'woocommerce' => 'KEY-WOO'],
                'submitted' => ['translations' => '', 'one-click' => 'KEY-NEW', 'woocommerce' => 'KEY-WOO-2'],
            ],
            LicenseActionHelpers::getLicenseKeysToSave(
                ['pro' => 'KEY-PRO', 'translations' => 'KEY-AIT', 'one-click' => '', 'woocommerce' => 'KEY-WOO'],
                ['pro' => ' KEY-PRO ', 'translations' => '', 'one-click' => 'KEY-NEW', 'woocommerce' => 'KEY-WOO-2'],
                ['pro', 'translations', 'woocommerce'],
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
                ['pro' => 'KEY-PRO', 'translations' => 'KEY-AIT'],
                ['pro' => 'KEY-PRO', 'translations' => 'KEY-AIT'],
                ['translations'],
            )
        );
    }

    public function testSavingWithNoEditedKeyActsOnNone(): void
    {
        $this->assertSame(
            ['previous' => [], 'submitted' => []],
            LicenseActionHelpers::getLicenseKeysToSave(self::STORED, self::STORED, ['pro', 'translations'])
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
