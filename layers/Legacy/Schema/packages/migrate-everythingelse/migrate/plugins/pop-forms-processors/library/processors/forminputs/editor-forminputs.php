<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_EditorFormInputs extends PoP_Module_Processor_EditorFormInputsBase
{
    public final const MODULE_FORMINPUT_EDITOR = 'forminputeditor';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EDITOR],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return TranslationAPIFacade::getInstance()->__('Content', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function getName(array $component): string
    {
        // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return 'forminputeditor';
        }
        return parent::getName($component);
    }
}



