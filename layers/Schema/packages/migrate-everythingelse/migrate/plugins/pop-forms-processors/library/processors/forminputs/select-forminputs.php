<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;

class PoP_Module_Processor_SelectFilterInputs extends PoP_Module_Processor_SelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
    
    public const MODULE_FILTERINPUT_ORDERUSER = 'filterinput-order-user';
    public const MODULE_FILTERINPUT_ORDERPOST = 'filterinput-order-post';
    public const MODULE_FILTERINPUT_ORDERTAG = 'filterinput-order-tag';
    public const MODULE_FILTERINPUT_ORDERCOMMENT = 'filterinput-order-comment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_ORDERUSER],
            [self::class, self::MODULE_FILTERINPUT_ORDERPOST],
            [self::class, self::MODULE_FILTERINPUT_ORDERTAG],
            [self::class, self::MODULE_FILTERINPUT_ORDERCOMMENT],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_ORDERUSER => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_ORDERPOST => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_ORDERTAG => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_ORDERCOMMENT => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_ORDER],
        ];
        return $filterInputs[$module[1]];
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_ORDERUSER:
    //         case self::MODULE_FILTERINPUT_ORDERPOST:
    //         case self::MODULE_FILTERINPUT_ORDERTAG:
    //             return true;
    //     }
        
    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDERUSER:
            case self::MODULE_FILTERINPUT_ORDERPOST:
            case self::MODULE_FILTERINPUT_ORDERTAG:
            case self::MODULE_FILTERINPUT_ORDERCOMMENT:
                return TranslationAPIFacade::getInstance()->__('Order by', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDERUSER:
                return GD_FormInput_OrderUser::class;

            case self::MODULE_FILTERINPUT_ORDERPOST:
                return GD_FormInput_OrderPost::class;

            case self::MODULE_FILTERINPUT_ORDERTAG:
                return GD_FormInput_OrderTag::class;

            case self::MODULE_FILTERINPUT_ORDERCOMMENT:
                return GD_FormInput_OrderComment::class;
        }
        
        return parent::getInputClass($module);
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDERUSER:
            case self::MODULE_FILTERINPUT_ORDERPOST:
            case self::MODULE_FILTERINPUT_ORDERTAG:
            case self::MODULE_FILTERINPUT_ORDERCOMMENT:
                // Add a nice name, so that the URL params when filtering make sense
                return 'order';
        }
        
        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_FILTERINPUT_ORDERUSER => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_ORDERPOST => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_ORDERTAG => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_ORDERCOMMENT => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$module[1]];
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_ORDERUSER => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_ORDERPOST => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_ORDERTAG => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_ORDERCOMMENT => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]];
    }
}



