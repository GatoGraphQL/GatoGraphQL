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

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => [GD_SP_Custom_EM_Module_Processor_ButtonInners::class, GD_SP_Custom_EM_Module_Processor_ButtonInners::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($componentVariation);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        $link = TranslationAPIFacade::getInstance()->__('%s link', 'pop-locationpostlinkscreation-processors');
        $titles = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => sprintf($link, PoP_LocationPosts_PostNameUtils::getNameUc()),
        );
        if ($title = $titles[$componentVariation[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getUrlField(array $componentVariation)
    {
        $fields = array(
            self::MODULE_BUTTON_LOCATIONPOSTLINK_CREATE => 'addLocationPostLinkURL',
        );
        if ($field = $fields[$componentVariation[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($componentVariation);
    }
}



