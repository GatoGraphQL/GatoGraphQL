<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\State;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\App;
use PoP\Root\Routing\RequestNature;
use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;

/**
 * If the single endpoint is disabled, or if pointing to a different URL
 * than the single endpoint (eg: /posts/) and the datastructure param
 * is not provided or is not "graphql", then:
 *
 *   Do not allow to query the endpoint through URL.
 *
 * Examples of not allowed URLs:
 *
 *   - /single-endpoint/?scheme=api&datastructure=graphql <= single endpoint disabled
 *   - /posts/?scheme=api
 */
class AppStateProvider extends AbstractAppStateProvider
{
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        if ($this->graphQLDataStructureFormatter === null) {
            /** @var GraphQLDataStructureFormatter */
            $graphQLDataStructureFormatter = $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
            $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
        }
        return $this->graphQLDataStructureFormatter;
    }

    /**
     * If modifying engine behavior is disabled, this service is not needed
     */
    public function isServiceEnabled(): bool
    {
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        return $componentModelModuleConfiguration->enableModifyingEngineBehaviorViaRequest();
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }

        /**
         * By setting explicit allowed datastructures, we avoid the empty one
         * being processed /?scheme=api <= native API.
         * If ever need to support REST or another format, add a hook here
         */
        $allowedDataStructures = [
            $this->getGraphQLDataStructureFormatter()->getName(),
        ];
        if (
            // If single endpoint not enabled
            !$this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT)
            // If datastructure is not GraphQL (or another allowed one)
            || !in_array($state['datastructure'], $allowedDataStructures)
        ) {
            $state['scheme'] = null;
            $state['datastructure'] = null;
            $state['nature'] = RequestNature::GENERIC;
        }
    }
}
