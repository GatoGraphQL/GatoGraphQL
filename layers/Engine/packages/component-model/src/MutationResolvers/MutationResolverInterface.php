<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

interface MutationResolverInterface
{
    /**
     * Please notice: the return type `mixed` includes `Error`
     */
    public function executeMutation(array $form_data): mixed;
    public function validateErrors(array $form_data): ?array;
    public function validateWarnings(array $form_data): ?array;
    public function getErrorType(): int;
}
