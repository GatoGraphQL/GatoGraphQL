<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\Pages\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Pages\Routing\RequestNature;

class EntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    protected function doGetGraphQLQueryToResolveRESTEndpoint(): string
    {
        return 'id|title|url|content';
    }

    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::PAGE][] = [
            'component' => new Component(
                FieldDataloadComponentProcessor::class,
                FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
                ]
            ),
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getRestDataStructureFormatter()->getName(),
            ],
        ];

        return $ret;
    }
}
