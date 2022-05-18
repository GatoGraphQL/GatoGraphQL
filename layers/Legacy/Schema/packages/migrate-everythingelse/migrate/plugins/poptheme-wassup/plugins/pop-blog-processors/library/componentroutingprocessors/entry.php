<?php

use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_Blog_Module_EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    // /**
    //  * @return array<string, array<array>>
    //  */
    // public function getStatePropertiesToSelectComponentByNature(): array
    // {
    //     $ret = array();

    //     // API
    //     if (!\PoPAPI\API\Environment::disableAPI()) {
    //         // Home
    //         $ret[RequestNature::HOME][] = [
    //             'component' => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
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
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // API
        if (!\PoPAPI\API\Environment::disableAPI()) {

            // Page
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            );
            foreach ($routemodules as $route => $component) {
                $ret[RequestNature::GENERIC][$route][] = [
                    'component' => $component,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                    ],
                ];
            }

            // REST API Page
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            );
            foreach ($routemodules as $route => $component) {
                $ret[RequestNature::GENERIC][$route][] = [
                    'component' => $component,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                        'datastructure' => $this->restDataStructureFormatter->getName(),
                    ],
                ];
            }

            // Author
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORCONTENTLIST],
            );
            foreach ($routemodules as $route => $component) {
                $ret[UserRequestNature::USER][$route][] = [
                    'component' => $component,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                    ],
                ];
            }

            // Tag
            $routemodules = array(
                POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGCONTENTLIST],
            );
            foreach ($routemodules as $route => $component) {
                $ret[TagRequestNature::TAG][$route][] = [
                    'component' => $component,
                    'conditions' => [
                        'scheme' => APISchemes::API,
                    ],
                ];
            }

            // Single
            $routemodules = array(
                POP_ROUTE_AUTHORS => [PoP_Blog_Module_Processor_FieldDataloads::class, PoP_Blog_Module_Processor_FieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEAUTHORLIST],
            );
            foreach ($routemodules as $route => $component) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                    'component' => $component,
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
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
    new PoPTheme_Wassup_Blog_Module_EntryComponentRoutingProcessor(new \PoPAPI\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter())
	);
}, 888200);
