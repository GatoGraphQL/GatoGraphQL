<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

class StandaloneGraphQLAppTestCase extends AbstractStandaloneGraphQLAppTestCase
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
        self::$standaloneGraphQLApp->execute($query);
    }
}
