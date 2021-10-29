<?php

declare(strict_types=1);

namespace PoP\Translation\Services;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithTranslationAPIServiceTrait
{
    private ?TranslationAPIInterface $translationAPI = null;

    // #[Required]
    public function setTranslationAPI(TranslationAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
    protected function getTranslationAPI(): TranslationAPIInterface
    {
        return $this->translationAPI ??= TranslationAPIFacade::getInstance();
    }
}
