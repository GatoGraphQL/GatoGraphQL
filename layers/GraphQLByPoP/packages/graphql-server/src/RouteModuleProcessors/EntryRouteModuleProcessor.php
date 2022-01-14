<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RouteModuleProcessors;

use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;
use PoP\API\Routing\RouteNatures;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[RouteNatures::QUERY_ROOT][] = [
            'module' => [
                RootRelationalFieldDataloadModuleProcessor::class,
                RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
                'graphql-operation-type' => OperationTypes::QUERY,
            ],
        ];
        $ret[RouteNatures::QUERY_ROOT][] = [
            'module' => [
                RootRelationalFieldDataloadModuleProcessor::class,
                RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
                'graphql-operation-type' => OperationTypes::MUTATION,
            ],
        ];

        return $ret;
    }
}
