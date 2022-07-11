<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

interface SchemaInputValidationFeedbackInterface extends QueryFeedbackInterface
{
    public function getDirective(): Directive;
}
