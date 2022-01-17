<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\MutationResolvers;

trait UpdateCustomPostMutationResolverTrait
{
    public function executeMutation(array $form_data): mixed
    {
        return $this->update($form_data);
    }

    public function validateErrors(array $form_data): array
    {
        return $this->validateUpdateErrors($form_data);
    }

    abstract protected function validateUpdateErrors(array $form_data): array;
}
