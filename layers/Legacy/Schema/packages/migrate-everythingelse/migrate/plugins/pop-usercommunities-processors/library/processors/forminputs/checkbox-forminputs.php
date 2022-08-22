<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserCommunities_Module_Processor_CheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public final const COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY = 'filterinput-filterbycommunity';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Include members?', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    protected function getCommunitiesInput()
    {
        return [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST];
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                return 'id';
        }
        
        return parent::getDbobjectField($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getComponentProcessor($input)->getName($input);
        }
        
        return parent::getName($component);
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getComponentProcessor($input)->isMultiple($input);
        }
        
        return parent::isMultiple($component);
    }
}



