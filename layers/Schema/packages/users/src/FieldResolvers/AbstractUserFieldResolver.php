<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

abstract class AbstractUserFieldResolver extends AbstractQueryableFieldResolver
{
    function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        protected UserTypeAPIInterface $userTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
        );
    }
    
    public function getFieldNamesToResolve(): array
    {
        return [
            'users',
            'userCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'users' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'userCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'users',
            'userCount',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'users' => $this->translationAPI->__('Users', 'pop-users'),
            'userCount' => $this->translationAPI->__('Number of users', 'pop-users'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'users':
            case 'userCount':
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'users':
            case 'userCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDefaultFilterDataloadingModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        switch ($fieldName) {
            case 'userCount':
                return [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'users':
                $query = [
                    'limit' => ComponentConfiguration::getUserListDefaultLimit(),
                ];
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $this->userTypeAPI->getUsers($query, $options);
            case 'userCount':
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $this->userTypeAPI->getUserCount([], $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'users':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
