<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Services\WithInstanceManagerServiceTrait;
use PoP\Root\Translation\TranslationAPIInterface;

trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;

    private ?TranslationAPIInterface $translationAPI = null;

    final public function setTranslationAPI(TranslationAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
    final protected function getTranslationAPI(): TranslationAPIInterface
    {
        return $this->translationAPI ??= $this->instanceManager->getInstance(TranslationAPIInterface::class);
    }

    /**
     * Shortcut function
     */
    final protected function __(string $text, string $domain = 'default'): string
    {
        return $this->getTranslationAPI()->__($text, $domain);
    }
}
