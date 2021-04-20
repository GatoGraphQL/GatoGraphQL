<?php

declare(strict_types=1);

use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PoP\PoP\Extensions\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationInTraitRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeSetList;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Symfony\Component\Cache\Traits\AbstractAdapterTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\Cache\CacheTrait;
use Symfony\Contracts\Service\ServiceLocatorTrait;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [
        DowngradeSetList::PHP_80,
        DowngradeSetList::PHP_74,
        DowngradeSetList::PHP_73,
        DowngradeSetList::PHP_72,
    ]);

    /**
     * Hack to fix bug.
     *
     * DowngradeParameterTypeWideningRector is modifying function `clear` from vendor/symfony/cache/Adapter/AdapterInterface.php:
     *
     * from:
     *     public function clear(string $prefix = '');
     * to:
     *     public function clear($prefix = '');
     *
     * But the same modification is not being done in vendor/symfony/cache/Traits/AbstractAdapterTrait.php
     * So apply this change (and several similar others) manually
     *
     * @see https://github.com/leoloso/PoP/issues/597#issue-855005786
     */
    $services = $containerConfigurator->services();
    $services->set(AddParamTypeDeclarationInTraitRector::class)
        ->call('configure', [[
            AddParamTypeDeclarationInTraitRector::PARAMETER_TYPEHINTS => ValueObjectInliner::inline([
                new AddParamTypeDeclaration(AbstractAdapterTrait::class, 'clear', 0, new NullType()),
                new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'has', 0, new NullType()),
                new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'get', 0, new NullType()),
                new AddParamTypeDeclaration(CacheTrait::class, 'get', 0, new StringType()),
                new AddParamTypeDeclaration(CacheTrait::class, 'get', 2, new NullType()),
                new AddParamTypeDeclaration(CacheTrait::class, 'get', 3, new NullType()),
            ]),
        ]]);

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
