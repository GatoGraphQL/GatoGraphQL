<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserCommunities_Module_Processor_CheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public final const MODULE_FILTERINPUT_FILTERBYCOMMUNITY = 'filterinput-filterbycommunity';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Include members?', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    protected function getCommunitiesInput()
    {
        return [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST];
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                return 'id';
        }
        
        return parent::getDbobjectField($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getProcessor($input)->getName($input);
        }
        
        return parent::getName($module);
    }

    public function isMultiple(array $module): bool
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_FILTERBYCOMMUNITY:
                // By having the same name/multiple, that other forminput will get its value
                $input = $this->getCommunitiesInput();
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getProcessor($input)->isMultiple($input);
        }
        
        return parent::isMultiple($module);
    }
}



