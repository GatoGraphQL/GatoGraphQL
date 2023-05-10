<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test WP-Cron.
 *
 * @see layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing/src/Executers/WPCronTestExecuter.php
 */
class WPCronFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WPCronWebserverRequestTestTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-wp-cron';
    }

    /**
     * Enable the wp-cront testing via the endpoint
     */
    protected function getEndpoint(): string
    {
        return $this->getWPCronEndpoint(
            'graphql/'
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getGraphQLVariables(string $graphQLVariablesFile): array
    {
        $variables = parent::getGraphQLVariables($graphQLVariablesFile);

        $uniquePostSlugID = 888888888;
        $variables['postSlug'] = str_replace(
            [' ', ':'],
            '-',
            sprintf(
                'Testing wp-cron: %s',
                $uniquePostSlugID
            ),
        );

        return $variables;
    }
}
