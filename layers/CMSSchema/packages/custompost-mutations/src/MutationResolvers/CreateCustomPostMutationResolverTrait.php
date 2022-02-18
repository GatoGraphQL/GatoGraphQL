<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDException;

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
     * @throws CustomPostCRUDException If there was an error (eg: some Custom Post creation validation failed)
     */
    abstract protected function create(array $form_data): string | int;

    public function validateErrors(array $form_data): array
    {
        return $this->validateCreateErrors($form_data);
    }

    abstract protected function validateCreateErrors(array $form_data): array;
}
