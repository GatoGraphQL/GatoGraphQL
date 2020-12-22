<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RouteModuleProcessors;

use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use PoP\Routing\RouteNatures;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use GraphQLByPoP\GraphQLServer\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[RouteNatures::HOME][] = [
            'module' => [RootRelationalFieldDataloadModuleProcessor::class, RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT],
            'conditions' => [
                'scheme' => APISchemes::API,
                'graphql-operation-type' => OperationTypes::QUERY,
            ],
        ];
        $ret[RouteNatures::HOME][] = [
            'module' => [RootRelationalFieldDataloadModuleProcessor::class, RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT],
            'conditions' => [
                'scheme' => APISchemes::API,
                'graphql-operation-type' => OperationTypes::MUTATION,
            ],
        ];

        return $ret;
    }
}
