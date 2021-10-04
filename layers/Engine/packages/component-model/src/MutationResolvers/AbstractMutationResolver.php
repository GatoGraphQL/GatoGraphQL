<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;

    #[Required]
    final public function autowireAbstractMutationResolver(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI): void
    {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
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
