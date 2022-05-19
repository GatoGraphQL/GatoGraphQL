<?php

class PoP_Module_Processor_SegmentedButtonLinks extends PoP_Module_Processor_SegmentedButtonLinksBase
{
    public final const COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR = 'layout-segmentedbutton-navigator';
    public final const COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR = 'layout-dropdownsegmentedbutton-navigator';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR],
            [self::class, self::COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
            case self::COMPONENT_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR:
                return 'fa-folder-open-o';
        }

        return parent::getFontawesome($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
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

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
                $this->appendProp($component, $props, 'class', 'btn btn-default btn-background');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



