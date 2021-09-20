<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class FieldObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        EngineInterface $engine,
        protected BooleanScalarTypeResolver $booleanScalarTypeResolver,
        protected StringScalarTypeResolver $stringScalarTypeResolver,
        protected ObjectScalarTypeResolver $objectScalarTypeResolver,
        protected InputValueObjectTypeResolver $inputValueObjectTypeResolver,
        protected TypeObjectTypeResolver $typeObjectTypeResolver,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
            $schemaDefinitionService,
            $engine,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            FieldObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'args',
            'type',
            'isDeprecated',
            'deprecationReason',
            'extensions',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->stringScalarTypeResolver,
            'description' => $this->stringScalarTypeResolver,
            'isDeprecated' => $this->booleanScalarTypeResolver,
            'deprecationReason' => $this->stringScalarTypeResolver,
            'extensions' => $this->objectScalarTypeResolver,
            'args' => $this->inputValueObjectTypeResolver,
            'type' => $this->typeObjectTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'name',
            'type',
            'isDeprecated',
            'extensions'
                => SchemaTypeModifiers::NON_NULLABLE,
            'args'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'name' => $this->translationAPI->__('Field\'s name', 'graphql-server'),
            'description' => $this->translationAPI->__('Field\'s description', 'graphql-server'),
            'args' => $this->translationAPI->__('Field arguments', 'graphql-server'),
            'type' => $this->translationAPI->__('Type to which the field belongs', 'graphql-server'),
            'isDeprecated' => $this->translationAPI->__('Is the field deprecated?', 'graphql-server'),
            'deprecationReason' => $this->translationAPI->__('Why was the field deprecated?', 'graphql-server'),
            'extensions' => $this->translationAPI->__('Custom metadata added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
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
        /** @var Field */
        $field = $object;
        switch ($fieldName) {
            case 'name':
                return $field->getName();
            case 'description':
                return $field->getDescription();
            case 'args':
                return $field->getArgIDs();
            case 'type':
                return $field->getTypeID();
            case 'isDeprecated':
                return $field->isDeprecated();
            case 'deprecationReason':
                return $field->getDeprecationDescription();
            case 'extensions':
                return $field->getExtensions();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
