<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE = 'dropdownbuttonquicklink-postshare';
    public final const COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE = 'dropdownbuttonquicklink-usershare';
    public final const COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO = 'dropdownbuttonquicklink-usercontactinfo';
    public final const COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE = 'dropdownbuttonquicklink-tagshare';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE,
            self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE,
            self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO,
            self::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $components = array();
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                $components[] = [PoP_Module_Processor_UserQuickLinkLayouts::class, PoP_Module_Processor_UserQuickLinkLayouts::COMPONENT_LAYOUTUSER_QUICKLINKS];
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

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($component);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return TranslationAPIFacade::getInstance()->__('Options', 'pop-coreprocessors');

            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return TranslationAPIFacade::getInstance()->__('Contact/Links', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'fa-angle-down';

            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $this->appendProp($component, $props, 'class', 'pull-right');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


