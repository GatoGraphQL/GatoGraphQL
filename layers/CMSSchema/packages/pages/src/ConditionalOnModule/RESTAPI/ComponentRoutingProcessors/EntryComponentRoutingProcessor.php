<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\Pages\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPCMSSchema\Pages\Routing\RequestNature;

class EntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    protected function getInitialRESTFields(): string
    {
        return 'id|title|url|content';
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[RequestNature::PAGE][] = [
            'component' => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_PAGE,
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
