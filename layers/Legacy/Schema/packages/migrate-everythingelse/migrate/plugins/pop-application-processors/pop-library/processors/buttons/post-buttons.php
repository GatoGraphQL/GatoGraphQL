<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const MODULE_BUTTON_POST_CREATE = 'postbutton-post-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_POST_CREATE],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_POST_CREATE => [Wassup_Module_Processor_ButtonInners::class, Wassup_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POST_CREATE],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_POST_CREATE:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::get_selectabletypeahead_template($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_POST_CREATE:
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
            self::MODULE_BUTTON_POST_CREATE => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($module, $props);
    }

    public function getUrlField(array $module)
    {
        $fields = array(
            self::MODULE_BUTTON_POST_CREATE => 'addpostURL',
        );
        if ($field = $fields[$module[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($module);
    }
}



