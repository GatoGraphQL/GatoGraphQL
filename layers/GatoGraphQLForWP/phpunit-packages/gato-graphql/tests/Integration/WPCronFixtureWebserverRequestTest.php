<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Placeholders;

/**
 * Test WP-Cron. It works like this:
 *
 * - The first execution (wp-cron:0.json) schedules the wp-cron event.
 * - The second execution (wp-cron.json) triggers the wp-cron.
 * - The third execution (wp-cron:1.json) unschedules the wp-cron event.
 *
 * By the time of the third execution, a new trashed post should've
 * been created (by WPCronTestExecuter), and we query it.
 *
 * @see layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing/src/Executers/WPCronTestExecuter.php
 */
class WPCronFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WPCronWebserverRequestTestTrait;
    use ModifyValueFixtureEndpointWebserverRequestTestCaseTrait;

    private ?int $timestamp = null;

    public function maybeInitTimestamp(): void
    {
        $this->timestamp ??= time();
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-wp-cron';
    }

    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    /**
     * Enable the wp-cron testing via the endpoint
     */
    protected function getFixtureCustomEndpoint(string $dataName): ?string
    {
        // Trigger the execution of wp-cron!
        if ($dataName === 'wp-cron') {
            return 'wp-cron.php?doing_wp_cron';
        }

        // For the other tests, schedule/unschedule the wp-cron event
        $this->maybeInitTimestamp();
        return $this->getWPCronEndpoint(
            $this->getEndpoint(),
            [
                'timestamp' => $this->timestamp,
            ]
        );
    }

    /**
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        /**
         * Do not execute the test against "wp-cron.json",
         * as it merely triggers the execution of wp-cron
         */
        // expectedContentType
        $providerItems['wp-cron'][0] = 'text/html';
        // expectedResponseBody. null => no exection of test
        $providerItems['wp-cron'][1] = null;

        // Make sure "wp-cron:0" executes first
        return $this->reorderProviderEndpointEntriesToExecuteOriginalTestFirst($providerItems);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getGraphQLVariables(string $graphQLVariablesFile): array
    {
        $variables = parent::getGraphQLVariables($graphQLVariablesFile);

        $this->maybeInitTimestamp();
        $postTitle = sprintf(
            Placeholders::WP_CRON_UNIQUE_POST_TITLE,
            $this->timestamp
        );
        $variables['postSlug'] = $this->getSlugFromPostTitle($postTitle);

        return $variables;
    }

    protected function getSlugFromPostTitle(string $postTitle): string
    {
        return \str_replace(
            [' ', ':'],
            ['-', ''],
            $postTitle,
        );
    }
}
