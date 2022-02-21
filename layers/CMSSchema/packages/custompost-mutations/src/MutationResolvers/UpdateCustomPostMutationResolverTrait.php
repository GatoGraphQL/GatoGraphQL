<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait UpdateCustomPostMutationResolverTrait
{
    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    public function executeMutation(array $form_data): mixed
    {
        return $this->update($form_data);
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exists)
     */
    abstract protected function update(array $form_data): string | int;

    public function validateErrors(array $form_data): array
    {
        return $this->validateUpdateErrors($form_data);
    }

    abstract protected function validateUpdateErrors(array $form_data): array;
}
