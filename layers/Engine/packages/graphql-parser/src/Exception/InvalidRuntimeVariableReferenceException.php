<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\RuntimeVariableReferenceInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

final class InvalidRuntimeVariableReferenceException extends AbstractDeferredValuePromiseException
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        private readonly RuntimeVariableReferenceInterface $runtimeVariableReference,
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $runtimeVariableReference,
        );
    }

    public function getRuntimeVariableReference(): RuntimeVariableReferenceInterface
    {
        return $this->runtimeVariableReference;
    }
}
