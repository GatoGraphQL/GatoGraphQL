<?php

class PoP_Module_ContentPageSectionTopLevelRouteModuleProcessor extends PoP_Module_ContentPageSectionTopLevelRouteModuleProcessorBase
{
    /**
     * @return array<string, string[]>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        // The modules below are chosen when the correspoding target is set
        $target_modules = array(
            POP_TARGET_SIDE => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_SIDE],
            POP_TARGET_TOP => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_TOP],
            POP_TARGET_BACKGROUND => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BACKGROUND],
            POP_TARGET_NAVIGATOR => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_NAVIGATOR],
            POP_TARGET_FRAMECOMPONENTS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_FRAMECOMPONENTS],
            POP_TARGET_ADDONS => [PoP_Module_Processor_Windows::class, PoP_Module_Processor_Windows::MODULE_WINDOW_ADDONS],
            POP_TARGET_MODALS => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_MODALS],
            POP_TARGET_QUICKVIEW => [PoP_Module_Processor_Modals::class, PoP_Module_Processor_Modals::MODULE_MODAL_QUICKVIEW],
            POP_TARGET_HOVER => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_HOVER],
            POP_TARGET_HOLE => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_HOLE],
        );
        foreach ($target_modules as $target => $module) {
            $ret[] = [
                'module' => $module,
                'conditions' => [
                    'target' => $target,
                ],
            ];
        }

        $ret[] = [
            'module' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODY],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_ContentPageSectionTopLevelRouteModuleProcessor()
	);
}, 200);
