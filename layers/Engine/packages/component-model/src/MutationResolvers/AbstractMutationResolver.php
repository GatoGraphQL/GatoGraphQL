<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    use BasicServiceTrait;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(FieldDataAccessorInterface $fieldDataProvider): array
    {
        return [];
    }

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(FieldDataAccessorInterface $fieldDataProvider): array
    {
        return [];
    }

    public function getErrorType(): int
    {
        return ErrorTypes::DESCRIPTIONS;
    }
}
