<?php

declare(strict_types=1);

require_once 'monorepo-builder/vendor/autoload.php';

use PoP\PoP\Symplify\MonorepoBuilder\Command\PackageEntriesJsonCommand;
use PoP\PoP\Symplify\MonorepoBuilder\Command\SymlinkLocalPackageCommand;
use PoP\PoP\Symplify\MonorepoBuilder\Json\PackageEntriesJsonProvider;
use PoP\PoP\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $packagePathOrganizations = [
        'layers/Engine/packages' => 'getpop',
        'layers/API/packages' => 'getpop',
        'layers/Schema/packages' => 'PoPSchema',
        'layers/GraphQLByPoP/packages' => 'GraphQLByPoP',
        'layers/GraphQLAPIForWP/plugins' => 'GraphQLAPI',
        // 'layers/GraphQLAPIForWP/subplugins' => 'GraphQLAPI',
        'layers/SiteBuilder/packages' => 'getpop',
        'layers/Wassup/packages' => 'PoPSites-Wassup',
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

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::DATA_TO_REMOVE, [
        'require-dev' => [
            'wikimedia/composer-merge-plugin' => '*',
            'phpunit/phpunit' => '*',
        ],
        // 'minimum-stability' => 'dev',
        // 'prefer-stable' => true,
    ]);

    // // how skip packages in loaded direectories?
    // $parameters->set(Option::PACKAGE_DIRECTORIES_EXCLUDES, [__DIR__ . '/packages/secret-package']);

    // // "merge" command related

    // // what extra parts to add after merge?
    // $parameters->set(Option::DATA_TO_APPEND, [
    //     'autoload-dev' => [
    //         'psr-4' => [
    //             'Symplify\Tests\\' => 'tests',
    //         ],
    //     ],
    //     'require-dev' => [
    //         'phpstan/phpstan' => '^0.12',
    //     ],
    // ]);

    // $parameters->set(Option::DATA_TO_REMOVE, [
    //     'require' => [
    //         // the line is removed by key, so version is irrelevant, thus *
    //         'phpunit/phpunit' => '*',
    //     ],
    // ]);

    $services = $containerConfigurator->services();
    $services->defaults()
        ->autowire()
        ->autoconfigure();

    /** Commands */
    $services
        ->set(PackageEntriesJsonProvider::class)
        ->set(PackageEntriesJsonCommand::class)
        ->set(SymlinkLocalPackageCommand::class);

    /** release workers - in order to execute */
    $services->set(UpdateReplaceReleaseWorker::class);
    $services->set(SetCurrentMutualDependenciesReleaseWorker::class);
    $services->set(AddTagToChangelogReleaseWorker::class);
    // $services->set(TagVersionReleaseWorker::class);
    // $services->set(PushTagReleaseWorker::class);
    $services->set(SetNextMutualDependenciesReleaseWorker::class);
    $services->set(UpdateBranchAliasReleaseWorker::class);
    // $services->set(PushNextDevReleaseWorker::class);
};