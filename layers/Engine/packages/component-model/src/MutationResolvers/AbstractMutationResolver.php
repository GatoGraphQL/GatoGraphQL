<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI
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
