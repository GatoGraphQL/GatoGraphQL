<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Standalone\Upstream\ComponentModel;

use GraphQLAPI\GraphQLAPI\Standalone\AbstractFakerFixtureQueryExecutionGraphQLServerTestCase;

class FakerFixtureQueryExecutionGraphQLServerTest extends AbstractFakerFixtureQueryExecutionGraphQLServerTestCase
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/Fixture';
    }

    /**
     * @return string[]
     */
    protected static function getGraphQLServerComponentClasses(): array
    {
        return [
            ...parent::getGraphQLServerComponentClasses(),
            ...[
                \PoPWPSchema\Users\Component::class,
            ]
        ];
    }

    // /**
    //  * @return array<string,mixed>
    //  */
    // protected static function getGraphQLServerComponentClassConfiguration(): array
    // {
    //     return [
    //         ...parent::getGraphQLServerComponentClassConfiguration(),
    //         ...[
    //             \PoP\GraphQLParser\Component::class => [
    //                 \PoP\GraphQLParser\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION => true,
    //                 \PoP\GraphQLParser\Environment::ENABLE_DYNAMIC_VARIABLES => true,
    //                 \PoP\GraphQLParser\Environment::ENABLE_COMPOSABLE_DIRECTIVES => true,
    //                 \PoP\GraphQLParser\Environment::ENABLE_RESOLVED_FIELD_VARIABLE_REFERENCES => true,
    //             ],
    //             \PoPAPI\API\Component::class => [
    //                 \PoPAPI\API\Environment::ENABLE_EMBEDDABLE_FIELDS => true,
    //             ],
    //             \PoP\ComponentModel\Component::class => [
    //                 \PoP\ComponentModel\Environment::CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST => true,
    //             ],
    //         ]
    //     ];
    // }
}
