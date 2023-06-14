<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPSchema\DirectiveCommons\ObjectModels\TypedDataValidationPayload;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\FloatScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\NumericScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

abstract class AbstractTransformFloatFieldValueFieldDirectiveResolver extends AbstractTransformTypedFieldValueFieldDirectiveResolver
{
    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        return [
            FloatScalarTypeResolver::class,
            NumericScalarTypeResolver::class,
            AnyBuiltInScalarScalarTypeResolver::class,
        ];
    }

    protected function isMatchingType(mixed $value): bool
    {
        return is_float($value) || is_integer($value);
    }

    /**
     * @param float|int $value
     * @return mixed TypedDataValidationPayload if error, or the value otherwise
     */
    final protected function transformTypeValue(mixed $value): mixed
    {
        return $this->transformFloatValue((float) $value);
    }

    abstract protected function transformFloatValue(float $value): float|TypedDataValidationPayload;

    /**
     * Validate the value against the directive args
     *
     * @param float|int $value
     */
    final protected function validateTypeData(mixed $value): ?TypedDataValidationPayload
    {
        return $this->validateFloatData((float) $value);
    }

    protected function validateFloatData(float $value): ?TypedDataValidationPayload
    {
        return null;
    }

    protected function getNonMatchingTypeValueFeedbackItemResolution(
        mixed $value,
        string|int $id,
        FieldInterface $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            FeedbackItemProvider::E5,
            [
                $this->getDirectiveName(),
                $field->getOutputKey(),
                $id,
            ]
        );
    }
}
