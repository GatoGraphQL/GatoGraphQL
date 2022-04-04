<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_EditorFormInputs extends PoP_Module_Processor_EditorFormInputsBase
{
    public final const MODULE_FORMINPUT_EDITOR = 'forminputeditor';
    
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EDITOR],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return TranslationAPIFacade::getInstance()->__('Content', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function getName(array $module): string
    {
        // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EDITOR:
                return 'forminputeditor';
        }
        return parent::getName($module);
    }
}



