<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_URE_FORMINPUT_CUP_CONTACTPERSON = 'forminput-ure-cup-contactperson';
    public final const MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER = 'forminput-ure-cup-contactnumber';
    public final const MODULE_URE_FORMINPUT_CUP_LASTNAME = 'forminput-ure-cup-lastName';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUT_CUP_LASTNAME],
            [self::class, self::COMPONENT_URE_FORMINPUT_CUP_CONTACTPERSON],
            [self::class, self::COMPONENT_URE_FORMINPUT_CUP_CONTACTNUMBER],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_CUP_LASTNAME:
                return TranslationAPIFacade::getInstance()->__('Last name', 'ure-popprocessors');

            case self::COMPONENT_URE_FORMINPUT_CUP_CONTACTPERSON:
                return TranslationAPIFacade::getInstance()->__('Contact person', 'ure-popprocessors');

            case self::COMPONENT_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return TranslationAPIFacade::getInstance()->__('Telephone / Fax', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        $ret = parent::getLabel($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_CUP_CONTACTPERSON:
                return '<i class="fa fa-fw fa-user"></i>'.$ret;

            case self::COMPONENT_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return '<i class="fa fa-fw fa-phone"></i>'.$ret;
        }
        
        return $ret;
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_CUP_LASTNAME:
                return 'lastName';

            case self::COMPONENT_URE_FORMINPUT_CUP_CONTACTPERSON:
                return 'contactPerson';

            case self::COMPONENT_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return 'contactNumber';
        }
        
        return parent::getDbobjectField($component);
    }
}



