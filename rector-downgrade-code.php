<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp72\Rector\FunctionLike\DowngradeObjectTypeDeclarationRector;
use Rector\Set\ValueObject\DowngradeSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [
        DowngradeSetList::PHP_80,
        DowngradeSetList::PHP_74,
        DowngradeSetList::PHP_73,
        // DowngradeSetList::PHP_72,
    ]);

    /**
     * Downgrade PHP 7.2 - Temporarily skip buggy rules:
     * - DowngradeParameterTypeWideningRector
     *   @see https://github.com/leoloso/PoP/issues/597)
     * - DowngradePregUnmatchedAsNullConstantRector
     *   @see https://github.com/leoloso/PoP/issues/598
     *
     * Source code from: vendor/rector/rector/config/set/downgrade-php72.php
     */
    $services = $containerConfigurator->services();
    $services->set(DowngradeObjectTypeDeclarationRector::class);
    // $services->set(DowngradeParameterTypeWideningRector::class);
    // $services->set(DowngradePregUnmatchedAsNullConstantRector::class);

    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);

    // Do not change the code, other than the required rules
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::BOOTSTRAP_FILES, [
        // full directory
        __DIR__ . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        // Avoid error: "Class EM_Event not found"
        __DIR__ . '/stubs/wpackagist-plugin/events-manager/em-stubs.php',
    ]);

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // Ignore downgrading the monorepo source
        __DIR__ . '/src/*',

        // Skip tests
        '*/tests/*',
        '*/test/*',
        '*/Test/*',

        // Even when downgrading all packages, skip Symfony's polyfills
        __DIR__ . '/vendor/symfony/polyfill-*',

        // Skip since they are not needed and they fail
        __DIR__ . '/vendor/composer/*',
        __DIR__ . '/vendor/lkwdwrd/wp-muplugin-loader/*',

        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        __DIR__ . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        __DIR__ . '/vendor/symfony/cache/DoctrineProvider.php',
        __DIR__ . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        __DIR__ . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
    ]);

    // /**
    //  * This constant is defined in wp-load.php, but never loaded.
    //  * It is read when resolving class WP_Upgrader in Plugin.php.
    //  * Define it here again, or otherwise Rector fails with message:
    //  *
    //  * "PHP Warning:  Use of undefined constant ABSPATH -
    //  * assumed 'ABSPATH' (this will throw an Error in a future version of PHP)
    //  * in .../graphql-api-for-wp/vendor/wordpress/wordpress/wp-admin/includes/class-wp-upgrader.php
    //  * on line 13"
    //  */
    // /** Define ABSPATH as this file's directory */
    // if (!defined('ABSPATH')) {
    //     define('ABSPATH', __DIR__ . '/vendor/wordpress/wordpress/');
    // }
};
