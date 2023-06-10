<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPSchema\DirectiveCommons\ObjectModels\TypedDataValidationPayload;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\NumericScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

abstract class AbstractTransformIntegerFieldValueFieldDirectiveResolver extends AbstractTransformTypedFieldValueFieldDirectiveResolver
{
    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        return [
            IntScalarTypeResolver::class,
            NumericScalarTypeResolver::class,
            AnyBuiltInScalarScalarTypeResolver::class,
        ];
    }

    protected function isMatchingType(mixed $value): bool
    {
        return is_integer($value);
    }

    /**
     * @param int $value
     */
    final protected function transformTypeValue(mixed $value): mixed
    {
        return $this->transformIntValue($value);
    }

    abstract protected function transformIntValue(int $value): int;

    /**
     * Validate the value against the directive args
     *
     * @param int $value
     */
    final protected function validateTypeData(mixed $value): ?TypedDataValidationPayload
    {
        return $this->validateIntData($value);
    }

    protected function validateIntData(int $value): ?TypedDataValidationPayload
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
            FeedbackItemProvider::E4,
            [
                $this->getDirectiveName(),
                $field->getOutputKey(),
                $id,
            ]
        );
    }
}
