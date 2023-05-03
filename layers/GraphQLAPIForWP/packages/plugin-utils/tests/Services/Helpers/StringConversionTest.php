<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginUtils\Services\Helpers;

use PoP\ComponentModel\AbstractTestCaseCase;

class StringConversionTest extends AbstractTestCaseCase
{
    public function testDashesToCamelCase(): void
    {
        /** @var StringConversion */
        $stringConversion = $this->getService(StringConversion::class);

        $this->assertSame(
            'graphqlApiSchemaConfigOptions',
            $stringConversion->dashesToCamelCase(
                'graphql-api-schema-config-options'
            )
        );

        $this->assertSame(
            'GraphqlApiSchemaConfigOptions',
            $stringConversion->dashesToCamelCase(
                'graphql-api-schema-config-options',
                true
            )
        );
    }
}
