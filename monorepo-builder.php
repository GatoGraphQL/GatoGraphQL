<?php

declare(strict_types=1);

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Neon\NeonPrinter;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $packagePathOrganizations = [
        'layers/Engine/packages' => 'getpop',
        'layers/API/packages' => 'getpop',
        'layers/Schema/packages' => 'PoPSchema',
        'layers/GraphQLByPoP/packages' => 'GraphQLByPoP',
        'layers/GraphQLAPIForWP/packages' => 'GraphQLAPI',
        'layers/GraphQLAPIForWP/plugins' => 'GraphQLAPI',
        // 'layers/GraphQLAPIForWP/subplugins' => 'GraphQLAPI',
        'layers/SiteBuilder/packages' => 'getpop',
        'layers/Wassup/packages' => 'PoPSites-Wassup',
        'layers/Misc/packages' => 'leoloso',
    ];
    $parameters->set(CustomOption::PACKAGE_ORGANIZATIONS, $packagePathOrganizations);
    $parameters->set(Option::PACKAGE_DIRECTORIES, array_map(
        function (string $packagePath): string {
            return __DIR__ . '/' . $packagePath;
        },
        array_keys($packagePathOrganizations)
    ));
    $parameters->set(Option::PACKAGE_DIRECTORIES_EXCLUDES, [
        'graphql-api-for-wp/wordpress',
    ]);

    /**
     * Plugins to generate
     */
    $parameters->set(CustomOption::PLUGIN_CONFIG_ENTRIES, [
        [
            'path' => 'layers/GraphQLAPIForWP/plugins/graphql-api-for-wp',
            'zip_file' => 'graphql-api.zip',
            'main_file' => 'graphql-api.php',
        ],
    ]);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::DATA_TO_REMOVE, [
        'require-dev' => [
            // 'phpunit/phpunit' => '*',
            'wpackagist-plugin/block-metadata' => '*',
            'wpackagist-plugin/events-manager' => '*',
        ],
        // 'minimum-stability' => 'dev',
        // 'prefer-stable' => true,
    ]);

    // Install also the monorepo-builder! So it can be used in CI
    $parameters->set(Option::DATA_TO_APPEND, [
        'require-dev' => [
            'symplify/monorepo-builder' => '^9.0',
        ],
        'autoload' => [
            'psr-4' => [
                'PoP\\PoP\\'=> 'src',
            ],
        ],
        // 'extra' => [
        //     'installer-paths' => [
        //         'wordpress/wp-content/plugins/{$name}/' => [
        //             'type:wordpress-plugin',
        //         ]
        //     ]
        // ],
    ]);

    $services = $containerConfigurator->services();
    $services->defaults()
        ->autowire()
        ->autoconfigure();

    /** Set all services */
    $services
        ->set(NeonPrinter::class) // Required to inject into PHPStanNeonContentProvider
        ->load('PoP\\PoP\\', 'src/*');

    /** release workers - in order to execute */
    $services->set(UpdateReplaceReleaseWorker::class);
    $services->set(SetCurrentMutualDependenciesReleaseWorker::class);
    $services->set(AddTagToChangelogReleaseWorker::class);
    $services->set(TagVersionReleaseWorker::class);
    $services->set(PushTagReleaseWorker::class);
    $services->set(SetNextMutualDependenciesReleaseWorker::class);
    $services->set(UpdateBranchAliasReleaseWorker::class);
    $services->set(PushNextDevReleaseWorker::class);
};
