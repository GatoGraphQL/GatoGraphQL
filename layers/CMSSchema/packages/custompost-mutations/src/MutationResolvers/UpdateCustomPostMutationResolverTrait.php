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
    public function executeMutation(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): mixed
    {
        return $this->update($mutationDataProvider);
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exists)
     */
    abstract protected function update(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): array
    {
        return $this->validateUpdateErrors($mutationDataProvider);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateUpdateErrors(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): array;
}
