<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoPSchema\Pages\Routing\RouteNatures;
use PoPSchema\Pages\ModuleProcessors\FieldDataloadModuleProcessor;

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

        $vars = ApplicationState::getVars();
        $ret[RouteNatures::PAGE][] = [
            'module' => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_PAGE,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query']
                        : $this->getRESTFields()
                    ]
                ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->restDataStructureFormatter->getName(),
            ],
        ];

        return $ret;
    }
}
