<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public const MODULE_BUTTON_HIGHLIGHTEDIT = 'button-highlightedit';
    public const MODULE_BUTTON_HIGHLIGHTVIEW = 'button-highlightview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_HIGHLIGHTEDIT],
            [self::class, self::MODULE_BUTTON_HIGHLIGHTVIEW],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_HIGHLIGHTEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTEDIT],
            self::MODULE_BUTTON_HIGHLIGHTVIEW => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTVIEW],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getUrlField(array $module)
    {
        $fields = array(
            self::MODULE_BUTTON_HIGHLIGHTEDIT => 'editURL',
            // self::MODULE_BUTTON_HIGHLIGHTVIEW => 'highlightedPostURL',
        );
        if ($field = $fields[$module[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($module);
    }

    public function getTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_BUTTON_HIGHLIGHTEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
            self::MODULE_BUTTON_HIGHLIGHTVIEW => TranslationAPIFacade::getInstance()->__('View', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($module, $props);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_HIGHLIGHTEDIT:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_BUTTON_HIGHLIGHTVIEW:
            case self::MODULE_BUTTON_HIGHLIGHTEDIT:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


