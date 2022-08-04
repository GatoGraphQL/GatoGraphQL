<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeSerialization\LeafOutputTypeSerializationServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

final class SerializeLeafOutputTypeValuesDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    private ?LeafOutputTypeSerializationServiceInterface $leafOutputTypeSerializationService = null;

    final public function setLeafOutputTypeSerializationService(LeafOutputTypeSerializationServiceInterface $leafOutputTypeSerializationService): void
    {
        $this->leafOutputTypeSerializationService = $leafOutputTypeSerializationService;
    }
    final protected function getLeafOutputTypeSerializationService(): LeafOutputTypeSerializationServiceInterface
    {
        return $this->leafOutputTypeSerializationService ??= $this->instanceManager->getInstance(LeafOutputTypeSerializationServiceInterface::class);
    }

    public function getDirectiveName(): string
    {
        return 'serializeLeafOutputTypeValues';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SYSTEM;
    }

    /**
     * Execute it at the very end, after everything else,
     * to allow other directives to deal with the unserialized value
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_SERIALIZE;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $serializedIDFieldValues = $this->getLeafOutputTypeSerializationService()->serializeLeafOutputTypeIDFieldValues(
            $relationalTypeResolver,
            $resolvedIDFieldValues,
            $idFieldSet,
            $idObjects,
            $this->directive,
            $engineIterationFeedbackStore,
        );
        foreach ($serializedIDFieldValues as $id => $serializedFieldValues) {
            /** @var FieldInterface $field */
            foreach ($serializedFieldValues as $field) {
                $serializedValue = $serializedFieldValues[$field];
                $resolvedIDFieldValues[$id][$field] = $serializedValue;
            }
        }
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Serialize the results for fields of Scalar and Enum Types. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
