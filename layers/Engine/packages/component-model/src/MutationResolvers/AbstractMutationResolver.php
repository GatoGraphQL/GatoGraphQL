<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractMutationResolver implements MutationResolverInterface
{
    use BasicServiceTrait;
    
    protected ?HooksAPIInterface $hooksAPI = null;

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
    }

    //#[Required]
    final public function autowireAbstractMutationResolver(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }

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
