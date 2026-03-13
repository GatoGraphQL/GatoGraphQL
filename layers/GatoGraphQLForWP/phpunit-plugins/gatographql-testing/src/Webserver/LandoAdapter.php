<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Webserver;

use GatoGraphQL\GatoGraphQL\Facades\Request\PrematureRequestServiceFacade;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineHookNames;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;

use function add_filter;
use function remove_filter;

/**
 * When not using Lando with a proxy, the assigned URL
 * will be something like "localhost:54023", however,
 * this URL is not accessible from within the container.
 *
 * Convert it into "host.docker.internal:54023", then it works.
 */
class LandoAdapter
{
    public function __construct()
    {
        \add_action(
            'init',
            function (): void {
                $applicationStateHelperService = PrematureRequestServiceFacade::getInstance();
                /** @var EndpointHelpers */
                $endpointHelpers = SystemInstanceManagerFacade::getInstance()->getInstance(EndpointHelpers::class);
                if (!$applicationStateHelperService->isPubliclyExposedGraphQLAPIRequest()
                    && !$endpointHelpers->isRequestingAdminGraphQLEndpoint()
                ) {
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
        if (\preg_match('#^(https?)://localhost:(\d+)(/.*)?$#', $url, $matches) === 1) {
            $scheme = $matches[1];
            $port = $matches[2];
            $path = $matches[3] ?? '';
            return \sprintf('%s://host.docker.internal:%s%s', $scheme, $port, $path);
        }
        return $url;
    }
}
