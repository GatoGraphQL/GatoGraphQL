<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_TrendingTags_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_tags = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGS);

        $routemodules_tagdetails = array(
            POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS => [PoP_TrendingTags_Module_Processor_SectionBlocks::class, PoP_TrendingTags_Module_Processor_SectionBlocks::MODULE_BLOCK_TRENDINGTAGS_SCROLL_DETAILS],
        );
        foreach ($routemodules_tagdetails as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_taglist = array(
            POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS => [PoP_TrendingTags_Module_Processor_SectionBlocks::class, PoP_TrendingTags_Module_Processor_SectionBlocks::MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST],
        );
        foreach ($routemodules_taglist as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
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
		new PoPTheme_Wassup_TrendingTags_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
