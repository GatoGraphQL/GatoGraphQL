<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Feedback\FeedbackItemResolution;

/**
 * The ObjectResolutionFeedback is used to store errors that happen during
 * a directive pipeline stage. The `$astNode` is where the error
 * happens, and the `$directive` is the Directive executing that
 * stage on the pipeline.
 */
class ObjectResolutionFeedback extends AbstractQueryFeedback implements ObjectResolutionFeedbackInterface
{
    /**
     * @param Directive $directive At what stage from the Directive pipeline does the error happen
     * @param @var array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param @var array<string, mixed> $extensions
     */
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected Directive $directive,
        protected array $idFieldSet,
        array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $astNode,
            $extensions,
        );
    }

    /**
     * @var array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    public static function fromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
        array $idFieldSet,
    ): self {
        return new self(
            $objectTypeFieldResolutionFeedback->getFeedbackItemResolution(),
            $objectTypeFieldResolutionFeedback->getAstNode(),
            $relationalTypeResolver,
            $directive,
            $idFieldSet,
            $objectTypeFieldResolutionFeedback->getExtensions(),
        );
    }

    public function getDirective(): Directive
    {
        return $this->directive;
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
