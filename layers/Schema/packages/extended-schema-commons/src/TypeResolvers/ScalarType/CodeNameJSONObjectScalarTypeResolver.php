<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

class CodeNameJSONObjectScalarTypeResolver extends JSONObjectScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'CodeNameJSONObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Custom scalar representing a JSON Object with exactly 2 properties: code (of type Int or String) and name (of type String)', 'extended-schema-commons');
    }

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

        // Validate that the object has exactly 2 properties
        if (count($inputValueArray) !== 2) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }

        // Validate that 'code' property exists and is a valid ID
        if (!isset($inputValueArray['code'])) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }

        $codeValue = $inputValueArray['code'];
        if ($codeValue === null || is_array($codeValue) || is_object($codeValue)) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }

        // Ensure code is string or int (ID type)
        if (!is_string($codeValue) && !is_int($codeValue)) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }

        // Validate that 'name' property exists and is a string
        if (!isset($inputValueArray['name'])) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }

        $nameValue = $inputValueArray['name'];
        if ($nameValue === null || is_array($nameValue) || is_object($nameValue)) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }

        // Ensure name is a string
        if (!is_string($nameValue)) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }

        return $inputValue;
    }
}
