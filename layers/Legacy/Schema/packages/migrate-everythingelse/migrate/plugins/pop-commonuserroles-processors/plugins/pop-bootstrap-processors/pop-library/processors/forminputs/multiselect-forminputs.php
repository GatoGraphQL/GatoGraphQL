<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_MultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_URE_FORMINPUT_INDIVIDUALINTERESTS = 'forminput-individualinterests';
    public final const MODULE_URE_FORMINPUT_ORGANIZATIONCATEGORIES = 'forminput-organizationcategories';
    public final const MODULE_URE_FORMINPUT_ORGANIZATIONTYPES = 'forminput-organizationtypes';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_INDIVIDUALINTERESTS],
            [self::class, self::MODULE_URE_FORMINPUT_ORGANIZATIONCATEGORIES],
            [self::class, self::MODULE_URE_FORMINPUT_ORGANIZATIONTYPES],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_INDIVIDUALINTERESTS:
                return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');
                
            case self::MODULE_URE_FORMINPUT_ORGANIZATIONCATEGORIES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:categories',
                    TranslationAPIFacade::getInstance()->__('Organization Categories', 'poptheme-wassup')
                );
                
            case self::MODULE_URE_FORMINPUT_ORGANIZATIONTYPES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:types',
                    TranslationAPIFacade::getInstance()->__('Organization Types', 'poptheme-wassup')
                );
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_INDIVIDUALINTERESTS:
                return GD_FormInput_IndividualInterests::class;
                
            case self::MODULE_URE_FORMINPUT_ORGANIZATIONCATEGORIES:
                return GD_FormInput_OrganizationCategories::class;
                
            case self::MODULE_URE_FORMINPUT_ORGANIZATIONTYPES:
                return GD_FormInput_OrganizationTypes::class;
        }
        
        return parent::getInputClass($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_ORGANIZATIONTYPES:
                return 'organizationtypes';

            case self::MODULE_URE_FORMINPUT_ORGANIZATIONCATEGORIES:
                return 'organizationcategories';

            case self::MODULE_URE_FORMINPUT_INDIVIDUALINTERESTS:
                return 'individualinterests';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



