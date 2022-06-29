<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    use BasicServiceTrait;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        return [];
    }

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(WithArgumentsInterface $withArgumentsAST): array
    {
        return [];
    }

    public function getErrorType(): int
    {
        return ErrorTypes::DESCRIPTIONS;
    }
}
