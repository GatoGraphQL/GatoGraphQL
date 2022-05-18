<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE = 'dropdownbuttonquicklink-postshare';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE = 'dropdownbuttonquicklink-usershare';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO = 'dropdownbuttonquicklink-usercontactinfo';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE = 'dropdownbuttonquicklink-tagshare';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $components = array();
        switch ($component[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                $components[] = [PoP_Module_Processor_UserQuickLinkLayouts::class, PoP_Module_Processor_UserQuickLinkLayouts::MODULE_LAYOUTUSER_QUICKLINKS];
                break;
        }

        // Allow PoP Generic Forms Processors to add modules
        $components = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $components,
            $component
        );
        $ret = array_merge(
            $ret,
            $components
        );

        return $ret;
    }

    public function getBtnClass(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($component);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return TranslationAPIFacade::getInstance()->__('Options', 'pop-coreprocessors');

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return TranslationAPIFacade::getInstance()->__('Contact/Links', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }

    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'fa-angle-down';

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $this->appendProp($component, $props, 'class', 'pull-right');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


