<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class PrivateACLWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractACLWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getWildcardResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-acl-private';
    }

    /**
     * @return class-string<CompilerPassInterface>|null
     */
    protected static function getWildcardCompilerPassClass(): ?string
    {
        return \PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker\Container\CompilerPasses\PrivateACLConfigureAccessControlCompilerPass::class;
    }
}
