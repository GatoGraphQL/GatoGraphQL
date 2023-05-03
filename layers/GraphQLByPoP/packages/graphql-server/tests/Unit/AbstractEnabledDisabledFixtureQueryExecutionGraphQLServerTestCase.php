<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

abstract class AbstractEnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseCase extends AbstractFixtureQueryExecutionGraphQLServerTestCaseCase
{
    use EnabledDisabledFixtureQueryExecutionGraphQLServerTestCaseTrait;
}
