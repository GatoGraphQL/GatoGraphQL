<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserRoles\FilterInputProcessors\ExcludeUserRolesFilterInputProcessor;
use PoPCMSSchema\UserRoles\FilterInputProcessors\UserRolesFilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_USER_ROLES = 'filterinput-user-roles';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES = 'filterinput-exclude-user-roles';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?UserRolesFilterInputProcessor $userRolesFilterInputProcessor = null;
    private ?ExcludeUserRolesFilterInputProcessor $excludeUserRolesFilterInputProcessor = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setUserRolesFilterInputProcessor(UserRolesFilterInputProcessor $userRolesFilterInputProcessor): void
    {
        $this->userRolesFilterInputProcessor = $userRolesFilterInputProcessor;
    }
    final protected function getUserRolesFilterInputProcessor(): UserRolesFilterInputProcessor
    {
        return $this->userRolesFilterInputProcessor ??= $this->instanceManager->getInstance(UserRolesFilterInputProcessor::class);
    }
    final public function setExcludeUserRolesFilterInputProcessor(ExcludeUserRolesFilterInputProcessor $excludeUserRolesFilterInputProcessor): void
    {
        $this->excludeUserRolesFilterInputProcessor = $excludeUserRolesFilterInputProcessor;
    }
    final protected function getExcludeUserRolesFilterInputProcessor(): ExcludeUserRolesFilterInputProcessor
    {
        return $this->excludeUserRolesFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeUserRolesFilterInputProcessor::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_USER_ROLES],
            [self::class, self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES],
        );
    }

    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_USER_ROLES => $this->getUserRolesFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => $this->getExcludeUserRolesFilterInputProcessor(),
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getName(array $component): string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_USER_ROLES => 'roles',
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => 'excludeRoles',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_USER_ROLES => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => $this->getStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_USER_ROLES,
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_USER_ROLES => $this->__('Get the users with given roles', 'user-roles'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES => $this->__('Get the users without the given roles', 'user-roles'),
            default => null,
        };
    }
}
