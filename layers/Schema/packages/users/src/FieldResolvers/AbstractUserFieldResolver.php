<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\ModuleProcessors\UserFilterInputContainerModuleProcessor;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\Users\TypeResolvers\Object\UserTypeResolver;

abstract class AbstractUserFieldResolver extends AbstractQueryableFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected UserTypeAPIInterface $userTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
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

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'userCount' => SchemaDefinition::TYPE_INT,
            'userCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'userCount',
            'userCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'users',
            'usersForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'users' => $this->translationAPI->__('Users', 'pop-users'),
            'userCount' => $this->translationAPI->__('Number of users', 'pop-users'),
            'usersForAdmin' => $this->translationAPI->__('[Unrestricted] Users', 'pop-users'),
            'userCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of users', 'pop-users'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'users' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_USERS],
            'userCount' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_USERCOUNT],
            'usersForAdmin' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERS],
            'userCountForAdmin' => [UserFilterInputContainerModuleProcessor::class, UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT],
            default => parent::getFieldDataFilteringModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'users':
            case 'usersForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getUserListDefaultLimit(),
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($objectTypeResolver, $fieldName);
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
        object $resultItem,
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

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'users':
            case 'usersForAdmin':
                return UserTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
