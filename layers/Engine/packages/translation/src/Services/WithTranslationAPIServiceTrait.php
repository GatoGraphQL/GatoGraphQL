<?php

declare(strict_types=1);

namespace PoP\Translation\Services;

use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithTranslationAPIServiceTrait
{
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    public function setTranslationAPI(TranslationAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
}
