<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\EmbeddableFields;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\FieldResolvers\ObjectType\OperatorGlobalObjectTypeFieldResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * When Embeddable Fields is enabled, register the `echoStr` field
 */
class EchoOperatorGlobalObjectTypeFieldResolver extends OperatorGlobalObjectTypeFieldResolver
{
    use EmbeddableFieldsObjectTypeFieldResolverTrait;

    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    final public function autowireEchoOperatorGlobalObjectTypeFieldResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    /**
     * By making it not global, it gets registered on each single type.
     * Otherwise, it is not exposed in the schema
     */
    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return false;
    }

    // /**
    //  * Higher priority => Process before the global fieldResolver,
    //  * so this one gets registered (otherwise, since `SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA`
    //  * is false, the field would be removed)
    //  */
    // public function getPriorityToAttachToClasses(): int
    // {
    //     return 20;
    // }

    /**
     * Only the `echo` field is to be exposed
     *
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'echoStr',
        ];
    }

    /**
     * Change the type from mixed to string
     */
    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'echoStr' => $this->stringScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    /**
     * Change the type from mixed to string
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'echoStr' => [
                'value' => $this->stringScalarTypeResolver,
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['echoStr' => 'value'] => $this->translationAPI->__('The input string to be echoed back', 'graphql-api'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['echoStr' => 'value'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Change the type from mixed to string
     */
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'echoStr' => $this->translationAPI->__('Repeat back the input string', 'graphql-api'),
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
        switch ($fieldName) {
            case 'echoStr':
                return $fieldArgs['value'];
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
