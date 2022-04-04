<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE = 'dropdownbuttonquicklink-postshare';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE = 'dropdownbuttonquicklink-usershare';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO = 'dropdownbuttonquicklink-usercontactinfo';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE = 'dropdownbuttonquicklink-tagshare';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $modules = array();
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $modules[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                $modules[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $modules[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                $modules[] = [PoP_Module_Processor_UserQuickLinkLayouts::class, PoP_Module_Processor_UserQuickLinkLayouts::MODULE_LAYOUTUSER_QUICKLINKS];
                break;
        }

        // Allow PoP Generic Forms Processors to add modules
        $modules = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $modules,
            $module
        );
        $ret = array_merge(
            $ret,
            $modules
        );

        return $ret;
    }

    public function getBtnClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($module);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return TranslationAPIFacade::getInstance()->__('Options', 'pop-coreprocessors');

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return TranslationAPIFacade::getInstance()->__('Contact/Links', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'fa-angle-down';

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return 'fa-link';
        }

        return parent::getFontawesome($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $this->appendProp($module, $props, 'class', 'pull-right');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


