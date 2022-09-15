<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

final class ObjectFieldValuePromiseException extends AbstractValueResolutionPromiseException
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        private readonly FieldInterface $field,
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $field,
        );
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }
}
