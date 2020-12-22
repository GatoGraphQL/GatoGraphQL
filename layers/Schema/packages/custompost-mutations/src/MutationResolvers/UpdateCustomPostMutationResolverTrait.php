<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\MutationResolvers;

trait UpdateCustomPostMutationResolverTrait
{
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        return $this->update($form_data);
    }

    public function validateErrors(array $form_data): ?array
    {
        return $this->validateUpdateErrors($form_data);
    }

    abstract protected function validateUpdateErrors(array $form_data): ?array;
}
