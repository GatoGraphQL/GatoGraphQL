<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Module_Processor_MultiTargetIndentMenuLayouts extends PoP_Module_Processor_MultiTargetIndentMenuLayoutsBase
{
    public const MODULE_LAYOUT_MENU_MULTITARGETINDENT = 'layout-menu-multitargetindent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_MULTITARGETINDENT],
        );
    }

    public function getTargets(array $module, array &$props)
    {
        $ret = parent::getTargets($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_MULTITARGETINDENT:
                $ret[POP_TARGET_NAVIGATOR] = '<i class="fa fa-fw fa-angle-right"></i>';
                
                // $icon = '<i class="fa fa-fw fa-angle-right"></i>';
                // $ret[\PoP\ConfigurationComponentModel\Constants\Targets::MAIN] = $icon.TranslationAPIFacade::getInstance()->__('Main', 'pop-coreprocessors');
                // $ret[POP_TARGET_NAVIGATOR] = $icon.TranslationAPIFacade::getInstance()->__('Navigator', 'pop-coreprocessors');
                // $ret[POP_TARGET_ADDONS] = $icon.TranslationAPIFacade::getInstance()->__('Floating', 'pop-coreprocessors');
                break;
        }
    
        return $ret;
    }

    public function getMultitargetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_MULTITARGETINDENT:
                // Do not show for mobile phone
                return 'hidden-xs';
        }

        return parent::getMultitargetClass($module, $props);
    }

    public function getMultitargetTooltip(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_MULTITARGETINDENT:
                return TranslationAPIFacade::getInstance()->__('Navigate', 'pop-coreprocessors');
        }

        return parent::getMultitargetTooltip($module, $props);
    }

    // function getDropdownmenuClass(array $module, array &$props) {

    //     switch ($module[1]) {

    //         case self::MODULE_LAYOUT_MENU_MULTITARGETINDENT:
                
    //             // Do not show for mobile phone
    //             return 'hidden-xs';
    //     }

    //     return parent::getDropdownmenuClass($module, $props);
    // }
}


