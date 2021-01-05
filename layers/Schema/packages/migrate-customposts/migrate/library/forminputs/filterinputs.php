<?php
use PoP\ComponentModel\PoP_InputUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;

class PoP_CustomPosts_Module_Processor_FilterInputs extends \PoP\ComponentModel\AbstractFormInputs implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';
    public const MODULE_FILTERINPUT_CUSTOMPOSTDATES = 'filterinput-custompostdates';
    public const MODULE_FILTERINPUT_GENERICPOSTTYPES = 'filterinput-customposttypes';
    public const MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES = 'filterinput-unioncustomposttypes';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_SEARCH],
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
            [self::class, self::MODULE_FILTERINPUT_GENERICPOSTTYPES],
            [self::class, self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_SEARCH => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_SEARCH],
            self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => [\PoPSchema\CustomPosts\FilterInputProcessor::class, \PoPSchema\CustomPosts\FilterInputProcessor::FILTERINPUT_CUSTOMPOSTDATES],
            self::MODULE_FILTERINPUT_GENERICPOSTTYPES => [\PoPSchema\CustomPosts\FilterInputProcessor::class, \PoPSchema\CustomPosts\FilterInputProcessor::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => [\PoPSchema\CustomPosts\FilterInputProcessor::class, \PoPSchema\CustomPosts\FilterInputProcessor::FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                // Allow for multiple names, for multiple inputs
                $name = $this->getName($module);
                $names = array();
                foreach ($this->getInputSubnames($module) as $subname) {
                    $names[$subname] = PoP_InputUtils::getMultipleinputsName($name, $subname).($this->isMultiple($module) ? '[]' : '');
                }
                return $names;
        }

        return parent::getInputName($module);
    }

    public function getInputOptions(array $module)
    {
        $options = parent::getInputOptions($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                $options['subnames'] = ['from', 'to'];
                break;
        }

        return $options;
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                return \PoP\Engine\GD_FormInput_MultipleInputs::class;
            case self::MODULE_FILTERINPUT_GENERICPOSTTYPES:
            case self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return \PoP\ComponentModel\GD_FormInput_MultiInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_SEARCH:
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
            case self::MODULE_FILTERINPUT_GENERICPOSTTYPES:
            case self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_SEARCH => 'searchfor',
                    self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => 'date',
                    self::MODULE_FILTERINPUT_GENERICPOSTTYPES => 'customPostTypes',
                    self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => 'customPostTypes',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_FILTERINPUT_SEARCH => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => SchemaDefinition::TYPE_DATE,
            self::MODULE_FILTERINPUT_GENERICPOSTTYPES => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$module[1]] ?? null;
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                return sprintf(
                    $translationAPI->__('Search for posts between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\'', 'pop-posts'),
                    \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subnames[0]),
                    \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subnames[1])
                );
        }

        $descriptions = [
            self::MODULE_FILTERINPUT_SEARCH => $translationAPI->__('Search for posts containing the given string', 'pop-posts'),
            self::MODULE_FILTERINPUT_GENERICPOSTTYPES => $translationAPI->__('Return results from Custom Post Types', 'pop-posts'),
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => $translationAPI->__('Return results from Union of the Custom Post Types', 'pop-posts'),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



