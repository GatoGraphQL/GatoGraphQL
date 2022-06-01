<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserRoles\FilterInputs\ExcludeUserRolesFilterInput;
use PoPCMSSchema\UserRoles\FilterInputs\UserRolesFilterInput;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_USER_ROLES = 'filterinput-user-roles';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES = 'filterinput-exclude-user-roles';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?UserRolesFilterInput $userRolesFilterInput = null;
    private ?ExcludeUserRolesFilterInput $excludeUserRolesFilterInput = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setUserRolesFilterInput(UserRolesFilterInput $userRolesFilterInput): void
    {
        $this->userRolesFilterInput = $userRolesFilterInput;
    }
    final protected function getUserRolesFilterInput(): UserRolesFilterInput
    {
        return $this->userRolesFilterInput ??= $this->instanceManager->getInstance(UserRolesFilterInput::class);
    }
    final public function setExcludeUserRolesFilterInput(ExcludeUserRolesFilterInput $excludeUserRolesFilterInput): void
    {
        $this->excludeUserRolesFilterInput = $excludeUserRolesFilterInput;
    }
    final protected function getExcludeUserRolesFilterInput(): ExcludeUserRolesFilterInput
    {
        return $this->excludeUserRolesFilterInput ??= $this->instanceManager->getInstance(ExcludeUserRolesFilterInput::class);
    }

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_USER_ROLES,
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_USER_ROLES => $this->getUserRolesFilterInput(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => $this->getExcludeUserRolesFilterInput(),
            default => null,
        };
    }

    public function getName(Component $component): string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_USER_ROLES => 'roles',
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => 'excludeRoles',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_USER_ROLES => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => $this->getStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_USER_ROLES,
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_USER_ROLES => $this->__('Get the users with given roles', 'user-roles'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => $this->__('Get the users without the given roles', 'user-roles'),
            default => null,
        };
    }
}
