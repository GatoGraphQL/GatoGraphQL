<?php

use PoP\Root\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Pages\Routing\RouteNatures as PageRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoP_Module_TabContentPageSectionRouteModuleProcessor extends PoP_Module_TabContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $nature_modules = array(
            RouteNatures::HOME => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_HOME],
            UserRouteNatures::USER => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_AUTHOR],
            CustomPostRouteNatures::CUSTOMPOST => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_SINGLE],
            TagRouteNatures::TAG => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_TAG],
            RouteNatures::NOTFOUND => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_404],
            PageRouteNatures::PAGE => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_PAGE],
            RouteNatures::GENERIC => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_ROUTE],
        );
        foreach ($nature_modules as $nature => $module) {
            $ret[$nature][] = [
                'module' => $module,
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
		new PoP_Module_TabContentPageSectionRouteModuleProcessor()
	);
}, 200);
