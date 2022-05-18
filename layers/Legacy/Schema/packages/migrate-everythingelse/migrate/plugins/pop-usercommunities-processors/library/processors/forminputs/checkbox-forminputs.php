<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserCommunities_Module_Processor_CheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public final const MODULE_FILTERINPUT_FILTERBYCOMMUNITY = 'filterinput-filterbycommunity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Include members?', 'pop-genericforms');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($componentVariation);
    // }

    protected function getCommunitiesInput()
    {
        return [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST];
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                return 'id';
        }
        
        return parent::getDbobjectField($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getProcessor($input)->getName($input);
        }
        
        return parent::getName($componentVariation);
    }

    public function isMultiple(array $componentVariation): bool
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getProcessor($input)->isMultiple($input);
        }
        
        return parent::isMultiple($componentVariation);
    }
}



