<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractQueryFeedback extends AbstractFeedback implements QueryFeedbackInterface
{
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
