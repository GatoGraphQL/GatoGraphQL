<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\ModuleProcessors\FormInputs;

use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\UserRoles\FilterInputProcessors\FilterInputProcessor;
use Symfony\Contracts\Service\Attribute\Required;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_USER_ROLES = 'filterinput-user-roles';
    public const MODULE_FILTERINPUT_EXCLUDE_USER_ROLES = 'filterinput-exclude-user-roles';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    //#[Required]
    final public function autowireFilterInputModuleProcessor(
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_USER_ROLES],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_USER_ROLES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_USER_ROLES],
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_USER_ROLES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES => 'roles',
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => 'excludeRoles',
            default => parent::getName($module),
        };
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => $this->getStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES,
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES => $this->translationAPI->__('Get the users with given roles', 'user-roles'),
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => $this->translationAPI->__('Get the users without the given roles', 'user-roles'),
            default => null,
        };
    }
}
