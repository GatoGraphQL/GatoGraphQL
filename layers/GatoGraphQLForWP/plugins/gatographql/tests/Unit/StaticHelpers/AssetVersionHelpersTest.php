<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function file_put_contents;
use function is_dir;
use function mkdir;
use function rmdir;
use function sys_get_temp_dir;
use function touch;
use function uniqid;
use function unlink;

class AssetVersionHelpersTest extends TestCase
{
    private const PLUGIN_URL = 'https://example.com/wp-content/plugins/my-plugin/';
    private const FILE_TIME = 1700000000;

    private static string $pluginFolder;

    public static function setUpBeforeClass(): void
    {
        self::$pluginFolder = sys_get_temp_dir() . '/' . uniqid('asset-version-helpers-');
        mkdir(self::$pluginFolder . '/assets/js', 0777, true);
        file_put_contents(self::$pluginFolder . '/assets/js/script.js', '');
        touch(self::$pluginFolder . '/assets/js/script.js', self::FILE_TIME);
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$pluginFolder . '/assets/js/script.js');
        foreach (['/assets/js', '/assets', ''] as $dir) {
            if (is_dir(self::$pluginFolder . $dir)) {
                rmdir(self::$pluginFolder . $dir);
            }
        }
    }

    #[DataProvider('provideAddFileTimeToDevelopmentAssetVersion')]
    public function testAddFileTimeToDevelopmentAssetVersion(string $src, string $expected): void
    {
        $this->assertSame(
            $expected,
            AssetVersionHelpers::addFileTimeToDevelopmentAssetVersion($src, self::PLUGIN_URL, self::$pluginFolder)
        );
    }

    /**
     * @return array<string,string[]>
     */
    public static function provideAddFileTimeToDevelopmentAssetVersion(): array
    {
        $script = self::PLUGIN_URL . 'assets/js/script.js';
        $devVersion = '20.0.0-dev-' . self::FILE_TIME;
        return [
            'development-version' => [
                $script . '?ver=20.0.0-dev',
                $script . '?ver=' . $devVersion,
            ],
            'development-version-among-other-args' => [
                $script . '?a=1&ver=20.0.0-dev&b=20.0.0-dev',
                $script . '?a=1&ver=' . $devVersion . '&b=20.0.0-dev',
            ],
            'development-version-after-an-arg-ending-in-ver' => [
                $script . '?aver=20.0.0-dev&ver=20.0.0-dev',
                $script . '?aver=20.0.0-dev&ver=' . $devVersion,
            ],
            'development-version-before-a-fragment' => [
                $script . '?ver=20.0.0-dev#top',
                $script . '?ver=' . $devVersion . '#top',
            ],
            'released-version' => [
                $script . '?ver=20.0.0',
                $script . '?ver=20.0.0',
            ],
            'content-hash-version' => [
                $script . '?ver=3a7bd3e2360a3d29eea4',
                $script . '?ver=3a7bd3e2360a3d29eea4',
            ],
            'no-version' => [
                $script,
                $script,
            ],
            'another-plugin-sharing-the-url-prefix' => [
                'https://example.com/wp-content/plugins/my-plugin-extension/assets/js/script.js?ver=20.0.0-dev',
                'https://example.com/wp-content/plugins/my-plugin-extension/assets/js/script.js?ver=20.0.0-dev',
            ],
            'another-site' => [
                'https://cdn.example.org/assets/js/script.js?ver=20.0.0-dev',
                'https://cdn.example.org/assets/js/script.js?ver=20.0.0-dev',
            ],
            'missing-file' => [
                self::PLUGIN_URL . 'assets/js/missing.js?ver=20.0.0-dev',
                self::PLUGIN_URL . 'assets/js/missing.js?ver=20.0.0-dev',
            ],
        ];
    }
}
