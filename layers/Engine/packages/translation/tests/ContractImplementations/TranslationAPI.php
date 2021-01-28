<?php

declare(strict_types=1);

namespace PoP\Translation\ContractImplementations;

use PoP\Translation\TranslationAPIInterface;
class TranslationAPI implements TranslationAPIInterface
{
    public function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }
}
