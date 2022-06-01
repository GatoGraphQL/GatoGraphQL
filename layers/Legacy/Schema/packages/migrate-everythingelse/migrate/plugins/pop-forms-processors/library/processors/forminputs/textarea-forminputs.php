<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const COMPONENT_FORMINPUT_TEXTAREAEDITOR = 'forminput-textarea-editor';
    public final const COMPONENT_FORMINPUT_EMAILS = 'forminput-emails';
    public final const COMPONENT_FORMINPUT_ADDITIONALMESSAGE = 'forminput-additionalmessage';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_TEXTAREAEDITOR],
            [self::class, self::COMPONENT_FORMINPUT_EMAILS],
            [self::class, self::COMPONENT_FORMINPUT_ADDITIONALMESSAGE],
        );
    }

    public function getRows(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXTAREAEDITOR:
                return 5;
        }

        return parent::getRows($component, $props);
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXTAREAEDITOR:
                return TranslationAPIFacade::getInstance()->__('Content', 'pop-coreprocessors');
                
            case self::COMPONENT_FORMINPUT_EMAILS:
                return TranslationAPIFacade::getInstance()->__('Email(s)', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_ADDITIONALMESSAGE:
                return TranslationAPIFacade::getInstance()->__('Additional message', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXTAREAEDITOR:
            case self::COMPONENT_FORMINPUT_EMAILS:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXTAREAEDITOR:
                return 'contentEdit';
        }
        
        return parent::getDbobjectField($component);
    }

    public function clearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EMAILS:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



