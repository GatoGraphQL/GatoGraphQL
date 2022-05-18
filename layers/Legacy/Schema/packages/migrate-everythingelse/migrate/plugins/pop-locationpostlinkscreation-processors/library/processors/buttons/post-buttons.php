<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_SP_Custom_EM_Module_Processor_Buttons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const MODULE_BUTTON_LOCATIONPOSTLINK_CREATE = 'postbutton-locationpostlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => [GD_SP_Custom_EM_Module_Processor_ButtonInners::class, GD_SP_Custom_EM_Module_Processor_ButtonInners::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getTitle(array $component, array &$props)
    {
        $link = TranslationAPIFacade::getInstance()->__('%s link', 'pop-locationpostlinkscreation-processors');
        $titles = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => sprintf($link, PoP_LocationPosts_PostNameUtils::getNameUc()),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(array $component)
    {
        $fields = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => 'addLocationPostLinkURL',
        );
        if ($field = $fields[$component[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }
}



