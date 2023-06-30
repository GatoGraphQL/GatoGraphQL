<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserRoles\FilterInputs\ExcludeUserRolesFilterInput;
use PoPCMSSchema\UserRoles\FilterInputs\UserRolesFilterInput;
use PoPCMSSchema\UserRoles\Module;
use PoPCMSSchema\UserRoles\ModuleConfiguration;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\AbstractUsersFilterInputObjectTypeResolver;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?UserRolesFilterInput $userRolesFilterInput = null;
    private ?ExcludeUserRolesFilterInput $excludeUserRolesFilterInput = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setUserRolesFilterInput(UserRolesFilterInput $userRolesFilterInput): void
    {
        $this->userRolesFilterInput = $userRolesFilterInput;
    }
    final protected function getUserRolesFilterInput(): UserRolesFilterInput
    {
        if ($this->userRolesFilterInput === null) {
            /** @var UserRolesFilterInput */
            $userRolesFilterInput = $this->instanceManager->getInstance(UserRolesFilterInput::class);
            $this->userRolesFilterInput = $userRolesFilterInput;
        }
        return $this->userRolesFilterInput;
    }
    final public function setExcludeUserRolesFilterInput(ExcludeUserRolesFilterInput $excludeUserRolesFilterInput): void
    {
        $this->excludeUserRolesFilterInput = $excludeUserRolesFilterInput;
    }
    final protected function getExcludeUserRolesFilterInput(): ExcludeUserRolesFilterInput
    {
        if ($this->excludeUserRolesFilterInput === null) {
            /** @var ExcludeUserRolesFilterInput */
            $excludeUserRolesFilterInput = $this->instanceManager->getInstance(ExcludeUserRolesFilterInput::class);
            $this->excludeUserRolesFilterInput = $excludeUserRolesFilterInput;
        }
        return $this->excludeUserRolesFilterInput;
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->getInputFieldNameTypeResolvers(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->getInputFieldDescription(...),
            10,
            3
        );
        App::addFilter(
            HookNames::SENSITIVE_INPUT_FIELD_NAMES,
            $this->getSensitiveInputFieldNames(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            $this->getInputFieldTypeModifiers(...),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            $this->getInputFieldFilterInput(...),
            10,
            3
        );
    }

    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'roles' => $this->getStringScalarTypeResolver(),
                'excludeRoles' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    /**
     * @param string[] $sensitiveInputFieldNames
     * @return string[]
     */
    public function getSensitiveInputFieldNames(
        array $sensitiveInputFieldNames,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $sensitiveInputFieldNames;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserRoleAsSensitiveData()) {
            $sensitiveInputFieldNames[] = 'roles';
            $sensitiveInputFieldNames[] = 'excludeRoles';
        }
        return $sensitiveInputFieldNames;
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'roles' => $this->__('Filter users by role(s)', 'user-roles'),
            'excludeRoles' => $this->__('Filter users by excluding role(s)', 'user-roles'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldTypeModifiers(
        int $inputFieldTypeModifiers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): int {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        return match ($inputFieldName) {
            'roles',
            'excludeRoles'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => $inputFieldTypeModifiers,
        };
    }

    public function getInputFieldFilterInput(
        ?FilterInputInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputInterface {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'roles' => $this->getUserRolesFilterInput(),
            'excludeRoles' => $this->getExcludeUserRolesFilterInput(),
            default => $inputFieldFilterInput,
        };
    }
}
