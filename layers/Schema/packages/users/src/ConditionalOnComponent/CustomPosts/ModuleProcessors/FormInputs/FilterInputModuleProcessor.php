<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\ModuleProcessors\FormInputs;

use PoP\ComponentModel\ModuleProcessors\AbstractFilterInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFilterInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    public const MODULE_FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public const MODULE_FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';
    public const MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS = 'filterinput-exclude-author-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_AUTHOR_IDS],
            [self::class, self::MODULE_FILTERINPUT_AUTHOR_SLUG],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_AUTHOR_IDS],
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_AUTHOR_SLUG],
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => 'authorIDs',
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => 'authorSlug',
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => 'excludeAuthorIDs',
            default => parent::getName($module),
        };
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => $this->__('Get results from the authors with given IDs', 'pop-users'),
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => $this->__('Get results from the authors with given slug', 'pop-users'),
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->__('Get results excluding the ones from authors with given IDs', 'pop-users'),
            default => null,
        };
    }
}
