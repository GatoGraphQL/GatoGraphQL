<?php

use PoPSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoPTheme_Wassup_RelatedPosts_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Single route modules
        $default_format_singlesection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLESECTION);

        $routemodules_details = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_DETAILS) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_SIMPLEVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::class, PoP_RelatedPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singlesection == POP_FORMAT_LIST) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['module' => $module];
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
		new PoPTheme_Wassup_RelatedPosts_Module_MainContentRouteModuleProcessor()
	);
}, 200);
