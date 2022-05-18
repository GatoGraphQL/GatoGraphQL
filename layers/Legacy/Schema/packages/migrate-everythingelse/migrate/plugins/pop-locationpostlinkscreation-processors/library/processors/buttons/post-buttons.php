<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_SP_Custom_EM_Module_Processor_Buttons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const MODULE_BUTTON_LOCATIONPOSTLINK_CREATE = 'postbutton-locationpostlink-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => [GD_SP_Custom_EM_Module_Processor_ButtonInners::class, GD_SP_Custom_EM_Module_Processor_ButtonInners::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getTitle(array $module, array &$props)
    {
        $link = TranslationAPIFacade::getInstance()->__('%s link', 'pop-locationpostlinkscreation-processors');
        $titles = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => sprintf($link, PoP_LocationPosts_PostNameUtils::getNameUc()),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($module, $props);
    }

    public function getUrlField(array $module)
    {
        $fields = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => 'addLocationPostLinkURL',
        );
        if ($field = $fields[$module[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($module);
    }
}



