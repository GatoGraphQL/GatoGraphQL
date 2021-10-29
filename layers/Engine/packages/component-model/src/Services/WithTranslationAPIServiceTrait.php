<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Services;

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
