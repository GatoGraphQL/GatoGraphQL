<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
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
    public function executeMutation(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): mixed;
    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): array;
    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): array;
    public function getErrorType(): int;
}
