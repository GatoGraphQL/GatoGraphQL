<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

interface MutationResolverInterface
{
    /**
     * @return mixed
     */
    public function execute(array $form_data);
    public function validateErrors(array $form_data): ?array;
    public function validateWarnings(array $form_data): ?array;
    public function getErrorType(): int;
}
