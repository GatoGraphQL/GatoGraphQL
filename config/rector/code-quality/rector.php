<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\DeadDocBlock\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadDocBlock\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * This Rector configuration imports the fully qualified classnames
 * using `use`, and removing it from the body.
 * Rule `AndAssignsToSeparateLinesRector` is not needed, but we need
 * to run at least 1 rule.
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUselessParamTagRector::class);
    $services->set(RemoveUselessReturnTagRector::class);
    
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    $monorepoDir = dirname(__DIR__);

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::BOOTSTRAP_FILES, [
        // full directory
        $monorepoDir . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
    ]);

    // files to skip downgrading
    $parameters->set(Option::PATHS, [
        $monorepoDir . '/src/*',
        $monorepoDir . '/layers/*',
    ]);

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        '*/migrate-*',
        '*/vendor/*',
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
    //     define('ABSPATH', $monorepoDir . '/vendor/wordpress/wordpress/');
    // }
};
