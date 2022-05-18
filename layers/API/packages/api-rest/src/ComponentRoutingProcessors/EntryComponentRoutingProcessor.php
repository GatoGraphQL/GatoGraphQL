<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\ComponentRoutingProcessors;

use PoPAPI\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoP\Root\App;

class EntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    protected function getInitialRESTFields(): string
    {
        return 'fullSchema';
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::QUERY_ROOT][] = [
            'module' => [
                RootRelationalFieldDataloadModuleProcessor::class,
                RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ROOT,
                [
                    'fields' => !empty(App::getState('query')) ?
                        App::getState('query')
                        : $this->getRESTFields()
                    ]
                ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getRestDataStructureFormatter()->getName(),
            ],
        ];

        return $ret;
    }
}
