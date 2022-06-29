<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait UpdateCustomPostMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        return $this->update($withArgumentsAST);
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exists)
     */
    abstract protected function update(WithArgumentsInterface $withArgumentsAST): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        return $this->validateUpdateErrors($withArgumentsAST);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateUpdateErrors(WithArgumentsInterface $withArgumentsAST): array;
}
