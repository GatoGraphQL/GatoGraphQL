<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoPTheme_Wassup_RelatedPosts_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // Single route modules
        $default_format_singlesection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLESECTION);

        $routeComponents_details = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_DETAILS) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_SIMPLEVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_LIST) {
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
		new PoPTheme_Wassup_RelatedPosts_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
