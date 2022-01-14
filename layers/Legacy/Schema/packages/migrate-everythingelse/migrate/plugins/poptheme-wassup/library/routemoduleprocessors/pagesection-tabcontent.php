<?php

use PoP\Root\Routing\RequestNature;
use PoPSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Module_TabContentPageSectionRouteModuleProcessor extends PoP_Module_TabContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $nature_modules = array(
            RequestNature::HOME => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_HOME],
            UserRequestNature::USER => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_AUTHOR],
            CustomPostRequestNature::CUSTOMPOST => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_SINGLE],
            TagRequestNature::TAG => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_TAG],
            RequestNature::NOTFOUND => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_404],
            PageRequestNature::PAGE => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_PAGE],
            RequestNature::GENERIC => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::MODULE_LAYOUT_PAGETABS_ROUTE],
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
