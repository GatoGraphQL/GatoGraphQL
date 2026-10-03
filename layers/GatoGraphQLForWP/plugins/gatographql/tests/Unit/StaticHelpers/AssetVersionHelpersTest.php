<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function file_put_contents;
use function mkdir;
use function rmdir;
use function sys_get_temp_dir;
use function touch;
use function uniqid;
use function unlink;

class AssetVersionHelpersTest extends TestCase
{
    private const FILE_TIME = 1700000000;

    private static string $assetFolder;

    public static function setUpBeforeClass(): void
    {
        self::$assetFolder = sys_get_temp_dir() . '/' . uniqid('asset-version-helpers-');
        mkdir(self::$assetFolder);
        file_put_contents(self::$assetFolder . '/script.js', '');
        touch(self::$assetFolder . '/script.js', self::FILE_TIME);
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$assetFolder . '/script.js');
        rmdir(self::$assetFolder);
    }

    #[DataProvider('provideGetAssetVersion')]
    public function testGetAssetVersion(string $pluginVersion, string $assetFileName, string $expected): void
    {
        $this->assertSame(
            $expected,
            AssetVersionHelpers::getAssetVersion($pluginVersion, self::$assetFolder . '/' . $assetFileName)
        );
    }

    /**
     * @return array<string,string[]>
     */
    public static function provideGetAssetVersion(): array
    {
        return [
            'development version gets the file time' => ['20.0.0-dev', 'script.js', '20.0.0-dev-' . self::FILE_TIME],
            'released version stays as it is' => ['20.0.0', 'script.js', '20.0.0'],
            'version merely containing "-dev" stays as it is' => ['20.0.0-dev.1', 'script.js', '20.0.0-dev.1'],
            'missing file keeps the version' => ['20.0.0-dev', 'missing.js', '20.0.0-dev'],
            'folder keeps the version' => ['20.0.0-dev', '', '20.0.0-dev'],
        ];
    }
}
