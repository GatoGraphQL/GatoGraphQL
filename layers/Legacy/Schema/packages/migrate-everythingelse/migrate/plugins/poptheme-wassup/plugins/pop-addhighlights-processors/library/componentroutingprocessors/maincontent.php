<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_AddHighlights_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT => [PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_HIGHLIGHT_CREATE],
            POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT => [PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_HIGHLIGHT_UPDATE],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        // Page modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_highlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_HIGHLIGHTS);
        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        $default_format_myhighlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYHIGHLIGHTS);

        $routeComponents_addons = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_ADDONS],
        );
        foreach ($routeComponents_addons as $route => $component) {
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

        $routeComponents_highlighttypeahead = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HIGHLIGHTS_TYPEAHEAD],
        );
        foreach ($routeComponents_highlighttypeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_highlights == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_highlightfullview = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_highlightfullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_highlights == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_highlightthumbnail = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_highlightthumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_highlights == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_highlightlist = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_LIST],
        );
        foreach ($routeComponents_highlightlist as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_highlights == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_myhighlights = array(
            POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_MySectionBlocks::class, PoP_AddHighlights_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYHIGHLIGHTS_TABLE_EDIT],
        );
        foreach ($routeComponents_myhighlights as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_myhighlights == POP_FORMAT_TABLE) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_myhighlights_fullviewpreviews = array(
            POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_MySectionBlocks::class, PoP_AddHighlights_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routeComponents_myhighlights_fullviewpreviews as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        $default_format_authortags = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORTAGS);
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_highlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_HIGHLIGHTS);

        $routeComponents_highlightfullview = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_highlightfullview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_highlights == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_highlightthumbnail = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_highlightthumbnail as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_highlights == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_highlightlist = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST],
        );
        foreach ($routeComponents_highlightlist as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_highlights == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }

        // Single route modules
        $default_format_singlesection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLESECTION);
        $default_format_singleusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEUSERS);
        $default_format_singlehighlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEHIGHLIGHTS);

        $routeComponents_highlightfullview = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_highlightfullview as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singlehighlights == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_highlightthumbnail = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_highlightthumbnail as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singlehighlights == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_highlightlist = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSectionBlocks::class, PoP_AddHighlights_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
        );
        foreach ($routeComponents_highlightlist as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singlehighlights == POP_FORMAT_LIST) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
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
		new PoPTheme_Wassup_AddHighlights_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
