<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\Translation\TranslationAPIInterface;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    function __construct(
        protected TranslationAPIInterface $translationAPI
    ) {
    }

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
