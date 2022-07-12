<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;

final class InvalidDynamicContextException extends AbstractParserException
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        Location $location,
        private readonly DynamicVariableReference $dynamicVariableReference,
    ) {
        parent::__construct($feedbackItemResolution, $location);
    }

    public function getDynamicVariableReference(): DynamicVariableReference
    {
        return $this->dynamicVariableReference;
    }
}
