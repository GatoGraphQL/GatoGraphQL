<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataProviderInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;

interface MutationResolverInterface
{
    /**
     * Please notice: the return type `mixed` includes `Error`
     */
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataProviderInterface $fieldDataProvider): mixed;
    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(FieldDataProviderInterface $fieldDataProvider): array;
    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(FieldDataProviderInterface $fieldDataProvider): array;
    public function getErrorType(): int;
}
