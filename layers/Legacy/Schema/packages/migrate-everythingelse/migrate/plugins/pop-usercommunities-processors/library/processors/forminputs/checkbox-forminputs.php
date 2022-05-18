<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserCommunities_Module_Processor_CheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public final const MODULE_FILTERINPUT_FILTERBYCOMMUNITY = 'filterinput-filterbycommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Include members?', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    protected function getCommunitiesInput()
    {
        return [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST];
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                return 'id';
        }
        
        return parent::getDbobjectField($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getProcessor($input)->getName($input);
        }
        
        return parent::getName($component);
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getProcessor($input)->isMultiple($input);
        }
        
        return parent::isMultiple($component);
    }
}



