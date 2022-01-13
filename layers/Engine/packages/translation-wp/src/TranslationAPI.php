<?php

declare(strict_types=1);

namespace PoP\TranslationWP;

use PoP\Root\Translation\TranslationAPIInterface;

use function __;

class TranslationAPI implements TranslationAPIInterface
{
    public function __(string $text, string $domain = 'default'): string
    {
        return __($text, $domain);
    }
}
