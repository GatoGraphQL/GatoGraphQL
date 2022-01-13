<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use PoP\Root\App;
use PoP\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    protected function getInitialRESTFields(): string
    {
        return 'fullSchema';
    }

    /**
     * @return array<array>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $ret[] = [
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
