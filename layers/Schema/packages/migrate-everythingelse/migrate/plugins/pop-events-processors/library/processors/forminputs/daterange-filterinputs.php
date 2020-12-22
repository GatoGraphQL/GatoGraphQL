<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;

class PoP_Events_Module_Processor_DateRangeComponentFilterInputs extends PoP_Module_Processor_DateRangeFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
    
    public const MODULE_FILTERINPUT_EVENTSCOPE = 'filterinput-eventscope';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_EVENTSCOPE],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_EVENTSCOPE => [PoP_Events_Module_Processor_FilterInputProcessor::class, PoP_Events_Module_Processor_FilterInputPrDATEsor::FILTERINPUT_EVENTSCOPE],
        ];
        return $filterInputs[$module[1]];
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_EVENTSCOPE:
    //             return true;
    //     }
        
    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_EVENTSCOPE:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_EVENTSCOPE:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_EVENTSCOPE => 'scope',
                );
                return $names[$module[1]];
        }
        
        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_FILTERINPUT_EVENTSCOPE => SchemaDefinition::TYPE_DATE,
        ];
        return $types[$module[1]];
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_EVENTSCOPE => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]];
    }
}



