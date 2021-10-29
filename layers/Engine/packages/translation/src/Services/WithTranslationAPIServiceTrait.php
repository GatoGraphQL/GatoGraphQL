<?php

declare(strict_types=1);

namespace PoP\Translation\Services;

use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithTranslationAPIServiceTrait
{
    // use WithInstanceManagerServiceTrait;

    private ?TranslationAPIInterface $translationAPI = null;

    // #[Required]
    public function setTranslationAPI(TranslationAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
    protected function getTranslationAPI(): TranslationAPIInterface
    {
        return $this->translationAPI ??= $this->instanceManager->getInstance(TranslationAPIInterface::class);
    }
}
