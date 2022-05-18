<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // Single
        $routemodules = array(
            POP_ROUTE_AUTHORS => [PoP_Module_Processor_SingleTabPanelSectionBlocks::class, PoP_Module_Processor_SingleTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_SINGLEAUTHORS],
        );
        foreach ($routemodules as $route => $componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[UserRequestNature::USER][] = [
            'component-variation' => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCONTENT],
            'conditions' => [
                'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
            ],
        ];
        $ret[TagRequestNature::TAG][] = [
            'component-variation' => [PoP_Module_Processor_TagTabPanelSectionBlocks::class, PoP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCONTENT],
            'conditions' => [
                'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
            ],
        ];
        $ret[RequestNature::HOME][] = [
            'component-variation' => [PoP_Module_Processor_HomeTabPanelSectionBlocks::class, PoP_Module_Processor_HomeTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_HOMECONTENT],
            'conditions' => [
                'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
            ],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
