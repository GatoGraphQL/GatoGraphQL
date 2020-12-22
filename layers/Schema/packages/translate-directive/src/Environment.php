<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirective;

class Environment
{
    public static function getDefaultTranslationProvider(): ?string
    {
        return getenv('DEFAULT_TRANSLATION_PROVIDER') !== false ? getenv('DEFAULT_TRANSLATION_PROVIDER') : null;
    }

    public static function useAsyncForMultiLanguageTranslation(): bool
    {
        return getenv('USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION') !== false ? strtolower(getenv('USE_ASYNC_FOR_MULTILANGUAGE_TRANSLATION')) == "true" : true;
    }
}
