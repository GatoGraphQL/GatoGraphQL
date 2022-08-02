<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\Root\Feedback\FeedbackItemResolution;

final class InvalidDynamicContextException extends InvalidRequestException
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        private readonly DynamicVariableReference $dynamicVariableReference,
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $dynamicVariableReference,
        );
    }

    public function getDynamicVariableReference(): DynamicVariableReference
    {
        return $this->dynamicVariableReference;
    }
}
