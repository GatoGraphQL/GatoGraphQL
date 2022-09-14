<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class WildcardACLWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-wildcard-acl';
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPWPSchema\Posts\Module::class,
                \PoPWPSchema\Comments\Module::class,
                \PoPWPSchema\Users\Module::class,
                \PoPCMSSchema\UserStateAccessControl\Module::class,
                \PoPCMSSchema\UserStateWP\Module::class,
            ]
        ];
    }

    /**
     * @return array<class-string<CompilerPassInterface>>
     */
    protected static function getGraphQLServerApplicationContainerCompilerPassClasses(): array
    {
        return [
            ...parent::getGraphQLServerApplicationContainerCompilerPassClasses(),
            ...[
                \PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker\Container\CompilerPasses\WildcardACLConfigureAccessControlCompilerPass::class,
            ]
        ];
    }
}
