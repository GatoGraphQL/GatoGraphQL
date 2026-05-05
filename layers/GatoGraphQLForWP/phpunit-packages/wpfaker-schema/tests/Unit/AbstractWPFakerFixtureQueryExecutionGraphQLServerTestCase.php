<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WPFakerSchema\Unit;

use GraphQLByPoP\GraphQLServer\Unit\AbstractFixtureQueryExecutionGraphQLServerTestCase;

abstract class AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase extends AbstractFixtureQueryExecutionGraphQLServerTestCase
{
    use WPFakerFixtureQueryExecutionGraphQLServerTestTrait;
}
