<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\MutationResolvers;

trait CreateCustomPostMutationResolverTrait
{
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        return $this->create($form_data);
    }

    public function validateErrors(array $form_data): ?array
    {
        return $this->validateCreateErrors($form_data);
    }

    abstract protected function validateCreateErrors(array $form_data): ?array;
}
