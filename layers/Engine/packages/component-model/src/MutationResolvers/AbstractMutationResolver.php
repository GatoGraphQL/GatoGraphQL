<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    use BasicServiceTrait;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(array $form_data): array
    {
        return [];
    }

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(array $form_data): array
    {
        return [];
    }

    public function getErrorType(): int
    {
        return ErrorTypes::DESCRIPTIONS;
    }
}
