<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class XMLScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'XML';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Custom scalar representing an XML string', 'engine');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://www.w3.org/TR/REC-xml/';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $parsed = \simplexml_load_string($inputValue);
        if ($parsed === false) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return $inputValue;
    }
}
