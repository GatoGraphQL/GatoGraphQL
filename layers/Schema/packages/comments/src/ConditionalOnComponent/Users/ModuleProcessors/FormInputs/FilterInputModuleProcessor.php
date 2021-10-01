<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\ModuleProcessors\FormInputs;

use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Comments\ConditionalOnComponent\Users\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS = 'filterinput-custompost-author-ids';
    public const MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS = 'filterinput-exclude-custompost-author-ids';

    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    public function autowireFilterInputModuleProcessor(
        IDScalarTypeResolver $idScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_AUTHOR_IDS],
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => 'customPostAuthorIDs',
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => 'excludeCustomPostAuthorIDs',
            default => parent::getName($module),
        };
    }

    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
                => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
                => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $descriptions = [
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->translationAPI->__('Get results from the authors with given IDs', 'pop-users'),
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->translationAPI->__('Get results from the ones from authors with given IDs', 'pop-users'),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}
