<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class SkipDowngradeTestFilesDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * @return string[]
     */
    public function getSkipDowngradeTestFiles(): array
    {
        return [
            // 'vendor/symfony/cache/Adapter/MemcachedAdapter.php',
            'vendor/symfony/cache/DataCollector/CacheDataCollector.php',
            'vendor/symfony/cache/DoctrineProvider.php',
            'vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
            'vendor/symfony/dotenv/Command/DebugCommand.php',
            'vendor/symfony/dotenv/Command/DotenvDumpCommand.php',
            'vendor/symfony/string/Slugger/AsciiSlugger.php',
        ];
    }
}
