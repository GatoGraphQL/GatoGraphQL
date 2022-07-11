<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Feedback\FeedbackItemResolution;

class SchemaInputValidationFeedback extends AbstractQueryFeedback implements SchemaInputValidationFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        protected Directive $directive,
        protected InputTypeResolverInterface $inputTypeResolver,
        /** @var array<string, mixed> */
        array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $astNode,
            $extensions,
        );
    }

    public function getDirective(): Directive
    {
        return $this->directive;
    }
}
