<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\SchemaHooks;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserRoles\FilterInputProcessors\ExcludeUserRolesFilterInputProcessor;
use PoPCMSSchema\UserRoles\FilterInputProcessors\UserRolesFilterInputProcessor;
use PoPCMSSchema\UserRoles\Module;
use PoPCMSSchema\UserRoles\ModuleConfiguration;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\AbstractUsersFilterInputObjectTypeResolver;

class InputObjectTypeHookSet extends AbstractHookSet
{
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
            HookNames::ADMIN_INPUT_FIELD_NAMES,
            $this->getAdminInputFieldNames(...),
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
     * @param array<string, InputTypeResolverInterface> $inputFieldNameTypeResolvers
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
     * @param string[] $adminInputFieldNames
     * @return string[]
     */
    public function getAdminInputFieldNames(
        array $adminInputFieldNames,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $adminInputFieldNames;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserRoleAsAdminData()) {
            $adminInputFieldNames[] = 'roles';
            $adminInputFieldNames[] = 'excludeRoles';
        }
        return $adminInputFieldNames;
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
        ?FilterInputProcessorInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputProcessorInterface {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'roles' => $this->getUserRolesFilterInputProcessor(),
            'excludeRoles' => $this->getExcludeUserRolesFilterInputProcessor(),
            default => $inputFieldFilterInput,
        };
    }
}
