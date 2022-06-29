<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait CreateCustomPostMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        return $this->create($withArgumentsAST);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    abstract protected function create(WithArgumentsInterface $withArgumentsAST): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        return $this->validateCreateErrors($withArgumentsAST);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateCreateErrors(WithArgumentsInterface $withArgumentsAST): array;
}
