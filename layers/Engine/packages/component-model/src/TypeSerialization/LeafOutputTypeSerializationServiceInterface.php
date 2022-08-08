<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeSerialization;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface LeafOutputTypeSerializationServiceInterface
{
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $idFieldValues
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int,SplObjectStorage<FieldInterface,mixed>>
     */
    public function serializeOutputTypeIDFieldValues(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldValues,
        array $idFieldSet,
        array $idObjects,
        Directive $directive,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;

    /**
     * The response for Scalar Types and Enum types must be serialized.
     * The response type is the same as in the type's `serialize` method.
     */
    public function serializeLeafOutputTypeValue(
        mixed $value,
        LeafOutputTypeResolverInterface $fieldLeafOutputTypeResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): string|int|float|bool|array;
}
