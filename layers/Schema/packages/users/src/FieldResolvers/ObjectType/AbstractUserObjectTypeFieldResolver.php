<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\ModuleProcessors\UserFilterInputContainerModuleProcessor;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    protected UserTypeAPIInterface $userTypeAPI;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected UserObjectTypeResolver $userObjectTypeResolver;

    #[Required]
    final public function autowireAbstractUserObjectTypeFieldResolver(
        UserTypeAPIInterface $userTypeAPI,
        IntScalarTypeResolver $intScalarTypeResolver,
        UserObjectTypeResolver $userObjectTypeResolver,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'users',
            'userCount',
            'usersForAdmin',
            'userCountForAdmin',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'usersForAdmin',
            'userCountForAdmin',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'users',
            'usersForAdmin'
                => $this->userObjectTypeResolver,
            'userCount',
            'userCountForAdmin'
                => $this->intScalarTypeResolver,
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'userCount',
            'userCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'users',
            'usersForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'users' => $this->translationAPI->__('Users', 'pop-users'),
            'userCount' => $this->translationAPI->__('Number of users', 'pop-users'),
            'usersForAdmin' => $this->translationAPI->__('[Unrestricted] Users', 'pop-users'),
            'userCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of users', 'pop-users'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'users' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_USERS],
            'userCount' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_USERCOUNT],
            'usersForAdmin' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERS],
            'userCountForAdmin' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        switch ($fieldName) {
            case 'users':
            case 'usersForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                if ($fieldArgName === $limitFilterInputName) {
                    return ComponentConfiguration::getUserListDefaultLimit();
                }
                break;
        }
        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'users':
            case 'usersForAdmin':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getUserListMaxLimit(),
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors[] = $maybeError;
                }
                break;
        }
        return $errors;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'users':
            case 'usersForAdmin':
                return $this->userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'userCount':
            case 'userCountForAdmin':
                return $this->userTypeAPI->getUserCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
