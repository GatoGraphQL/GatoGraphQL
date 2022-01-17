<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\Root\App;
use PoP\API\Response\Schemes as APISchemes;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoPCMSSchema\Pages\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPCMSSchema\Pages\Routing\RequestNature;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    protected function getInitialRESTFields(): string
    {
        return 'id|title|url|content';
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[RequestNature::PAGE][] = [
            'module' => [
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
