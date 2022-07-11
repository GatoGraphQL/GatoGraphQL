<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

class ObjectFeedback extends AbstractQueryFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        Directive $directive,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected string|int $objectID,
        /** @var array<string, mixed> */
        array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $directive, // The Directive is the AST node
            $extensions,
        );
    }

    public static function fromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        string|int $objectID,
        ?Directive $directive
    ): self {
        return new self(
            $objectTypeFieldResolutionFeedback->getFeedbackItemResolution(),
            $objectTypeFieldResolutionFeedback->getAstNode(),
            $relationalTypeResolver,
            $objectID,
            $objectTypeFieldResolutionFeedback->getExtensions(),
        );
    }

    public function getRelationalTypeResolver(): RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    public function getObjectID(): string|int
    {
        return $this->objectID;
    }
}
