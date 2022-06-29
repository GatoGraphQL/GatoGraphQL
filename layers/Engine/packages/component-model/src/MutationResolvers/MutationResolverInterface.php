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
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed;
    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array;
    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(WithArgumentsInterface $withArgumentsAST): array;
    public function getErrorType(): int;
}
