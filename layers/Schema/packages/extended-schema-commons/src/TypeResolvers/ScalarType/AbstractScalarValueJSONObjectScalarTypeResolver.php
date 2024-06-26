<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * GraphQL Custom Scalar representing a JSON Object on the client-side,
 * handled via an stdClass object on the server-side
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
abstract class AbstractScalarValueJSONObjectScalarTypeResolver extends JSONObjectScalarTypeResolver
{
    public function getSpecifiedByURL(): ?string
    {
        return null;
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $inputValue = parent::coerceValue(
            $inputValue,
            $astNode,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($inputValue === null) {
            return $inputValue;
        }
        /** @var stdClass $inputValue */

        $inputValueArray = (array) $inputValue;
        foreach ($inputValueArray as $key => $value) {
            /**
             * If the value is of any of the following types, it can't be
             * coerced to string:
             *
             * - null
             * - array
             * - object
             */
            if ($value === null || is_array($value) || is_object($value)) {
                $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
                return null;
            }
            /**
             * Coerce to the specific scalar type, if possible
             */
            if (!$this->canCastJSONObjectPropertyValue($value)) {
                $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
                return null;
            }
            $inputValue->$key = $this->castJSONObjectPropertyValue($value);
        }
        return $inputValue;
    }

    abstract protected function canCastJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): bool;

    abstract protected function castJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): string|int|float|bool;
}
