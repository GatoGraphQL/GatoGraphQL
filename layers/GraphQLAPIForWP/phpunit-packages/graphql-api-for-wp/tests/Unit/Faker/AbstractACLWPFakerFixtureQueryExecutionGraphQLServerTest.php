<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use PHPUnitForGraphQLAPI\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTest;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

abstract class AbstractACLWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-acl';
    }

    final protected function getResponseFixtureFolder(): string
    {
        return $this->getACLResponseFixtureFolder();
    }

    abstract protected function getACLResponseFixtureFolder(): string;

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPWPSchema\Posts\Module::class,
                \PoPCMSSchema\PostCategoriesWP\Module::class,
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
        $graphQLServerApplicationContainerCompilerPassClasses = parent::getGraphQLServerApplicationContainerCompilerPassClasses();
        $aclCompilerPassClass = static::getACLCompilerPassClass();
        if ($aclCompilerPassClass === null) {
            return $graphQLServerApplicationContainerCompilerPassClasses;
        }
        return [
            ...$graphQLServerApplicationContainerCompilerPassClasses,
            ...[
                $aclCompilerPassClass,
            ]
        ];
    }

    /**
     * @return class-string<CompilerPassInterface>|null
     */
    abstract protected static function getACLCompilerPassClass(): ?string;
}
