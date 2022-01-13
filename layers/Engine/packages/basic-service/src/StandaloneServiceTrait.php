<?php

declare(strict_types=1);

namespace PoP\BasicService;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoP\Root\Translation\TranslationAPIInterface;

trait StandaloneServiceTrait
{
    protected function getHooksAPI(): HooksAPIInterface
    {
        return HooksAPIFacade::getInstance();
    }
    protected function getTranslationAPI(): TranslationAPIInterface
    {
        return TranslationAPIFacade::getInstance();
    }

    /**
     * Shortcut function
     */
    protected function __(string $text, string $domain = 'default'): string
    {
        return $this->getTranslationAPI()->__($text, $domain);
    }
}
