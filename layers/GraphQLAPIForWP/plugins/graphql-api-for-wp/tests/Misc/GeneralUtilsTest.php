<?php

namespace GraphQLAPI\GraphQLAPI\Misc;

use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use PHPUnit\Framework\TestCase;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class GeneralUtilsTest extends TestCase
{
    public function testDashesToCamelCase(): void
    {
        /**
         * Currently can't use container services in tests
         * @todo Load container services in bootstrap-phpunit.php, then restore
         */
        return;

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GeneralUtils */
        $generalUtils = $instanceManager->getInstance(GeneralUtils::class);

        $this->assertSame(
            'graphqlApiSchemaConfigOptions',
            $generalUtils->dashesToCamelCase(
                'graphql-api-schema-config-options'
            )
        );

        $this->assertSame(
            'GraphqlApiSchemaConfigOptions',
            $generalUtils->dashesToCamelCase(
                'graphql-api-schema-config-options',
                true
            )
        );
    }
}
