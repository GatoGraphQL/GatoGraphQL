<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Services\WithInstanceManagerServiceTrait;
use PoP\Root\Translation\TranslationAPIInterface;

trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;

    private ?TranslationAPIInterface $translationAPI = null;

    final protected function getTranslationAPI(): TranslationAPIInterface
    {
        if ($this->translationAPI === null) {
            /** @var TranslationAPIInterface */
            $translationAPI = $this->instanceManager->getInstance(TranslationAPIInterface::class);
            $this->translationAPI = $translationAPI;
        }
        return $this->translationAPI;
    }

    /**
     * Shortcut function
     */
    final protected function __(string $text, string $domain = 'default'): string
    {
        return $this->getTranslationAPI()->__($text, $domain);
    }
}
