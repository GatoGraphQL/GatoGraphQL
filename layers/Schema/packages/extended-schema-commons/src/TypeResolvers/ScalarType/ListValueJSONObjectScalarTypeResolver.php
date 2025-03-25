<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

class ListValueJSONObjectScalarTypeResolver extends JSONObjectScalarTypeResolver
{
    public function getSpecifiedByURL(): ?string
    {
        return null;
    }

    public function getTypeName(): string
    {
        return 'ListValueJSONObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Custom scalar representing a JSON Object where values are lists (of anything)', 'extended-schema-commons');
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
             * Check the value is an array
             */
            if (!is_array($value)) {
                $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
                return null;
            }
            $inputValue->$key = $value;
        }
        return $inputValue;
    }
}
