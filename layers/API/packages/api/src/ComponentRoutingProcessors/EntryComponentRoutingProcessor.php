<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoPAPI\API\ComponentProcessors\RootRelationalFieldDataloadComponentProcessor;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::QUERY_ROOT][] = [
            'component' => new Component(
                RootRelationalFieldDataloadComponentProcessor::class,
                RootRelationalFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT
            ),
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
