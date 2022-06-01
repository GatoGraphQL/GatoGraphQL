<?php

class PoP_Module_Processor_SegmentedButtonLinks extends PoP_Module_Processor_SegmentedButtonLinksBase
{
    public final const COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR = 'layout-segmentedbutton-navigator';
    public final const COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR = 'layout-dropdownsegmentedbutton-navigator';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR,
            self::COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR,
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
            case self::COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR:
                return 'fa-folder-open-o';
        }

        return parent::getFontawesome($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
            case self::COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        // 'data-intercept-target' => GD_INTERCEPT_TARGET_NAVIGATOR,
                        'target' => GD_INTERCEPT_TARGET_NAVIGATOR,
                    )
                );
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
                $this->appendProp($component, $props, 'class', 'btn btn-default btn-background');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



