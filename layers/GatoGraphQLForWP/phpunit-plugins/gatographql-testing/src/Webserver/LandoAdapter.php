<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Webserver;

use GatoGraphQL\GatoGraphQL\Facades\Request\PrematureRequestServiceFacade;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineHookNames;

use function add_filter;
use function remove_filter;

/**
 * When not using Lando with a proxy, the assigned URL
 * will be something like "localhost:54023", however,
 * this URL is not accessible from within the container,
 * as the port is not mapped.
 *
 * Hence, convert this into "localhost".
 */
class LandoAdapter
{
    public function __construct()
    {
        \add_action(
            'init',
            function(): void {
                $applicationStateHelperService = PrematureRequestServiceFacade::getInstance();
                if (!$applicationStateHelperService->isPubliclyExposedGraphQLAPIRequest()) {
                    return;
                }

                App::addAction(
                    EngineHookNames::GENERATE_DATA_BEGINNING,
                    $this->addHooks(...),
                );
                App::addAction(
                    EngineHookNames::GENERATE_DATA_END,
                    $this->removeHooks(...),
                );
            }
        );
    }

    public function addHooks(): void
    {
        add_filter('option_siteurl', $this->maybeRemovePortFromLocalhostURL(...));
        add_filter('option_home', $this->maybeRemovePortFromLocalhostURL(...));
    }

    public function removeHooks(): void
    {
        remove_filter('option_siteurl', $this->maybeRemovePortFromLocalhostURL(...));
        remove_filter('option_home', $this->maybeRemovePortFromLocalhostURL(...));
    }

    /**
     * Do it only when executing a GraphQL query, or otherwise
     * Guzzle can't invoke the PHPUnit tests
     */
    public function maybeRemovePortFromLocalhostURL(string $url): string
    {
        if (str_starts_with($url, 'https://localhost:')) {
            return 'https://localhost';
        }
        if (str_starts_with($url, 'http://localhost:')) {
            return 'http://localhost';
        }
        return $url;
    }
}
