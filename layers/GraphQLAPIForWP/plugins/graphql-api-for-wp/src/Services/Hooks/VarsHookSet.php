<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\API\Response\Schemes as APISchemes;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;

class VarsHookSet extends AbstractHookSet
{
    protected ModuleRegistryInterface $moduleRegistry;
    protected GraphQLDataStructureFormatter $graphQLDataStructureFormatter;

    #[Required]
    public function autowireVarsHookSet(
        ModuleRegistryInterface $moduleRegistry,
        GraphQLDataStructureFormatter $graphQLDataStructureFormatter,
    ) {
        $this->moduleRegistry = $moduleRegistry;
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }

    protected function init(): void
    {
        // Implement immediately, before VarsHookSet in API adds output=json
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            array($this, 'maybeRemoveVars'),
            0,
            1
        );
    }

    /**
     * If the single endpoint is disabled, or if pointing to a different URL
     * than the single endpoint (eg: /posts/) and the datastructure param
     * is not provided it's not "graphql", then:
     * Do not allow to query the endpoint through URL.
     *
     * Examples of not allowed URLs:
     * - /single-endpoint/?scheme=api&datastructure=graphql <= single endpoint disabled
     * - /posts/?scheme=api
     *
     * @param array<array> $vars_in_array
     */
    public function maybeRemoveVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            // By setting explicit allowed datastructures, we avoid the empty one
            // being processed /?scheme=api <= native API
            // If ever need to support REST or another format, add a hook here
            $allowedDataStructures = [
                $this->graphQLDataStructureFormatter->getName(),
            ];
            if (
                // If single endpoint not enabled
                !$this->moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT)
                // If datastructure is not GraphQL (or another allowed one)
                || !in_array($vars['datastructure'], $allowedDataStructures)
            ) {
                unset($vars['scheme']);
                unset($vars['datastructure']);
            }
        }
    }
}
