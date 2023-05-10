<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginLifecyclePriorities;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Placeholders;
use PoP\Root\Constants\HookNames;
use RuntimeException;

class WPCronTestExecuter
{
    /**
     * Inject the "internal-graphql-server-response" state
     * containing the response of the same requested query
     * via the endpoint, but executed against `GraphQLServer`.
     */
    public function __construct()
    {
        \add_action(
            PluginAppHooks::INITIALIZE_APP,
            $this->addHooks(...),
            PluginLifecyclePriorities::INITIALIZE_APP
        );
    }

    public function addHooks(string $pluginAppGraphQLServerName): void
    {
        App::addAction(
            HookNames::APPLICATION_READY,
            $this->maybeExecuteWPCron(...)
        );
    }

    public function maybeExecuteWPCron(): void
    {
        if (!AppHelpers::isMainAppThread()) {
            return;
        }

        $actions = App::getState('actions');
        if (!is_array($actions)) {
            return;
        }

        /** @var string[] $actions */
        if (!in_array(Actions::TEST_WP_CRON, $actions)) {
            return;
        }

        $timestamp = App::getRequest()->get(Params::TIMESTAMP);
        if ($timestamp === null) {
            throw new RuntimeException(
                sprintf(
                    \__('When testing wp-cron, must provide param "%s"'),
                    Params::TIMESTAMP
                )
            );
        }

        $this->executeWPCron((int)$timestamp);
    }

    /**
     * The first execution (wp-cron.json) sets-up the wp-cron execution.
     * The second execution (wp-cron:1.json) removes it.
     */
    protected function executeWPCron(int $uniquePostSlugID): void
    {
        $args = $this->getWPCronArgs($uniquePostSlugID);
        $timestamp = \wp_next_scheduled('gato_graphql__execute_query', $args);
        if ($timestamp !== false) {
            \wp_unschedule_event($timestamp, 'gato_graphql__execute_query', $args);
            return;
        }

        \wp_schedule_event(
            time(),
            'weekly',
            'gato_graphql__execute_query',
            $args
        );
    }

    /**
     * @return array<int,mixed>
     */
    protected function getWPCronArgs(int $uniquePostSlugID): array
    {
        $postTitle = sprintf(
            Placeholders::WP_CRON_UNIQUE_POST_TITLE,
            $uniquePostSlugID
        );
        return [
            <<<GRAPHQL
            mutation CreateTrashedPostWithUniqueSlug(
                \$postTitle: String!
            ) {
                createPost(input:{
                    title: \$postTitle
                    status: trash
                }) {
                    status
                    errors {
                        __typename
                        ...on ErrorPayload {
                            message
                        }
                    }
                }
            }
            GRAPHQL,
            [
                'postTitle' => $postTitle
            ],
            'CreateTrashedPostWithUniqueSlug',
            1 // This is the admin user's ID
        ];
    }
}
