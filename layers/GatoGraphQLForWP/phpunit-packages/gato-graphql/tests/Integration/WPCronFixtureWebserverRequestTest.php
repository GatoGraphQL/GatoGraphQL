<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Placeholders;

/**
 * Test WP-Cron. It works like this:
 *
 * The first execution (wp-cron.json) sets-up the wp-cron execution.
 * By the time of the second execution (wp-cron:1.json) a new trashed
 * post should've been created (by WPCronTestExecuter), and we query it.
 *
 * @see layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing/src/Executers/WPCronTestExecuter.php
 */
class WPCronFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WPCronWebserverRequestTestTrait;

    private ?int $timestamp = null;
    
    public function maybeInitTimestamp(): void
    {
        $this->timestamp ??= time();
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-wp-cron';
    }

    /**
     * Enable the wp-cront testing via the endpoint
     */
    protected function getEndpoint(): string
    {
        $this->maybeInitTimestamp();
        return $this->getWPCronEndpoint(
            'graphql/',
            [
                'timestamp' => $this->timestamp,
            ]
        );
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
