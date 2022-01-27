<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\App;
use PoP\Root\Routing\RequestNature;
use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

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
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    /**
     * If modifying engine behavior is disabled, this service is not needed
     */
    public function isServiceEnabled(): bool
    {
        /** @var ComponentModelComponentConfiguration */
        $componentModelComponentConfiguration = App::getComponent(ComponentModelComponent::class)->getConfiguration();
        return $componentModelComponentConfiguration->enableModifyingEngineBehaviorViaRequest();
    }

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
