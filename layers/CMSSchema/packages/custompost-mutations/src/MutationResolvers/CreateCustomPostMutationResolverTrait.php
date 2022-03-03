<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait CreateCustomPostMutationResolverTrait
{
    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    public function executeMutation(array $form_data): mixed
    {
        return $this->create($form_data);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    abstract protected function create(array $form_data): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(array $form_data): array
    {
        return $this->validateCreateErrors($form_data);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateCreateErrors(array $form_data): array;
}
