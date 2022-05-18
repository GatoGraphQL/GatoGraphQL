<?php

class PoP_Module_Processor_SegmentedButtonLinks extends PoP_Module_Processor_SegmentedButtonLinksBase
{
    public final const MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR = 'layout-segmentedbutton-navigator';
    public final const MODULE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR = 'layout-dropdownsegmentedbutton-navigator';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR],
        );
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
            case self::MODULE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR:
                return 'fa-folder-open-o';
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
            case self::MODULE_LAYOUT_DROPDOWNSEGMENTEDBUTTON_NAVIGATOR:
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        // 'data-intercept-target' => GD_INTERCEPT_TARGET_NAVIGATOR,
                        'target' => GD_INTERCEPT_TARGET_NAVIGATOR,
                    )
                );
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_SEGMENTEDBUTTON_NAVIGATOR:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-default btn-background');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



