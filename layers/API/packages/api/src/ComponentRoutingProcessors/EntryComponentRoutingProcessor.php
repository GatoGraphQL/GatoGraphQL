<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentRoutingProcessors;

use PoPAPI\API\ComponentProcessors\RootRelationalFieldDataloadComponentProcessor;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::QUERY_ROOT][] = [
            'component' => [
                RootRelationalFieldDataloadComponentProcessor::class,
                RootRelationalFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
