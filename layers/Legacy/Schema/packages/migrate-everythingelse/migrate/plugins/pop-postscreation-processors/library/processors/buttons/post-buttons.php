<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const MODULE_BUTTON_CONTENTPOSTLINK_CREATE = 'postbutton-postlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTON_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_CONTENTPOSTLINK_CREATE => [PoP_ContentPostLinksCreation_Module_Processor_ButtonInners::class, PoP_ContentPostLinksCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTON_CONTENTPOSTLINK_CREATE:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTON_CONTENTPOSTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getTitle(array $component, array &$props)
    {
        $extract = TranslationAPIFacade::getInstance()->__('Add Highlight', 'poptheme-wassup');
        $titles = array(
            self::COMPONENT_BUTTON_CONTENTPOSTLINK_CREATE => TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(array $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_CONTENTPOSTLINK_CREATE => 'addContentPostLinkURL',
        );
        if ($field = $fields[$component[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }
}



