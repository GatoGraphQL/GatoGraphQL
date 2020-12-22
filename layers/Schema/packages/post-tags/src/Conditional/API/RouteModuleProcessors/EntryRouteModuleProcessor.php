<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Conditional\API\RouteModuleProcessors;

use PoP\Routing\RouteNatures;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $ret[TagRouteNatures::TAG][] = [
            'module' => [\PoP_PostTags_Module_Processor_FieldDataloads::class, \PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAG],
            'conditions' => [
                'scheme' => APISchemes::API,
                'routing-state' => [
                    'taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName(),
                ],
            ],
        ];
        return $ret;
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $routemodules = array(
            POP_POSTTAGS_ROUTE_POSTTAGS => [\PoP_PostTags_Module_Processor_FieldDataloads::class, \PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [\PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'routing-state' => [
                        'taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
