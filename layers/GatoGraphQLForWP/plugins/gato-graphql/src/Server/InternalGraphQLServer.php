<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Server;

use GatoGraphQL\GatoGraphQL\PluginAppGraphQLServerNames;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GraphQLByPoP\GraphQLServer\Server\AbstractAttachedGraphQLServer;
use PoP\ComponentModel\App;
use PoP\Root\AppThreadInterface;

use function do_action;

class InternalGraphQLServer extends AbstractAttachedGraphQLServer
{
    protected function initializeApp(): AppThreadInterface
    {
        do_action(
            PluginAppHooks::INITIALIZE_APP,
            PluginAppGraphQLServerNames::INTERNAL,
        );
        return App::getAppThread();
    }

    /**
     * When doing wp-cron, the InternalGraphQLServer will use
     * the Store created for the (never-to-be-executed) endpoint
     */
    protected function areFeedbackAndTracingStoresAlreadyCreated(): bool
    {
        $doingCron = defined('DOING_CRON') && DOING_CRON;
        if ($doingCron) {
            return true;
        }
        return parent::areFeedbackAndTracingStoresAlreadyCreated();
    }
}
