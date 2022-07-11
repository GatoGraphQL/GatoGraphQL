<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

class SchemaInputValidationFeedback extends AbstractQueryFeedback implements SchemaInputValidationFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        protected InputTypeResolverInterface $inputTypeResolver,
        /** @var array<string, mixed> */
        array $extensions = [],
        /** @var SchemaInputValidationFeedbackInterface[] */
        protected array $nested = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $astNode,
            $extensions,
        );
    }

    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getNested(): array
    {
        return $this->nested;
    }
}
