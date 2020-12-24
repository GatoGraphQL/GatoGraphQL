<?php

declare(strict_types=1);

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

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // where are the packages located?
    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __DIR__ . '/layers/Engine/packages',
        __DIR__ . '/layers/API/packages',
        __DIR__ . '/layers/Schema/packages',
        __DIR__ . '/layers/GraphQLByPoP/packages',
        __DIR__ . '/layers/GraphQLAPIForWP/plugins',
        __DIR__ . '/layers/SiteBuilder/packages',
        __DIR__ . '/layers/Wassup/packages',
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

    # release workers - in order to execute
    $services->set(UpdateReplaceReleaseWorker::class);
    $services->set(SetCurrentMutualDependenciesReleaseWorker::class);
    $services->set(AddTagToChangelogReleaseWorker::class);
    // $services->set(TagVersionReleaseWorker::class);
    // $services->set(PushTagReleaseWorker::class);
    $services->set(SetNextMutualDependenciesReleaseWorker::class);
    $services->set(UpdateBranchAliasReleaseWorker::class);
    // $services->set(PushNextDevReleaseWorker::class);
};