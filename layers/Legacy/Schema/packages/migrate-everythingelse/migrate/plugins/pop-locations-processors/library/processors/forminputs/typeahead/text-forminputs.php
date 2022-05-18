<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_TEXT_TYPEAHEADADDLOCATION = 'forminput-text-typeaheadaddlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADADDLOCATION],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Location(s)', 'em-popprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADADDLOCATION:
                // Use the label as placeholder
                $this->setProp($component, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Name or Address', 'em-popprocessors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



