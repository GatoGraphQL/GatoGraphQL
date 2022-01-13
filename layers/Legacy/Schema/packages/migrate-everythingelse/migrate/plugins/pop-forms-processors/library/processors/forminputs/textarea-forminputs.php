<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public const MODULE_FORMINPUT_TEXTAREAEDITOR = 'forminput-textarea-editor';
    public const MODULE_FORMINPUT_EMAILS = 'forminput-emails';
    public const MODULE_FORMINPUT_ADDITIONALMESSAGE = 'forminput-additionalmessage';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXTAREAEDITOR],
            [self::class, self::MODULE_FORMINPUT_EMAILS],
            [self::class, self::MODULE_FORMINPUT_ADDITIONALMESSAGE],
        );
    }

    public function getRows(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
                return 5;
        }

        return parent::getRows($module, $props);
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
                return TranslationAPIFacade::getInstance()->__('Content', 'pop-coreprocessors');
                
            case self::MODULE_FORMINPUT_EMAILS:
                return TranslationAPIFacade::getInstance()->__('Email(s)', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_ADDITIONALMESSAGE:
                return TranslationAPIFacade::getInstance()->__('Additional message', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
            case self::MODULE_FORMINPUT_EMAILS:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
                return 'contentEdit';
        }
        
        return parent::getDbobjectField($module);
    }

    public function clearInput(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EMAILS:
                return true;
        }

        return parent::clearInput($module, $props);
    }
}



