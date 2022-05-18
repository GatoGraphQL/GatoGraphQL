<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\API\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPCMSSchema\Users\ConditionalOnModule\API\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();
        $ret[UserRequestNature::USER][] = [
            'component' => [FieldDataloadComponentProcessor::class, FieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];
        return $ret;
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getUsersRoute() => [FieldDataloadComponentProcessor::class, FieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        return $ret;
    }
}
