<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class WildcardPublicACLWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractACLWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getACLResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-wildcard-acl-public';
    }

    /**
     * @return class-string<CompilerPassInterface>|null
     */
    protected static function getACLCompilerPassClass(): ?string
    {
        return \PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker\Container\CompilerPasses\WildcardPublicACLConfigureAccessControlCompilerPass::class;
    }
}
