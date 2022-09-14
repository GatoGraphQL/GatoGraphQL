<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ControlGroupACLWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractACLWPFakerFixtureQueryExecutionGraphQLServerTest
{
    protected function getWildcardResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-acl-control-group';
    }

    /**
     * @return class-string<CompilerPassInterface>|null
     */
    protected static function getWildcardCompilerPassClass(): ?string
    {
        return null;
    }
}
