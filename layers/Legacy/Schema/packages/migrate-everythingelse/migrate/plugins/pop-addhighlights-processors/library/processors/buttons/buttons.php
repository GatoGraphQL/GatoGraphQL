<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_BUTTON_HIGHLIGHTEDIT = 'button-highlightedit';
    public final const MODULE_BUTTON_HIGHLIGHTVIEW = 'button-highlightview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_HIGHLIGHTEDIT],
            [self::class, self::MODULE_BUTTON_HIGHLIGHTVIEW],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_HIGHLIGHTEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTEDIT],
            self::MODULE_BUTTON_HIGHLIGHTVIEW => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTVIEW],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getUrlField(array $component)
    {
        $fields = array(
            self::MODULE_BUTTON_HIGHLIGHTEDIT => 'editURL',
            // self::MODULE_BUTTON_HIGHLIGHTVIEW => 'highlightedPostURL',
        );
        if ($field = $fields[$component[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }

    public function getTitle(array $component, array &$props)
    {
        $titles = array(
            self::MODULE_BUTTON_HIGHLIGHTEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
            self::MODULE_BUTTON_HIGHLIGHTVIEW => TranslationAPIFacade::getInstance()->__('View', 'poptheme-wassup'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_BUTTON_HIGHLIGHTEDIT:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::MODULE_BUTTON_HIGHLIGHTVIEW:
            case self::MODULE_BUTTON_HIGHLIGHTEDIT:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


