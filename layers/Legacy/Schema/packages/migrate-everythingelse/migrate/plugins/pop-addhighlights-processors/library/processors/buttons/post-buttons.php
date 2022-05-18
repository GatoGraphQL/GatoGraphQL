<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const MODULE_BUTTON_HIGHLIGHT_CREATE = 'postbutton-highlight-create';
    public final const MODULE_BUTTON_HIGHLIGHT_CREATEBTN = 'postbutton-highlight-createbtn';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_HIGHLIGHT_CREATE],
            [self::class, self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_ButtonInners::class, PoP_AddHighlights_Module_Processor_ButtonInners::MODULE_BUTTONINNER_HIGHLIGHT_CREATE],
            self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN => [PoP_AddHighlights_Module_Processor_ButtonInners::class, PoP_AddHighlights_Module_Processor_ButtonInners::MODULE_BUTTONINNER_HIGHLIGHT_CREATEBTN],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_HIGHLIGHT_CREATE:
            case self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::get_selectabletypeahead_template($componentVariation);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_HIGHLIGHT_CREATE:
            case self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN:
                $ret .= 'btn btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        $extract = TranslationAPIFacade::getInstance()->__('Add Highlight', 'poptheme-wassup');
        $titles = array(
            self::MODULE_BUTTON_HIGHLIGHT_CREATE => $extract,
            self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN => $extract,
        );
        if ($title = $titles[$componentVariation[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getUrlField(array $componentVariation)
    {
        $fields = array(
            self::MODULE_BUTTON_HIGHLIGHT_CREATE => 'addhighlightURL',
            self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN => 'addhighlightURL',
        );
        if ($field = $fields[$componentVariation[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($componentVariation);
    }
}



