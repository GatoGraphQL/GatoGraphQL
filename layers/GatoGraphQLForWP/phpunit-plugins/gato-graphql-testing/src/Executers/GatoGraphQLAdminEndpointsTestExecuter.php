<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use GatoGraphQL\GatoGraphQL\GatoGraphQL;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use RuntimeException;

class GatoGraphQLAdminEndpointsTestExecuter
{
    use OutputOriginalExceptionMessageTestExecuterTrait;

    public function __construct()
    {
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $actions = $_GET['actions'] ?? null;
        if ($actions === null || !is_array($actions)) {
            return;
        }
        /** @var string[] $actions */
        if (!in_array(Actions::TEST_GATO_GRAPHQL_ADMIN_ENDPOINTS, $actions)) {
            return;
        }

        $this->setupToOutputOriginalExceptionMessage();

        \add_action(
            'init',
            $this->testGatoGraphQLAdminEndpoints(...)
        );
    }

    /**
     * Test that the admin endpoint methods in
     * `GatoGraphQL\GatoGraphQL\GatoGraphQL` return
     * the expected values.
     *
     * If they do not, simply throw an exception
     * that will make the test fail.
     */
    public function testGatoGraphQLAdminEndpoints(): void
    {
        $methodToExpectedEndpoints = [
            GatoGraphQL::getAdminEndpoint() => '/wp-admin/edit.php?page=gato_graphql&action=execute_query',
            GatoGraphQL::getAdminBlockEditorEndpoint() => '/wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=blockEditor',
            GatoGraphQL::getAdminCustomEndpoint('myCustomEndpointGroup') => '/wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=myCustomEndpointGroup',
        ];

        foreach ($methodToExpectedEndpoints as $methodEndpoint => $expectedEndpoint) {
            $expectedEndpoint = \home_url($expectedEndpoint);
            if ($methodEndpoint === $expectedEndpoint) {
                continue;
            }
            throw new RuntimeException(
                sprintf(
                    \__('Admin endpoint "%s" was expected, but "%s" is the actual value'),
                    $expectedEndpoint,
                    $methodEndpoint
                )
            );
        }
    }
}
