<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_MultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const COMPONENT_URE_FORMINPUT_INDIVIDUALINTERESTS = 'forminput-individualinterests';
    public final const COMPONENT_URE_FORMINPUT_ORGANIZATIONCATEGORIES = 'forminput-organizationcategories';
    public final const COMPONENT_URE_FORMINPUT_ORGANIZATIONTYPES = 'forminput-organizationtypes';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_FORMINPUT_INDIVIDUALINTERESTS,
            self::COMPONENT_URE_FORMINPUT_ORGANIZATIONCATEGORIES,
            self::COMPONENT_URE_FORMINPUT_ORGANIZATIONTYPES,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_INDIVIDUALINTERESTS:
                return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');
                
            case self::COMPONENT_URE_FORMINPUT_ORGANIZATIONCATEGORIES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:categories',
                    TranslationAPIFacade::getInstance()->__('Organization Categories', 'poptheme-wassup')
                );
                
            case self::COMPONENT_URE_FORMINPUT_ORGANIZATIONTYPES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:types',
                    TranslationAPIFacade::getInstance()->__('Organization Types', 'poptheme-wassup')
                );
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_INDIVIDUALINTERESTS:
                return GD_FormInput_IndividualInterests::class;
                
            case self::COMPONENT_URE_FORMINPUT_ORGANIZATIONCATEGORIES:
                return GD_FormInput_OrganizationCategories::class;
                
            case self::COMPONENT_URE_FORMINPUT_ORGANIZATIONTYPES:
                return GD_FormInput_OrganizationTypes::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_ORGANIZATIONTYPES:
                return 'organizationtypes';

            case self::COMPONENT_URE_FORMINPUT_ORGANIZATIONCATEGORIES:
                return 'organizationcategories';

            case self::COMPONENT_URE_FORMINPUT_INDIVIDUALINTERESTS:
                return 'individualinterests';
        }
        
        return parent::getDbobjectField($component);
    }
}



