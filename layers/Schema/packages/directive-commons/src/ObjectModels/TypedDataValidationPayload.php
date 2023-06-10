<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\ObjectModels;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

class TypedDataValidationPayload
{
    public function __construct(
        public readonly FeedbackItemResolution $feedbackItemResolution,
        public readonly ?AstInterface $astNode = null,
    ) {
    }
}
