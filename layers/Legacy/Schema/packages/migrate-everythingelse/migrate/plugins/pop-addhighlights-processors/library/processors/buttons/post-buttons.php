<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const COMPONENT_BUTTON_HIGHLIGHT_CREATE = 'postbutton-highlight-create';
    public final const COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN = 'postbutton-highlight-createbtn';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTON_HIGHLIGHT_CREATE],
            [self::class, self::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN],
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_ButtonInners::class, PoP_AddHighlights_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATE],
            self::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN => [PoP_AddHighlights_Module_Processor_ButtonInners::class, PoP_AddHighlights_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATEBTN],
        );
        if ($buttoninner = $buttoninners[$component->name] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getTargetDynamicallyRenderedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_HIGHLIGHT_CREATE:
            case self::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::get_selectabletypeahead_template($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_HIGHLIGHT_CREATE:
            case self::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN:
                $ret .= 'btn btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $extract = TranslationAPIFacade::getInstance()->__('Add Highlight', 'poptheme-wassup');
        $titles = array(
            self::COMPONENT_BUTTON_HIGHLIGHT_CREATE => $extract,
            self::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN => $extract,
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_HIGHLIGHT_CREATE => 'addhighlightURL',
            self::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN => 'addhighlightURL',
        );
        if ($field = $fields[$component->name] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }
}



