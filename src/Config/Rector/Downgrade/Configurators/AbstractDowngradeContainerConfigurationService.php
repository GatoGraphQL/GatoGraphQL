<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

// use DateTimeInterface;
// use PoPSchema\SchemaCommons\Polyfill\PHP72\DateTimeInterface as PolyfillDateTimeInterface;
// use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
// use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeLevelSetList;

abstract class AbstractDowngradeContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        $this->rectorConfig->sets([
            // When this is enabled, generating the plugin in GitHub takes more than 30 min!
            // CustomDowngradeSetList::BEFORE_DOWNGRADE,

            /**
             * Watch out! Here it should use `DOWN_TO_PHP_74` to downgrade to PHP 7.4,
             * but there's a bug in which this code:
             *
             *   \set_error_handler(static fn($type, $message, $file, $line) => throw new \ErrorException($message, 0, $type, $file, $line));
             *
             * from file:
             *
             *   vendor/symfony/cache/Traits/FilesystemCommonTrait.php
             *
             * is not being downgraded, then the plugin explodes.
             *
             * (It should become:
             *
             *   \set_error_handler(static function ($type, $message, $file, $line) {
             *     throw new \ErrorException($message, 0, $type, $file, $line);
             *   });
             *
             * )
             *
             * To avoid this problem, for the time being, use set `DOWN_TO_PHP_73`
             *
             * @todo Upgrade Rector to v1 and try again
             * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2906
             */
            // DowngradeLevelSetList::DOWN_TO_PHP_74,
            DowngradeLevelSetList::DOWN_TO_PHP_73,
        ]);

        /**
         * @todo Uncomment this code
         * Currently it doesn't work, maybe because `RenameClassConstFetchRector`
         * doesn't handle interfaces, so it doesn't replace `DateTimeInterface`
         * Solution: Create a similar rule
         */
        // // Must also replace DateTimeInterface::ATOM for PHP 7.2
        // $this->rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [new RenameClassAndConstFetch(DateTimeInterface::class, 'ATOM', PolyfillDateTimeInterface::class, 'ATOM')]);

        // is your PHP version different from the one your refactor to? [default: your PHP version]
        $this->rectorConfig->phpVersion(PhpVersion::PHP_74);

        // Do not change the code, other than the required rules
        $this->rectorConfig->importNames(false, false);
        $this->rectorConfig->importShortClasses(false);

        // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
        if ($bootstrapFiles = $this->getBootstrapFiles()) {
            $this->rectorConfig->bootstrapFiles($bootstrapFiles);
        }

        // files to skip downgrading
        if ($skip = $this->getSkip()) {
            $this->rectorConfig->skip($skip);
        }
    }

    /**
     * @return string[]
     */
    protected function getBootstrapFiles(): array
    {
        return [
            $this->rootDirectory . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        ];
    }

    /**
     * @return string[]
     */
    protected function getSkip(): array
    {
        return [
            // Skip tests
            '*/tests/*',
            '*/test/*',
            '*/Test/*',
        ];
    }
}
