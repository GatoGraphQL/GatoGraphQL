<?php

class GD_EM_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS = 'em-dropdownbuttonquicklink-downloadlinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::MODULE_EM_BUTTON_GOOGLECALENDAR];
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::MODULE_EM_BUTTON_ICAL];
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($componentVariation);
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'fa-thumb-tack';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
}


