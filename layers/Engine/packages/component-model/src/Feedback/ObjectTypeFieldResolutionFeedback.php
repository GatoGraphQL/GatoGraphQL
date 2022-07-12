<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Feedback\FeedbackItemResolution;

class ObjectTypeFieldResolutionFeedback extends AbstractQueryFeedback implements ObjectTypeFieldResolutionFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        protected Directive $directive,
        /** @var array<string, mixed> */
        array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $astNode,
            $extensions,
        );
    }

    public function getDirective(): Directive
    {
        return $this->directive;
    }

    public static function fromSchemaInputValidationFeedback(
        SchemaInputValidationFeedbackInterface $schemaInputValidationFeedback,
    ): self {
        return new self(
            $schemaInputValidationFeedback->getFeedbackItemResolution(),
            $schemaInputValidationFeedback->getAstNode(),
            $schemaInputValidationFeedback->getDirective(),
            $schemaInputValidationFeedback->getExtensions(),
        );
    }
}
