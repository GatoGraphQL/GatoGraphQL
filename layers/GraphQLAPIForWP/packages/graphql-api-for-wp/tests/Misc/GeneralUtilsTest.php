<?php

namespace GraphQLAPI\GraphQLAPI\Misc;

use GraphQLAPI\GraphQLAPI\General\GeneralUtils;
use PHPUnit\Framework\TestCase;

class GeneralUtilsTest extends TestCase
{
    public function testDashesToCamelCase(): void
    {
        $this->assertSame(
            'graphqlApiSchemaConfigOptions',
            GeneralUtils::dashesToCamelCase(
                'graphql-api-schema-config-options'
            )
        );

        $this->assertSame(
            'GraphqlApiSchemaConfigOptions',
            GeneralUtils::dashesToCamelCase(
                'graphql-api-schema-config-options',
                true
            )
        );
    }
}
