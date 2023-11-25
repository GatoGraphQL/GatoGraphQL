<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

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
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsString($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }
        /** @var string $inputValue */

        $this->validateIsXML($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        return $inputValue;
    }

    final protected function validateIsXML(
        string $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        \libxml_use_internal_errors(true);
        $parsed = \simplexml_load_string($inputValue);
        if ($parsed !== false) {
            return;
        }

        $errorMessages = [];
        foreach (libxml_get_errors() as $error) {
            $errorMessages[] = $error->message;
        }
        $this->addDefaultError(
            $inputValue,
            $astNode,
            $objectTypeFieldResolutionFeedbackStore,
            ['problems' => $errorMessages]
        );
        \libxml_clear_errors();
    }
}
