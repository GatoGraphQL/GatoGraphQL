<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ModuleProcessors\FormInputs;

use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\FormMultipleInputModuleProcessorTrait;
use PoP\ComponentModel\PoP_InputUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Engine\FilterInputProcessor;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

class CommonFilterMultipleInputs extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
    use FormMultipleInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_DATES = 'filterinput-dates';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_DATES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_DATES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_DATES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                // Allow for multiple names, for multiple inputs
                $name = $this->getName($module);
                $names = array();
                foreach ($this->getInputSubnames($module) as $subname) {
                    $names[$subname] = PoP_InputUtils::getMultipleinputsName($name, $subname) . ($this->isMultiple($module) ? '[]' : '');
                }
                return $names;
        }

        return parent::getInputName($module);
    }

    public function getInputOptions(array $module)
    {
        $options = parent::getInputOptions($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $options['subnames'] = ['from', 'to'];
                break;
        }

        return $options;
    }

    public function getName(array $module)
    {
        // Add a nice name, so that the URL params when filtering make sense
        $names = array(
            self::MODULE_FILTERINPUT_DATES => 'date',
        );
        return $names[$module[1]] ?? parent::getName($module);
    }

    protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module)
    {
        // Replace the "date" item with "date-from" and "date-to"
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $translationAPI = TranslationAPIFacade::getInstance();
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                $dateFormat = 'Y-m-d';
                // Save documentation as template, and remove it
                $schemaDefinition = $schemaDefinitionItems[0];
                unset($schemaDefinition[SchemaDefinition::ARGNAME_NAME]);
                unset($schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION]);
                array_shift($schemaDefinitionItems);
                // Add the other elements, using the original documantation as placeholder
                $schemaDefinitionItems[] = array_merge(
                    [
                        SchemaDefinition::ARGNAME_NAME => PoP_InputUtils::getMultipleinputsName($name, $subnames[0]),
                    ],
                    $schemaDefinition,
                    [
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $translationAPI->__('Search for elements starting from this date, in format \'%s\'', 'pop-engine'),
                            $dateFormat
                        ),
                    ]
                );
                $schemaDefinitionItems[] = array_merge(
                    [
                        SchemaDefinition::ARGNAME_NAME => PoP_InputUtils::getMultipleinputsName($name, $subnames[1]),
                    ],
                    $schemaDefinition,
                    [
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $translationAPI->__('Search for elements starting until this date, in format \'%s\'', 'pop-engine'),
                            $dateFormat
                        ),
                    ]
                );
                break;
        }
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_FILTERINPUT_DATES => SchemaDefinition::TYPE_DATE,
        ];
        return $types[$module[1]] ?? null;
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
                return sprintf(
                    $translationAPI->__('Search for elements between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\', in format \'%s\'', 'pop-engine'),
                    PoP_InputUtils::getMultipleinputsName($name, $subnames[0]),
                    PoP_InputUtils::getMultipleinputsName($name, $subnames[1]),
                    $cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat'))
                );
        }
        return null;
    }
}



