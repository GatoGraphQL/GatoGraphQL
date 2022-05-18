<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_TEXTAREAEDITOR = 'forminput-textarea-editor';
    public final const MODULE_FORMINPUT_EMAILS = 'forminput-emails';
    public final const MODULE_FORMINPUT_ADDITIONALMESSAGE = 'forminput-additionalmessage';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXTAREAEDITOR],
            [self::class, self::MODULE_FORMINPUT_EMAILS],
            [self::class, self::MODULE_FORMINPUT_ADDITIONALMESSAGE],
        );
    }

    public function getRows(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
                return 5;
        }

        return parent::getRows($componentVariation, $props);
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
                return TranslationAPIFacade::getInstance()->__('Content', 'pop-coreprocessors');
                
            case self::MODULE_FORMINPUT_EMAILS:
                return TranslationAPIFacade::getInstance()->__('Email(s)', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_ADDITIONALMESSAGE:
                return TranslationAPIFacade::getInstance()->__('Additional message', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
            case self::MODULE_FORMINPUT_EMAILS:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_TEXTAREAEDITOR:
                return 'contentEdit';
        }
        
        return parent::getDbobjectField($componentVariation);
    }

    public function clearInput(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EMAILS:
                return true;
        }

        return parent::clearInput($componentVariation, $props);
    }
}



