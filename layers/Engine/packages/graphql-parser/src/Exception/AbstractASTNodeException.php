<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractASTNodeException extends AbstractLocationableException
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        private readonly AstInterface $astNode,
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $astNode->getLocation(),
        );
    }

    public function getAstNode(): AstInterface
    {
        return $this->astNode;
    }
}
