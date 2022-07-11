<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

class ObjectTypeFieldResolutionFeedback extends AbstractQueryFeedback implements ObjectTypeFieldResolutionFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var array<string, mixed> */
        array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $astNode,
            $extensions,
        );
    }

    public static function fromSchemaInputValidationFeedback(
        SchemaInputValidationFeedbackInterface $schemaInputValidationFeedback,
        RelationalTypeResolverInterface $relationalTypeResolver,
    ): self {
        return new self(
            $schemaInputValidationFeedback->getFeedbackItemResolution(),
            $schemaInputValidationFeedback->getAstNode(),
            $relationalTypeResolver,
            $schemaInputValidationFeedback->getExtensions(),
        );
    }
}
