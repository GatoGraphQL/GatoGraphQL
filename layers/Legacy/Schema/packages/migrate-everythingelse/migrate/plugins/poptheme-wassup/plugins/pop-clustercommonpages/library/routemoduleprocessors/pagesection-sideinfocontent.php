<?php

use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;

class PoPTheme_Wassup_ClusterCommonPages_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $pages = array(
            POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_HOWTOUSEWEBSITE,
            POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_OURMISSION,
            POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_OURSTORY,
            POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_VOLUNTEERWITHUS,
            POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_INTHEMEDIA,
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
		new PoPTheme_Wassup_ClusterCommonPages_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
