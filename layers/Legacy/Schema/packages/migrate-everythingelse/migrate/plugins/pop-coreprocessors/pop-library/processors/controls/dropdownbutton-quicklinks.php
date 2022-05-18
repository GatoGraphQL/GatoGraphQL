<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE = 'dropdownbuttonquicklink-postshare';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE = 'dropdownbuttonquicklink-usershare';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO = 'dropdownbuttonquicklink-usercontactinfo';
    public final const MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE = 'dropdownbuttonquicklink-tagshare';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO],
            [self::class, self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $componentVariations = array();
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $componentVariations[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                $componentVariations[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $componentVariations[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN];
                break;

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                $componentVariations[] = [PoP_Module_Processor_UserQuickLinkLayouts::class, PoP_Module_Processor_UserQuickLinkLayouts::MODULE_LAYOUTUSER_QUICKLINKS];
                break;
        }

        // Allow PoP Generic Forms Processors to add modules
        $componentVariations = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $componentVariations,
            $componentVariation
        );
        $ret = array_merge(
            $ret,
            $componentVariations
        );

        return $ret;
    }

    public function getBtnClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($componentVariation);
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return TranslationAPIFacade::getInstance()->__('Options', 'pop-coreprocessors');

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return TranslationAPIFacade::getInstance()->__('Contact/Links', 'pop-coreprocessors');
        }

        return parent::getLabel($componentVariation, $props);
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                return 'fa-angle-down';

            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
                return 'fa-link';
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO:
            case self::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $this->appendProp($componentVariation, $props, 'class', 'pull-right');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


