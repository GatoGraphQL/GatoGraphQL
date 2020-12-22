<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    public function validateErrors(array $form_data): ?array
    {
        return null;
    }

    public function validateWarnings(array $form_data): ?array
    {
        return null;
    }

    public function getErrorType(): int
    {
        return ErrorTypes::DESCRIPTIONS;
    }
}
