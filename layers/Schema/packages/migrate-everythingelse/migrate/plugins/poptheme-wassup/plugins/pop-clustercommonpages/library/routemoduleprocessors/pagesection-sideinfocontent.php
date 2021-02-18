<?php

use PoPSchema\Pages\Routing\RouteNatures as PageRouteNatures;

class PoPTheme_Wassup_ClusterCommonPages_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
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
            $ret[PageRouteNatures::PAGE][] = [
                'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR],
                'conditions' => [
                    'routing-state' => [
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_ClusterCommonPages_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
