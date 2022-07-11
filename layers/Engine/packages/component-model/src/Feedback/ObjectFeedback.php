<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Feedback\FeedbackItemResolution;

class ObjectFeedback extends AbstractQueryFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        Directive $directive,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var array<string|int,EngineIterationFieldSet> $idFieldSet */
        protected array $idFieldSet,
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
        array $idFieldSet,
    ): self {
        /** @var Directive */
        $directive = $objectTypeFieldResolutionFeedback->getAstNode();
        return new self(
            $objectTypeFieldResolutionFeedback->getFeedbackItemResolution(),
            $directive,
            $relationalTypeResolver,
            $idFieldSet,
            $objectTypeFieldResolutionFeedback->getExtensions(),
        );
    }

    public function getRelationalTypeResolver(): RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    /**
     * @return array<string|int,EngineIterationFieldSet>
     */
    public function getIDFieldSet(): array
    {
        return $this->idFieldSet;
    }
}
