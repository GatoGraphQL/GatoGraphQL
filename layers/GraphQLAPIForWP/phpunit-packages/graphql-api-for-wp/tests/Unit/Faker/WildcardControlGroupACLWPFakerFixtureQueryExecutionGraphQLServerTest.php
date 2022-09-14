<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class WildcardControlGroupACLWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractACLWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getWildcardResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-wildcard-acl-control-group';
    }

    /**
     * @return class-string<CompilerPassInterface>|null
     */
    protected static function getWildcardCompilerPassClass(): ?string
    {
        return null;
    }
}
