<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPSchema\DirectiveCommons\ObjectModels\TypedDataValidationPayload;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\CodeNameJSONObjectScalarTypeResolver;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\IDValueJSONObjectScalarTypeResolver;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\IntValueJSONObjectScalarTypeResolver;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\ListValueJSONObjectScalarTypeResolver;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\NullableListValueJSONObjectScalarTypeResolver;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\StringListValueJSONObjectScalarTypeResolver;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\StringValueJSONObjectScalarTypeResolver;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

abstract class AbstractTransformJSONObjectFieldValueFieldDirectiveResolver extends AbstractTransformTypedFieldValueFieldDirectiveResolver
{
    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        return [
            JSONObjectScalarTypeResolver::class,
            StringValueJSONObjectScalarTypeResolver::class,
            StringListValueJSONObjectScalarTypeResolver::class,
            NullableListValueJSONObjectScalarTypeResolver::class,
            ListValueJSONObjectScalarTypeResolver::class,
            IntValueJSONObjectScalarTypeResolver::class,
            IDValueJSONObjectScalarTypeResolver::class,
            CodeNameJSONObjectScalarTypeResolver::class,
        ];
    }

    protected function isMatchingType(mixed $value): bool
    {
        return $value instanceof stdClass;
    }

    /**
     * @param stdClass $value
     * @return mixed TypedDataValidationPayload if error, or the value otherwise
     */
    final protected function transformTypeValue(mixed $value): mixed
    {
        return $this->transformStdClassValue($value);
    }

    abstract protected function transformStdClassValue(stdClass $value): stdClass|TypedDataValidationPayload;

    /**
     * Validate the value against the directive args
     *
     * @param stdClass $value
     */
    final protected function validateTypeData(mixed $value): ?TypedDataValidationPayload
    {
        return $this->validateStdClassData($value);
    }

    protected function validateStdClassData(stdClass $value): ?TypedDataValidationPayload
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
            FeedbackItemProvider::E6,
            [
                $this->getDirectiveName(),
                $field->getOutputKey(),
                $id,
            ]
        );
    }
}
