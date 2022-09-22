<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ComponentRoutingProcessors;

use GraphQLByPoP\GraphQLServer\ComponentProcessors\RootRelationalFieldDataloadComponentProcessor;
use GraphQLByPoP\GraphQLServer\Constants\OperationTypes;
use PoPAPI\API\ComponentProcessors\SuperRootRelationalFieldDataloadComponentProcessor;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\ComponentModel\Component\Component;
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
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::QUERY_ROOT][] = [
            'component' => new Component(
                SuperRootRelationalFieldDataloadComponentProcessor::class,
                SuperRootRelationalFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_SUPERROOT
            ),
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
            ],
        ];
        // @todo Remove this commented code
        // $ret[RequestNature::QUERY_ROOT][] = [
        //     'component' => new Component(
        //         RootRelationalFieldDataloadComponentProcessor::class,
        //         RootRelationalFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT
        //     ),
        //     'conditions' => [
        //         'scheme' => APISchemes::API,
        //         'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
        //         'graphql-operation-type' => OperationTypes::QUERY,
        //     ],
        // ];
        // $ret[RequestNature::QUERY_ROOT][] = [
        //     'component' => new Component(
        //         RootRelationalFieldDataloadComponentProcessor::class,
        //         RootRelationalFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT
        //     ),
        //     'conditions' => [
        //         'scheme' => APISchemes::API,
        //         'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
        //         'graphql-operation-type' => OperationTypes::MUTATION,
        //     ],
        // ];

        return $ret;
    }
}
