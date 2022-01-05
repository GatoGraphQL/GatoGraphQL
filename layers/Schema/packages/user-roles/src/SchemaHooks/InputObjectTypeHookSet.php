<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\SchemaHooks;

use PoP\Engine\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\UserRoles\Component;
use PoPSchema\UserRoles\ComponentConfiguration;
use PoPSchema\UserRoles\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\Users\TypeResolvers\InputObjectType\AbstractUsersFilterInputObjectTypeResolver;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            [$this, 'getInputFieldNameTypeResolvers'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            [$this, 'getInputFieldDescription'],
            10,
            3
        );
        $this->getHooksAPI()->addFilter(
            HookNames::ADMIN_INPUT_FIELD_NAMES,
            [$this, 'getAdminInputFieldNames'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            [$this, 'getInputFieldTypeModifiers'],
            10,
            3
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            [$this, 'getInputFieldFilterInput'],
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->treatUserRoleAsAdminData()) {
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
            'roles' => $this->getTranslationAPI()->__('Filter users by role(s)', 'user-roles'),
            'excludeRoles' => $this->getTranslationAPI()->__('Filter users by excluding role(s)', 'user-roles'),
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
        ?array $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?array {
        if (!($inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'roles' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_USER_ROLES],
            'excludeRoles' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_USER_ROLES],
            default => $inputFieldFilterInput,
        };
    }
}
