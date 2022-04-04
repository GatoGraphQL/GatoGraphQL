<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const MODULE_BUTTON_HIGHLIGHT_CREATE = 'postbutton-highlight-create';
    public final const MODULE_BUTTON_HIGHLIGHT_CREATEBTN = 'postbutton-highlight-createbtn';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_HIGHLIGHT_CREATE],
            [self::class, self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_ButtonInners::class, PoP_AddHighlights_Module_Processor_ButtonInners::MODULE_BUTTONINNER_HIGHLIGHT_CREATE],
            self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN => [PoP_AddHighlights_Module_Processor_ButtonInners::class, PoP_AddHighlights_Module_Processor_ButtonInners::MODULE_BUTTONINNER_HIGHLIGHT_CREATEBTN],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_HIGHLIGHT_CREATE:
            case self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::get_selectabletypeahead_template($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_HIGHLIGHT_CREATE:
            case self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN:
                $ret .= 'btn btn-link';
                break;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
    {
        $extract = TranslationAPIFacade::getInstance()->__('Add Highlight', 'poptheme-wassup');
        $titles = array(
            self::MODULE_BUTTON_HIGHLIGHT_CREATE => $extract,
            self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN => $extract,
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($module, $props);
    }

    public function getUrlField(array $module)
    {
        $fields = array(
            self::MODULE_BUTTON_HIGHLIGHT_CREATE => 'addhighlightURL',
            self::MODULE_BUTTON_HIGHLIGHT_CREATEBTN => 'addhighlightURL',
        );
        if ($field = $fields[$module[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($module);
    }
}



