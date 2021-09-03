<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\CustomPosts\Types\Status;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_CUSTOMPOSTSTATUS = 'filterinput-custompoststatus';
    public const MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES = 'filterinput-unioncustomposttypes';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            [self::class, self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOSTSTATUS],
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS:
            case self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }
    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS:
            case self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => 'status',
                    self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => 'customPostTypes',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => SchemaDefinition::TYPE_ENUM,
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => SchemaDefinition::TYPE_STRING,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => true,
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => true,
            default => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => true,
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDefaultValue(array $module): mixed
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => [
                Status::PUBLISHED,
            ],
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => CustomPostUnionTypeHelpers::getTargetObjectTypeResolverCustomPostTypes(
                CustomPostUnionTypeResolver::class
            ),
            default => null,
        };
    }

    public function addSchemaDefinitionForFilter(array &$schemaDefinition, array $module): void
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS:
                /**
                 * @var CustomPostStatusEnum
                 */
                $customPostStatusEnum = $this->instanceManager->getInstance(CustomPostStatusEnum::class);
                $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_NAME] = $customPostStatusEnum->getName();
                $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $customPostStatusEnum->getValues()
                );
                break;
        }
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $descriptions = [
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => $this->translationAPI->__('Custom Post Status', 'customposts'),
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => $this->translationAPI->__('Return results from Union of the Custom Post Types', 'customposts'),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}
