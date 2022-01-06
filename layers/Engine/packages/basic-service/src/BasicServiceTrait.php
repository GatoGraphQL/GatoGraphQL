<?php

declare(strict_types=1);

namespace PoP\BasicService;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\WithInstanceManagerServiceTrait;
use PoP\Translation\TranslationAPIInterface;

trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;

    private ?HooksAPIInterface $hooksAPI = null;
    private ?TranslationAPIInterface $translationAPI = null;

    final public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    final protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
    }
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
