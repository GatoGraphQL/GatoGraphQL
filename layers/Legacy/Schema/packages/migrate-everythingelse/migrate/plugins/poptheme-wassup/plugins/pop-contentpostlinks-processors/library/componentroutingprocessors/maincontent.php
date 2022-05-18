<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_CPL_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        // Page modules
        $routemodules_addons = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LINKS_SCROLL_ADDONS],
        );
        foreach ($routemodules_addons as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routemodules_typeahead = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routemodules_thumbnail = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LINKS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routemodules_list = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LINKS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routemodules_details = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LINKS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routemodules_simpleview = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LINKS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routemodules_fullview = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LINKS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }


        // Tag modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);

        $routemodules_details = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLINKS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routemodules_simpleview = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routemodules_fullview = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLINKS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routemodules_thumbnail = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLINKS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routemodules_list = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLINKS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routemodules_details = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLINKS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routemodules_simpleview = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routemodules_fullview = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routemodules_thumbnail = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routemodules_list = array(
            POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::class, PoP_ContentPostLinks_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLINKS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
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
		new PoPTheme_Wassup_CPL_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
