<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const COMPONENT_URE_FORMINPUT_MEMBERSTATUS = 'ure-forminput-memberstatus';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUT_MEMBERSTATUS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_MEMBERSTATUS:
                return TranslationAPIFacade::getInstance()->__('Status', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_MEMBERSTATUS:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_MEMBERSTATUS:
                return GD_URE_FormInput_MemberStatus::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_MEMBERSTATUS:
                return 'memberstatus';
        }
        
        return parent::getDbobjectField($component);
    }
}



