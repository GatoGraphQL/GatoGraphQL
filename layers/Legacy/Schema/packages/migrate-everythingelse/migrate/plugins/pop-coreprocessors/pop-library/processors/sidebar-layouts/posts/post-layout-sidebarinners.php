<?php

class PoP_Module_Processor_PostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL = 'layout-postconclusionsidebarinner-horizontal';
    public final const COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL = 'layout-subjugatedpostconclusionsidebarinner-horizontal';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
            [self::class, self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
                $ret[] = [PoP_Module_Processor_PostMultipleSidebarComponents::class, PoP_Module_Processor_PostMultipleSidebarComponents::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT];
                $ret[] = [PoP_Module_Processor_PostMultipleSidebarComponents::class, PoP_Module_Processor_PostMultipleSidebarComponents::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT];
                break;

            case self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
                $ret[] = [PoP_Module_Processor_PostMultipleSidebarComponents::class, PoP_Module_Processor_PostMultipleSidebarComponents::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT];
                $ret[] = [PoP_Module_Processor_PostMultipleSidebarComponents::class, PoP_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT];
                break;
        }

        return $ret;
    }

    // function getWrapperClass(array $component) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
    //         case self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:

    //             return 'row';
    //     }

    //     return parent::getWrapperClass($component);
    // }

    // function getWidgetwrapperClass(array $component) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
    //         case self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:

    //             return 'col-xsm-6';
    //     }

    //     return parent::getWidgetwrapperClass($component);
    // }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
            case self::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
                $this->appendProp([PoP_Module_Processor_PostMultipleSidebarComponents::class, PoP_Module_Processor_PostMultipleSidebarComponents::COMPONENT_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT], $props, 'class', 'pull-right');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



