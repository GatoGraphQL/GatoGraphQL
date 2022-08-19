<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_BUTTON_HIGHLIGHTEDIT = 'button-highlightedit';
    public final const COMPONENT_BUTTON_HIGHLIGHTVIEW = 'button-highlightview';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTON_HIGHLIGHTEDIT,
            self::COMPONENT_BUTTON_HIGHLIGHTVIEW,
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_HIGHLIGHTEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTEDIT],
            self::COMPONENT_BUTTON_HIGHLIGHTVIEW => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTVIEW],
        );
        if ($buttoninner = $buttoninners[$component->name] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_HIGHLIGHTEDIT => 'editURL',
            // self::COMPONENT_BUTTON_HIGHLIGHTVIEW => 'highlightedPostURL',
        );
        if ($field = $fields[$component->name] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_BUTTON_HIGHLIGHTEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
            self::COMPONENT_BUTTON_HIGHLIGHTVIEW => TranslationAPIFacade::getInstance()->__('View', 'poptheme-wassup'),
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_HIGHLIGHTEDIT:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BUTTON_HIGHLIGHTVIEW:
            case self::COMPONENT_BUTTON_HIGHLIGHTEDIT:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


