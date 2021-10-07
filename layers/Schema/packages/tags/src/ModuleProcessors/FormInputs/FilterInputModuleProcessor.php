<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Tags\FilterInputProcessors\FilterInputProcessor;
use Symfony\Contracts\Service\Attribute\Required;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';
    public const MODULE_FILTERINPUT_TAG_IDS = 'filterinput-tag-ids';

    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    final public function autowireFilterInputModuleProcessor(
        IDScalarTypeResolver $idScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_TAG_SLUGS],
            [self::class, self::MODULE_FILTERINPUT_TAG_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_TAG_SLUGS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_SLUGS],
            self::MODULE_FILTERINPUT_TAG_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_TAG_SLUGS:
            case self::MODULE_FILTERINPUT_TAG_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => 'tagSlugs',
            self::MODULE_FILTERINPUT_TAG_IDS => 'tagIDs',
            default => parent::getName($module),
        };
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_TAG_IDS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS,
            self::MODULE_FILTERINPUT_TAG_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => $this->translationAPI->__('Limit results to elements with the given tags', 'tags'),
            self::MODULE_FILTERINPUT_TAG_IDS => $this->translationAPI->__('Limit results to elements with the given ids', 'tags'),
            default => null,
        };
    }
}
