<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirective\Translation;

interface TranslationServiceInterface
{
    public function getDefaultProvider(): ?string;
    public function setDefaultProvider(string $provider): void;
}
