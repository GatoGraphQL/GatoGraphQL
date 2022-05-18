<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION = 'forminput-text-typeaheadaddlocation';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Location(s)', 'em-popprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION:
                // Use the label as placeholder
                $this->setProp($componentVariation, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Name or Address', 'em-popprocessors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



