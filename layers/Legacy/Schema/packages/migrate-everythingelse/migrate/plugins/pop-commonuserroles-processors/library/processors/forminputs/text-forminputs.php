<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_URE_FORMINPUT_CUP_CONTACTPERSON = 'forminput-ure-cup-contactperson';
    public final const MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER = 'forminput-ure-cup-contactnumber';
    public final const MODULE_URE_FORMINPUT_CUP_LASTNAME = 'forminput-ure-cup-lastName';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_CUP_LASTNAME],
            [self::class, self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON],
            [self::class, self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_LASTNAME:
                return TranslationAPIFacade::getInstance()->__('Last name', 'ure-popprocessors');

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON:
                return TranslationAPIFacade::getInstance()->__('Contact person', 'ure-popprocessors');

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return TranslationAPIFacade::getInstance()->__('Telephone / Fax', 'ure-popprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        $ret = parent::getLabel($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON:
                return '<i class="fa fa-fw fa-user"></i>'.$ret;

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return '<i class="fa fa-fw fa-phone"></i>'.$ret;
        }
        
        return $ret;
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_LASTNAME:
                return 'lastName';

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON:
                return 'contactPerson';

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return 'contactNumber';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



