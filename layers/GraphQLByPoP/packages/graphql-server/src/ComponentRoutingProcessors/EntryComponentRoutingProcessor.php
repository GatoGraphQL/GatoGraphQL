<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ComponentRoutingProcessors;

use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
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
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::QUERY_ROOT][] = [
            'component' => [
                RootRelationalFieldDataloadModuleProcessor::class,
                RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
                'graphql-operation-type' => OperationTypes::QUERY,
            ],
        ];
        $ret[RequestNature::QUERY_ROOT][] = [
            'component' => [
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
