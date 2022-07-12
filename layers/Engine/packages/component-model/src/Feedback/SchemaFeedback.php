<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

class SchemaFeedback extends AbstractQueryFeedback implements SchemaFeedbackInterface
{
    /**
     * @param FieldInterface[] $fields
     * @param array<string, mixed> $extensions
     */
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected array $fields,
        array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $astNode,
            $extensions,
        );
    }

    public static function fromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
    ): self {
        return new self(
            $objectTypeFieldResolutionFeedback->getFeedbackItemResolution(),
            $objectTypeFieldResolutionFeedback->getAstNode(),
            $relationalTypeResolver,
            $objectTypeFieldResolutionFeedback->getExtensions(),
        );
    }

    public function getRelationalTypeResolver(): RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }
}
