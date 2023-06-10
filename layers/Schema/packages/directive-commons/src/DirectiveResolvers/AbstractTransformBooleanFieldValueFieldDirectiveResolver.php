<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPSchema\DirectiveCommons\ObjectModels\TypedDataValidationPayload;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

abstract class AbstractTransformBooleanFieldValueFieldDirectiveResolver extends AbstractTransformTypedFieldValueFieldDirectiveResolver
{
    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        return [
            BooleanScalarTypeResolver::class,
            AnyBuiltInScalarScalarTypeResolver::class,
        ];
    }

    protected function isMatchingType(mixed $value): bool
    {
        return is_bool($value);
    }

    /**
     * @param bool $value
     */
    final protected function transformTypeValue(mixed $value): mixed
    {
        return $this->transformBoolValue($value);
    }

    abstract protected function transformBoolValue(bool $value): bool;

    /**
     * Validate the value against the directive args
     *
     * @param bool $value
     */
    final protected function validateTypeData(mixed $value): ?TypedDataValidationPayload
    {
        return $this->validateBoolData($value);
    }

    protected function validateBoolData(bool $value): ?TypedDataValidationPayload
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
            FeedbackItemProvider::E3,
            [
                $this->getDirectiveName(),
                $field->getOutputKey(),
                $id,
            ]
        );
    }
}
