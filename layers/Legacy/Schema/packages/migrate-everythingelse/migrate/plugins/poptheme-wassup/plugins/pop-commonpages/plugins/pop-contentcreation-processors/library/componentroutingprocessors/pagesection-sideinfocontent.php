<?php

use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;

class PoPTheme_Wassup_CommonPages_ContentCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $pages = array(
            POP_COMMONPAGES_PAGE_ABOUT_CONTENTGUIDELINES,
        );
        foreach ($pages as $page) {
            $ret[PageRequestNature::PAGE][] = [
                'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR],
                'conditions' => [
                    'routing' => [
                        'queried-object-id' => $page,
                    ],
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
		new PoPTheme_Wassup_CommonPages_ContentCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
