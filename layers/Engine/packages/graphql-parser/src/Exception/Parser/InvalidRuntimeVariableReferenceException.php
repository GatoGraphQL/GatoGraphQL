<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\RuntimeVariableReferenceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

final class InvalidRuntimeVariableReferenceException extends AbstractLocationableException implements QueryExceptionInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        private readonly RuntimeVariableReferenceInterface $runtimeVariableReference,
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $runtimeVariableReference->getLocation(),
        );
    }

    public function getAstNode(): AstInterface
    {
        return $this->runtimeVariableReference;
    }

    public function getRuntimeVariableReference(): RuntimeVariableReferenceInterface
    {
        return $this->runtimeVariableReference;
    }
}
