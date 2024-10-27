<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Endpoint;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Registries\EndpointExecuterRegistryInterface;
use PoP\Root\Constants\HookNames;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

class EndpointExecuterService extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    private ?EndpointExecuterRegistryInterface $customEndpointExecuterRegistry = null;

    final protected function getEndpointExecuterRegistry(): EndpointExecuterRegistryInterface
    {
        if ($this->customEndpointExecuterRegistry === null) {
            /** @var EndpointExecuterRegistryInterface */
            $customEndpointExecuterRegistry = $this->instanceManager->getInstance(EndpointExecuterRegistryInterface::class);
            $this->customEndpointExecuterRegistry = $customEndpointExecuterRegistry;
        }
        return $this->customEndpointExecuterRegistry;
    }

    public function initialize(): void
    {
        /**
         * Call it on "boot" after the WP_Query is parsed, so the single CPT
         * is loaded, and asking for `is_singular(CPT)` works.
         */
        App::addAction(
            HookNames::AFTER_BOOT_APPLICATION,
            $this->executeRequestedEndpoint(...)
        );
    }

    /**
     * Execute the EndpointExecuters from the Registry:
     *
     * Only 1 executer should be executed, from among (or other injected ones):
     *
     * - Query resolution
     * - GraphiQL client
     * - Voyager client
     * - View query source
     * - Admin client
     * - Persisted Query
     *
     * All others will have `isServiceEnabled` => false, by checking
     * their expected value of ?view=... or if some attached service
     * is enabled or not
     */
    public function executeRequestedEndpoint(): void
    {
        foreach ($this->getEndpointExecuterRegistry()->getEnabledEndpointExecuters() as $endpointExecuter) {
            $endpointExecuter->executeEndpoint();
        }
    }
}
