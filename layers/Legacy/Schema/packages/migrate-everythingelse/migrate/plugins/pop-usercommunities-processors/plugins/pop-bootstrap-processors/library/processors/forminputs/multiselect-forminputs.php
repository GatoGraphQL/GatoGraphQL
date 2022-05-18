<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ProfileMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_URE_FORMINPUT_MEMBERPRIVILEGES = 'ure-forminput-memberprivileges';
    public final const MODULE_URE_FORMINPUT_MEMBERTAGS = 'ure-forminput-membertags';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES],
            [self::class, self::COMPONENT_URE_FORMINPUT_MEMBERTAGS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES:
                return TranslationAPIFacade::getInstance()->__('Privileges', 'ure-popprocessors');

            case self::COMPONENT_URE_FORMINPUT_MEMBERTAGS:
                return TranslationAPIFacade::getInstance()->__('Tags', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES:
                return GD_URE_FormInput_MemberPrivileges::class;
            
            case self::COMPONENT_URE_FORMINPUT_MEMBERTAGS:
                return GD_URE_FormInput_MemberTags::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES:
                return 'memberprivileges';

            case self::COMPONENT_URE_FORMINPUT_MEMBERTAGS:
                return 'membertags';
        }
        
        return parent::getDbobjectField($component);
    }
}



