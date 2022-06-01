<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ConditionalOnModule\API\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoPCMSSchema\Pages\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Pages\Routing\RequestNature;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string,array<mixed[]>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::PAGE][] = [
            'component' => new Component(FieldDataloadComponentProcessor::class, FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE),
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
