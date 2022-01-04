<?php

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\PluginUtils\Services\Helpers\StringConversion;
use PoP\Engine\AbstractTestCase;

class StringConversionTest extends AbstractTestCase
{
    public function testDashesToCamelCase(): void
    {
        /**
         * Currently can't use container services in tests
         * @todo Load container services in bootstrap-phpunit.php, then restore
         */
        return;

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
