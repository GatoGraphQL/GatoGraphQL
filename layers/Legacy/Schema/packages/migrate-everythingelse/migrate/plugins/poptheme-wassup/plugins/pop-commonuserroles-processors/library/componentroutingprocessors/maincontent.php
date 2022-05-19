<?php

use PoP\Root\Routing\RequestNature;

class PoP_CommonUserRolesProcessors_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION => [GD_URE_Module_Processor_CreateProfileBlocks::class, GD_URE_Module_Processor_CreateProfileBlocks::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE],
            POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL => [GD_URE_Module_Processor_CreateProfileBlocks::class, GD_URE_Module_Processor_CreateProfileBlocks::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE],
            POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION => [GD_URE_Module_Processor_UpdateProfileBlocks::class, GD_URE_Module_Processor_UpdateProfileBlocks::COMPONENT_BLOCK_PROFILEORGANIZATION_UPDATE],
            POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL => [GD_URE_Module_Processor_UpdateProfileBlocks::class, GD_URE_Module_Processor_UpdateProfileBlocks::COMPONENT_BLOCK_PROFILEINDIVIDUAL_UPDATE],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_myusers = PoP_Application_Utils::getDefaultformatByScreen(POP_URE_SCREEN_MYUSERS);

        $routeComponents_navigator = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR],
        );
        foreach ($routeComponents_navigator as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_users == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        $routeComponents_addons = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_ORGANIZATIONS_SCROLL_ADDONS],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_INDIVIDUALS_SCROLL_ADDONS],
        );
        foreach ($routeComponents_addons as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_users == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_typeahead = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD],
        );
        foreach ($routeComponents_typeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_users == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_details = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_ORGANIZATIONS_SCROLL_DETAILS],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_INDIVIDUALS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_users == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_users == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_ORGANIZATIONS_SCROLL_LIST],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomSectionBlocks::class, GD_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_INDIVIDUALS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_users == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
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
		new PoP_CommonUserRolesProcessors_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
