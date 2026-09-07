<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Services;

use PoPSchema\SchemaCommons\Constants\Behaviors;
use PHPUnit\Framework\TestCase;

class AllowOrDenySettingsServiceTest extends TestCase
{
    private function getService(): AllowOrDenySettingsService
    {
        return new AllowOrDenySettingsService();
    }

    public function testEmptyEntriesAllowUnderDenyBehavior(): void
    {
        $service = $this->getService();
        $this->assertTrue($service->isEntryAllowed('anything', [], Behaviors::DENY));
        $this->assertFalse($service->isEntryAllowed('anything', [], Behaviors::ALLOW));
    }

    /**
     * @dataProvider provideProtectedNameVariants
     */
    public function testDenylistCannotBeBypassedByNameVariant(string $name): void
    {
        $service = $this->getService();
        $this->assertFalse(
            $service->isEntryAllowed($name, ['plugin_api_key'], Behaviors::DENY)
        );
    }

    /**
     * @dataProvider provideProtectedNameVariants
     */
    public function testDenylistRegexCannotBeBypassedByNameVariant(string $name): void
    {
        $service = $this->getService();
        $this->assertFalse(
            $service->isEntryAllowed($name, ['/^plugin_api_key$/'], Behaviors::DENY)
        );
    }

    /**
     * @dataProvider provideProtectedNameVariants
     */
    public function testDenylistUppercaseRegexCannotBeBypassedByNameVariant(string $name): void
    {
        $service = $this->getService();
        $this->assertFalse(
            $service->isEntryAllowed($name, ['/^PLUGIN_API_KEY$/'], Behaviors::DENY)
        );
    }

    public function testDenylistRegexStillAllowsUnrelatedNames(): void
    {
        $service = $this->getService();
        $this->assertTrue(
            $service->isEntryAllowed('blogname', ['/^plugin_api_key$/'], Behaviors::DENY)
        );
    }

    /**
     * @return array<string,array{0:string}>
     */
    public static function provideProtectedNameVariants(): array
    {
        return [
            'exact' => ['plugin_api_key'],
            'uppercase' => ['PLUGIN_API_KEY'],
            'mixed case' => ['Plugin_Api_Key'],
            'trailing space' => ['plugin_api_key '],
            'uppercase and trailing space' => ['PLUGIN_API_KEY '],
            'leading space' => [' plugin_api_key'],
            'surrounding space' => ['  plugin_api_key  '],
        ];
    }

    public function testDenylistStillAllowsUnrelatedNames(): void
    {
        $service = $this->getService();
        $this->assertTrue(
            $service->isEntryAllowed('blogname', ['plugin_api_key'], Behaviors::DENY)
        );
        $this->assertTrue(
            $service->isEntryAllowed('plugin_api_key_suffix', ['plugin_api_key'], Behaviors::DENY)
        );
    }

    /**
     * @dataProvider provideProtectedNameVariants
     */
    public function testAllowlistAcceptsNameVariantsOfTheSameEntry(string $name): void
    {
        $service = $this->getService();
        $this->assertTrue(
            $service->isEntryAllowed($name, ['plugin_api_key'], Behaviors::ALLOW)
        );
    }

    public function testAllowlistStillRejectsUnlistedNames(): void
    {
        $service = $this->getService();
        $this->assertFalse(
            $service->isEntryAllowed('plugin_api_key', ['blogname'], Behaviors::ALLOW)
        );
    }
}
