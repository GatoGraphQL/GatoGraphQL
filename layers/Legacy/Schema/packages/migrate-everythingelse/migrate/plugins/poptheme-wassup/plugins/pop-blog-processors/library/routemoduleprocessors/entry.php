<?php

use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentRouting\AbstractEntryRouteModuleProcessor;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_Blog_Module_EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    // /**
    //  * @return array<string, array<array>>
    //  */
    // public function getModulesVarsPropertiesByNature(): array
    // {
    //     $ret = array();

    //     // API
    //     if (!\PoPAPI\API\Environment::disableAPI()) {
    //         // Home
    //         $ret[RequestNature::HOME][] = [
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
        if (!\PoPAPI\API\Environment::disableAPI()) {

            // Page
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            );
            foreach ($routemodules as $route => $module) {
                $ret[RequestNature::GENERIC][$route][] = [
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
                $ret[RequestNature::GENERIC][$route][] = [
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
                $ret[UserRequestNature::USER][$route][] = [
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
                $ret[TagRequestNature::TAG][$route][] = [
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
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
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
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
    new PoPTheme_Wassup_Blog_Module_EntryRouteModuleProcessor(new \PoPAPI\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter())
	);
}, 888200);
