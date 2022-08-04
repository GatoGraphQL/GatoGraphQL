<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeSerialization;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface LeafOutputTypeSerializationServiceInterface
{
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $idFieldValues
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int,SplObjectStorage<FieldInterface,mixed>>
     */
    public function serializeLeafOutputTypeIDFieldValues(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldValues,
        array $idFieldSet,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;    
}
