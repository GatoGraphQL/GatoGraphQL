<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_EditorFormInputs extends PoP_Module_Processor_EditorFormInputsBase
{
    public final const MODULE_FORMINPUT_EDITOR = 'forminputeditor';
    
    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EDITOR],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return TranslationAPIFacade::getInstance()->__('Content', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }

    public function getName(array $componentVariation): string
    {
        // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return 'forminputeditor';
        }
        return parent::getName($componentVariation);
    }
}



