<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    use BasicServiceTrait;

    public function validateErrors(array $form_data): array
    {
        return [];
    }

    public function validateWarnings(array $form_data): array
    {
        return [];
    }

    public function getErrorType(): int
    {
        return ErrorTypes::DESCRIPTIONS;
    }
}
