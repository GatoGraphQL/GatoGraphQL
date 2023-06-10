<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPSchema\DirectiveCommons\ObjectModels\TypedDataValidationPayload;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

abstract class AbstractTransformStringFieldValueFieldDirectiveResolver extends AbstractTransformTypedFieldValueFieldDirectiveResolver
{
    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        return [
            StringScalarTypeResolver::class,
            IDScalarTypeResolver::class,
            AnyBuiltInScalarScalarTypeResolver::class,
        ];
    }

    protected function isMatchingType(mixed $value): bool
    {
        return is_string($value);
    }

    /**
     * @param string $value
     */
    final protected function transformTypeValue(mixed $value): mixed
    {
        return $this->transformStringValue($value);
    }

    abstract protected function transformStringValue(string $value): string;

    /**
     * Validate the value against the directive args
     *
     * @param string $value
     */
    final protected function validateTypeData(mixed $value): ?TypedDataValidationPayload
    {
        return $this->validateStringData($value);
    }

    protected function validateStringData(string $value): ?TypedDataValidationPayload
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
            FeedbackItemProvider::E2,
            [
                $this->getDirectiveName(),
                $field->getOutputKey(),
                $id,
            ]
        );
    }
}
