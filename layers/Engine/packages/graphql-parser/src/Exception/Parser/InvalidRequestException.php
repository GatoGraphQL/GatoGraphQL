<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use PoP\GraphQLParser\Exception\QueryExceptionInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

final class InvalidRequestException extends AbstractLocationableException implements QueryExceptionInterface
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
