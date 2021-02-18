<?php

use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoPTheme_Wassup_Blog_Module_EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    // /**
    //  * @return array<string, array<array>>
    //  */
    // public function getModulesVarsPropertiesByNature(): array
    // {
    //     $ret = array();

    //     // API
    //     if (!\PoP\API\Environment::disableAPI()) {
    //         // Home
    //         $ret[RouteNatures::HOME][] = [
    //             'module' => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
    //             'conditions' => [
    //                 'scheme' => APISchemes::API,
    //             ],
    //         ];
    //     }

    //     return $ret;
    // }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // API
        if (!\PoP\API\Environment::disableAPI()) {

            $vars = ApplicationState::getVars();

            // Page
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            );
            foreach ($routemodules as $route => $module) {
                $ret[RouteNatures::STANDARD][$route][] = [
                    'module' => $module,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                    ],
                ];
            }

            // REST API Page
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            );
            foreach ($routemodules as $route => $module) {
                $ret[RouteNatures::STANDARD][$route][] = [
                    'module' => $module,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                        'datastructure' => $this->restDataStructureFormatter->getName(),
                    ],
                ];
            }

            // Author
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST],
            );
            foreach ($routemodules as $route => $module) {
                $ret[UserRouteNatures::USER][$route][] = [
                    'module' => $module,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                    ],
                ];
            }

            // Tag
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST],
            );
            foreach ($routemodules as $route => $module) {
                $ret[TagRouteNatures::TAG][$route][] = [
                    'module' => $module,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                    ],
                ];
            }

            // Single
            $routemodules = array(
                POP_ROUTE_AUTHORS => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST],
            );
            foreach ($routemodules as $route => $module) {
                $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                    'module' => $module,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                    ],
                ];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
    new PoPTheme_Wassup_Blog_Module_EntryRouteModuleProcessor()
	);
}, 200);
