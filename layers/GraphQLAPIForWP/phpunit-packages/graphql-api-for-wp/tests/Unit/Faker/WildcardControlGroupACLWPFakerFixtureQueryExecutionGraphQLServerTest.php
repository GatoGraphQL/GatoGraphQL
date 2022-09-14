<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class WildcardControlGroupACLWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractACLWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getACLResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-wildcard-acl-control-group';
    }

    /**
     * @return class-string<CompilerPassInterface>|null
     */
    protected static function getACLCompilerPassClass(): ?string
    {
        return null;
    }
}
