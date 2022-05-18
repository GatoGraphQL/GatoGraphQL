<?php

class PoP_Module_Processor_MaxHeightLayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT = 'multicomponent-simpleview-postcontent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT:
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES_LINE];
                $ret[] = [PoP_Module_Processor_MaxHeightLayouts::class, PoP_Module_Processor_MaxHeightLayouts::MODULE_LAYOUT_MAXHEIGHT_POSTCONTENT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT:
                // Change the "In response to" tag from 'h4' to 'em'
                $this->setProp([PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGET_REFERENCES_LINE], $props, 'title-htmltag', 'em');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



