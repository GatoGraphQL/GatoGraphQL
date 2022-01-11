<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Endpoint;

use GraphQLAPI\GraphQLAPI\Registries\EndpointExecuterRegistryInterface;
use PoP\BasicService\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

class EndpointExecuterService extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    private ?EndpointExecuterRegistryInterface $customEndpointExecuterRegistry = null;

    final public function setEndpointExecuterRegistry(EndpointExecuterRegistryInterface $customEndpointExecuterRegistry): void
    {
        $this->customEndpointExecuterRegistry = $customEndpointExecuterRegistry;
    }
    final protected function getEndpointExecuterRegistry(): EndpointExecuterRegistryInterface
    {
        return $this->customEndpointExecuterRegistry ??= $this->instanceManager->getInstance(EndpointExecuterRegistryInterface::class);
    }

    public function initialize(): void
    {
        /**
         * Call it on "boot" after the WP_Query is parsed, so the single CPT
         * is loaded, and asking for `is_singular(CPT)` works.
         */
        \add_action(
            'popcms:boot',
            function (): void {
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
                foreach ($this->getEndpointExecuterRegistry()->getEnabledEndpointExecuters() as $endpointExecuter) {
                    $endpointExecuter->executeEndpoint();
                }
            }
        );
    }
}
