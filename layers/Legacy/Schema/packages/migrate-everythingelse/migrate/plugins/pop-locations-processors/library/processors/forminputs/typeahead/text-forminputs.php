<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION = 'forminput-text-typeaheadaddlocation';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Location(s)', 'em-popprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION:
                // Use the label as placeholder
                $this->setProp($module, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Name or Address', 'em-popprocessors'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



