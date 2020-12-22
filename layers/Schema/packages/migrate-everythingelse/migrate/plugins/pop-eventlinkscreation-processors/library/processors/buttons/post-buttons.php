<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_EventLinksCreation_Module_Processor_Buttons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public const MODULE_BUTTON_EVENTLINK_CREATE = 'postbutton-eventlink-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_EVENTLINK_CREATE],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_EVENTLINK_CREATE => [PoP_EventLinksCreation_Module_Processor_ButtonInners::class, PoP_EventLinksCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_EVENTLINK_CREATE],
        );
        if ($buttoninner = $buttoninners[$module[1]]) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_EVENTLINK_CREATE:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_EVENTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_BUTTON_EVENTLINK_CREATE => TranslationAPIFacade::getInstance()->__('Event link', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]]) {
            return $title;
        }
        
        return parent::getTitle($module, $props);
    }

    public function getUrlField(array $module)
    {
        $fields = array(
            self::MODULE_BUTTON_EVENTLINK_CREATE => 'addEventLinkURL',
        );
        if ($field = $fields[$module[1]]) {
            return $field;
        }
        
        return parent::getUrlField($module);
    }
}



