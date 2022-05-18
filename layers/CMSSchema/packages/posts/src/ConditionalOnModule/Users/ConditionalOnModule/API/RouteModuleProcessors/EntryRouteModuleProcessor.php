<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\API\RouteModuleProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentRouting\AbstractEntryRouteModuleProcessor;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;
use PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\API\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPCMSSchema\Users\Routing\RequestNature;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Author's posts
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routemodules = array(
            $moduleConfiguration->getPostsRoute() => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST
            ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        return $ret;
    }
}
