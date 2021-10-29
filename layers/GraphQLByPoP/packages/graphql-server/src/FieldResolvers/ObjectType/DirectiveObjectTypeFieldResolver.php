<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveLocationEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class DirectiveObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?InputValueObjectTypeResolver $inputValueObjectTypeResolver = null;
    private ?DirectiveLocationEnumTypeResolver $directiveLocationEnumTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;

    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    public function setInputValueObjectTypeResolver(InputValueObjectTypeResolver $inputValueObjectTypeResolver): void
    {
        $this->inputValueObjectTypeResolver = $inputValueObjectTypeResolver;
    }
    protected function getInputValueObjectTypeResolver(): InputValueObjectTypeResolver
    {
        return $this->inputValueObjectTypeResolver ??= $this->instanceManager->getInstance(InputValueObjectTypeResolver::class);
    }
    public function setDirectiveLocationEnumTypeResolver(DirectiveLocationEnumTypeResolver $directiveLocationEnumTypeResolver): void
    {
        $this->directiveLocationEnumTypeResolver = $directiveLocationEnumTypeResolver;
    }
    protected function getDirectiveLocationEnumTypeResolver(): DirectiveLocationEnumTypeResolver
    {
        return $this->directiveLocationEnumTypeResolver ??= $this->instanceManager->getInstance(DirectiveLocationEnumTypeResolver::class);
    }
    public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        return $this->jsonObjectScalarTypeResolver ??= $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }

    //#[Required]
    final public function autowireDirectiveObjectTypeFieldResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        InputValueObjectTypeResolver $inputValueObjectTypeResolver,
        DirectiveLocationEnumTypeResolver $directiveLocationEnumTypeResolver,
        JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->inputValueObjectTypeResolver = $inputValueObjectTypeResolver;
        $this->directiveLocationEnumTypeResolver = $directiveLocationEnumTypeResolver;
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            DirectiveObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'args',
            'locations',
            'isRepeatable',
            'extensions',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->getStringScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'isRepeatable' => $this->getBooleanScalarTypeResolver(),
            'args' => $this->getInputValueObjectTypeResolver(),
            'locations' => $this->getDirectiveLocationEnumTypeResolver(),
            'extensions' => $this->getJsonObjectScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            'isRepeatable',
            'extensions'
                => SchemaTypeModifiers::NON_NULLABLE,
            'locations',
            'args'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->translationAPI->__('Directive\'s name', 'graphql-server'),
            'description' => $this->translationAPI->__('Directive\'s description', 'graphql-server'),
            'args' => $this->translationAPI->__('Directive\'s arguments', 'graphql-server'),
            'locations' => $this->translationAPI->__('The locations where the directive may be placed', 'graphql-server'),
            'isRepeatable' => $this->translationAPI->__('Can the directive be executed more than once in the same field?', 'graphql-server'),
            'extensions' => $this->translationAPI->__('Custom metadata added to the directive (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
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
        /** @var Directive */
        $directive = $object;
        switch ($fieldName) {
            case 'name':
                return $directive->getName();
            case 'description':
                return $directive->getDescription();
            case 'args':
                return $directive->getArgIDs();
            case 'locations':
                return $directive->getLocations();
            case 'isRepeatable':
                return $directive->isRepeatable();
            case 'extensions':
                return (object) $directive->getExtensions();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
