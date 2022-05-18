<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_ApplicationProcessors_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_HIGHLIGHTS],
            POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYHIGHLIGHTS],
            POP_POSTSCREATION_ROUTE_MYPOSTS => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_MYPOSTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SingleSectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_SingleSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT],
        );
        foreach ($routemodules as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORHIGHLIGHTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_ApplicationProcessors_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
