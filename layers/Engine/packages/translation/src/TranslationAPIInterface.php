<?php

declare(strict_types=1);

namespace PoP\Translation;

interface TranslationAPIInterface
{
    public function __(string $text, string $domain = 'default'): string;
}
