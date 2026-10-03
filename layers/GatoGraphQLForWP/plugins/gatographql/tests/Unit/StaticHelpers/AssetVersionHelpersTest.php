<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function clearstatcache;
use function file_put_contents;
use function filemtime;
use function mkdir;
use function rmdir;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

class AssetVersionHelpersTest extends TestCase
{
    private static string $assetFolder;

    public static function setUpBeforeClass(): void
    {
        self::$assetFolder = sys_get_temp_dir() . '/' . uniqid('asset-version-helpers-');
        mkdir(self::$assetFolder);
        file_put_contents(self::$assetFolder . '/script.js', '');
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$assetFolder . '/script.js');
        rmdir(self::$assetFolder);
    }

    public function testDevelopmentVersionGetsTheFileTime(): void
    {
        $assetFilePath = self::$assetFolder . '/script.js';
        clearstatcache(true, $assetFilePath);
        $this->assertSame(
            '20.0.0-dev-' . filemtime($assetFilePath),
            AssetVersionHelpers::getAssetVersion('20.0.0-dev', $assetFilePath)
        );
    }

    #[DataProvider('provideVersionKeptAsItIs')]
    public function testVersionKeptAsItIs(string $pluginVersion, string $assetFileName): void
    {
        $this->assertSame(
            $pluginVersion,
            AssetVersionHelpers::getAssetVersion($pluginVersion, self::$assetFolder . '/' . $assetFileName)
        );
    }

    /**
     * @return array<string,string[]>
     */
    public static function provideVersionKeptAsItIs(): array
    {
        return [
            'released version' => ['20.0.0', 'script.js'],
            'version merely containing "-dev"' => ['20.0.0-dev.1', 'script.js'],
            'missing file' => ['20.0.0-dev', 'missing.js'],
            'folder' => ['20.0.0-dev', ''],
        ];
    }
}
