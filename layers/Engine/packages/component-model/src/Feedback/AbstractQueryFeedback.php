<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

/**
 * Error that concern the GraphQL query. the `$astNode` is the
 * node in the AST where the error happens.
 */
abstract class AbstractQueryFeedback extends AbstractFeedback implements QueryFeedbackInterface
{
    /**
     * @param AstInterface $astNode AST node where the error happens (eg: a Field, a Directive, an Argument, etc)
     * @param array<string, mixed> $extensions
     */
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        protected AstInterface $astNode,
        /** @var array<string, mixed> */
        protected array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
        );
    }

    public function getAstNode(): AstInterface
    {
        return $this->astNode;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
