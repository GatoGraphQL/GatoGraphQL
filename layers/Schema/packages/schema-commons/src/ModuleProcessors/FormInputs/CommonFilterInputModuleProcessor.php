<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ModuleProcessors\FormInputs;

use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\SchemaCommons\FormInputs\MultiValueFromStringFormInput;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;

class CommonFilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_ORDER = 'filterinput-order';
    public const MODULE_FILTERINPUT_LIMIT = 'filterinput-limit';
    public const MODULE_FILTERINPUT_OFFSET = 'filterinput-offset';
    public const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';
    public const MODULE_FILTERINPUT_IDS = 'filterinput-ids';
    public const MODULE_FILTERINPUT_ID = 'filterinput-id';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_ORDER],
            [self::class, self::MODULE_FILTERINPUT_LIMIT],
            [self::class, self::MODULE_FILTERINPUT_OFFSET],
            [self::class, self::MODULE_FILTERINPUT_SEARCH],
            [self::class, self::MODULE_FILTERINPUT_IDS],
            [self::class, self::MODULE_FILTERINPUT_ID],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_ORDER => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_LIMIT => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_LIMIT],
            self::MODULE_FILTERINPUT_OFFSET => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_OFFSET],
            self::MODULE_FILTERINPUT_SEARCH => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SEARCH],
            self::MODULE_FILTERINPUT_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_INCLUDE],
            self::MODULE_FILTERINPUT_ID => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_INCLUDE],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDER:
                return OrderFormInput::class;
            case self::MODULE_FILTERINPUT_IDS:
                return FormMultipleInput::class;
            case self::MODULE_FILTERINPUT_ID:
                return MultiValueFromStringFormInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module)
    {
        // Add a nice name, so that the URL params when filtering make sense
        $names = array(
            self::MODULE_FILTERINPUT_ORDER => 'order',
            self::MODULE_FILTERINPUT_LIMIT => 'limit',
            self::MODULE_FILTERINPUT_OFFSET => 'offset',
            self::MODULE_FILTERINPUT_SEARCH => 'searchfor',
            self::MODULE_FILTERINPUT_IDS => 'ids',
            self::MODULE_FILTERINPUT_ID => 'id',
        );
        return $names[$module[1]] ?? parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_ORDER => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_LIMIT => SchemaDefinition::TYPE_INT,
            self::MODULE_FILTERINPUT_OFFSET => SchemaDefinition::TYPE_INT,
            self::MODULE_FILTERINPUT_SEARCH => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_IDS => SchemaDefinition::TYPE_ID,
            self::MODULE_FILTERINPUT_ID => SchemaDefinition::TYPE_ID,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_IDS => true,
            default => false,
        };
    }

    public function isSchemaFilterInputNonEmptyArrayType(array $module): bool
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_IDS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $descriptions = [
            self::MODULE_FILTERINPUT_ORDER => $this->translationAPI->__('Order the results. Specify the \'orderby\' and \'order\' (\'ASC\' or \'DESC\') fields in this format: \'orderby|order\'', 'pop-engine'),
            self::MODULE_FILTERINPUT_LIMIT => $this->translationAPI->__('Limit the results. \'-1\' brings all the results (or the maximum amount allowed)', 'pop-engine'),
            self::MODULE_FILTERINPUT_OFFSET => $this->translationAPI->__('Offset the results by how many places (required for pagination)', 'pop-engine'),
            self::MODULE_FILTERINPUT_SEARCH => $this->translationAPI->__('Search for elements containing the given string', 'pop-engine'),
            self::MODULE_FILTERINPUT_IDS => sprintf(
                $this->translationAPI->__('Limit results to elements with the given IDs', 'pop-engine'),
                Param::VALUE_SEPARATOR
            ),
            self::MODULE_FILTERINPUT_ID => sprintf(
                $this->translationAPI->__('Limit results to elements with the given ID, or IDs (separated by \'%s\')', 'pop-engine'),
                Param::VALUE_SEPARATOR
            ),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



