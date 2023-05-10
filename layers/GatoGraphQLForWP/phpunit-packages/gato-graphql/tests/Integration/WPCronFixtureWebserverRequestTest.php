<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Placeholders;

/**
 * Test WP-Cron. It works like this:
 *
 * The first execution (wp-cron:0.json) sets-up the wp-cron execution.
 * The second execution (wp-cron.json) triggers the wp-cron.
 * By the time of the third execution (wp-cron:1.json) a new trashed
 * post should've been created (by WPCronTestExecuter), and we query it.
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
     * Enable the wp-cront testing via the endpoint
     */
    protected function getFixtureCustomEndpoint(string $dataName): ?string
    {
        // Trigger the execution of wp-cron!
        if ($dataName === 'wp-cron') {
            return 'wp-cron.php?doing_wp_cron';
        }

        if (str_ends_with($dataName, ':0')
            || str_ends_with($dataName, ':1')
        ) {
            $this->maybeInitTimestamp();
            return $this->getWPCronEndpoint(
                $this->getEndpoint(),
                [
                    'timestamp' => $this->timestamp,
                ]
            );
        }

        return parent::getFixtureCustomEndpoint($dataName);
    }

    /**
     * Make sure "wp-cron:0" executes first
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        return $this->reorderProviderEndpointEntriesToExecuteOriginalTestFirst($providerItems);
    }

    // /**
    //  * Wait a few seconds to make sure wp-cron has been executed.
    //  * 
    //  * @param array<string,mixed> $options
    //  * @return array<string,mixed>
    //  */
    // protected function customizeRequestOptions(array $options): array
    // {
    //     $options = parent::customizeRequestOptions($options);

    //     if ($this->getDataName() === 'wp-cron') {
    //         $options[RequestOptions::DELAY] = 2000;
    //     } elseif ($this->getDataName() === 'wp-cron:1') {
    //         $options[RequestOptions::DELAY] = 2000;
    //     }

    //     return $options;
    // }

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
