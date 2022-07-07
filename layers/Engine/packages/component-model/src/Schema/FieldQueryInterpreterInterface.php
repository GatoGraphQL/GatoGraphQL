<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\FieldQuery\FieldQueryInterpreterInterface as UpstreamFieldQueryInterpreterInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface FieldQueryInterpreterInterface extends UpstreamFieldQueryInterpreterInterface
{
    /**
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     */
    public function extractDirectiveArgumentsForSchema(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
        SplObjectStorage $directiveFields,
        array $variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        bool $disableDynamicFields = false
    ): array;
    /**
     * @param FieldInterface[] $fields
     */
    public function extractDirectiveArgumentsForObject(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        array $fields,
        Directive $directive,
        array $variables,
        array $expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;
    public function maybeConvertFieldArgumentValue(mixed $fieldArgValue, ?array $variables = null): mixed;
    public function maybeConvertFieldArgumentArrayOrObjectValue(mixed $fieldArgValue, ?array $variables = null): mixed;
    public function resolveExpression(
        RelationalTypeResolverInterface $relationalTypeResolver,
        mixed $fieldArgValue,
        ?array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed;
}
