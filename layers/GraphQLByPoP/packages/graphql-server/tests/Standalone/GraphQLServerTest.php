<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

class GraphQLServerTest extends AbstractGraphQLServerTestCase
{
    public function testHasDependedComponentClasses(): void
    {
        $query = '{ id }';
        $response = [
            'data' => [
                'id' => 'root',
            ]
        ];
        $this->expectOutputString(json_encode($response));
        self::$graphQLServer->execute($query);
    }
}
