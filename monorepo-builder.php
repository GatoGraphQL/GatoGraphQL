<?php

declare(strict_types=1);

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command\PackageEntriesJsonCommand;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command\SourcePackagesCommand;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command\SymlinkLocalPackageCommand;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\PackageEntriesJsonProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackagesProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
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

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::DATA_TO_REMOVE, [
        'require-dev' => [
            'wikimedia/composer-merge-plugin' => '*',
            // 'phpunit/phpunit' => '*',
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
    ]);

    $services = $containerConfigurator->services();
    $services->defaults()
        ->autowire()
        ->autoconfigure();

    /** Commands */
    $services
        ->set(PackageEntriesJsonProvider::class)
        ->set(PackageEntriesJsonCommand::class)
        ->set(SourcePackagesProvider::class)
        ->set(SourcePackagesCommand::class)
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
