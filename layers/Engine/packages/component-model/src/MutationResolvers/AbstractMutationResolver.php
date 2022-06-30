<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    use BasicServiceTrait;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        return [];
    }

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(MutationDataProviderInterface $mutationDataProvider): array
    {
        return [];
    }

    public function getErrorType(): int
    {
        return ErrorTypes::DESCRIPTIONS;
    }
}
