<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserRoles\FilterInputProcessors\FilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const MODULE_FILTERINPUT_USER_ROLES = 'filterinput-user-roles';
    public final const MODULE_FILTERINPUT_EXCLUDE_USER_ROLES = 'filterinput-exclude-user-roles';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_USER_ROLES],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_USER_ROLES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_USER_ROLES],
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_USER_ROLES],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    public function getName(array $componentVariation): string
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES => 'roles',
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => 'excludeRoles',
            default => parent::getName($componentVariation),
        };
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => $this->getStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES,
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_USER_ROLES => $this->__('Get the users with given roles', 'user-roles'),
            self::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES => $this->__('Get the users without the given roles', 'user-roles'),
            default => null,
        };
    }
}
