<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

class ObjectTypeFieldResolutionFeedback extends AbstractQueryFeedback implements ObjectTypeFieldResolutionFeedbackInterface
{
    public static function fromSchemaInputValidationFeedback(
        SchemaInputValidationFeedbackInterface $schemaInputValidationFeedback,
    ): self {
        return new self(
            $schemaInputValidationFeedback->getFeedbackItemResolution(),
            $schemaInputValidationFeedback->getAstNode(),
            $schemaInputValidationFeedback->getExtensions(),
        );
    }
}
