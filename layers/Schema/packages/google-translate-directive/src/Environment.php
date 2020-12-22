<?php

declare(strict_types=1);

namespace PoPSchema\GoogleTranslateDirective;

class Environment
{
    public static function getGoogleTranslateAPIKey(): ?string
    {
        return getenv('GOOGLE_TRANSLATE_API_KEY') !== false ? getenv('GOOGLE_TRANSLATE_API_KEY') : null;
    }

    public static function enableGlobalGoogleTranslateDirective(): bool
    {
        return getenv('ENABLE_GOOGLE_TRANSLATE_DIRECTIVE') !== false ? strtolower(getenv('ENABLE_GOOGLE_TRANSLATE_DIRECTIVE')) == "true" : true;
    }
}
