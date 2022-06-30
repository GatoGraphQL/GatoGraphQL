<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait CreateCustomPostMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        return $this->create($mutationDataProvider);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    abstract protected function create(MutationDataProviderInterface $mutationDataProvider): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        return $this->validateCreateErrors($mutationDataProvider);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateCreateErrors(MutationDataProviderInterface $mutationDataProvider): array;
}
