<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirective\Translation;

class TranslationService implements TranslationServiceInterface
{
    protected ?string $defaultProvider = null;

    public function getDefaultProvider(): ?string
    {
        return $this->defaultProvider;
    }
    public function setDefaultProvider(string $provider): void
    {
        $this->defaultProvider = $provider;
    }
}
