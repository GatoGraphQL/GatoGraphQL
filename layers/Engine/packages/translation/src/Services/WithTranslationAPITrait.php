<?php

declare(strict_types=1);

namespace PoP\Translation\Services;

use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithTranslationAPITrait
{
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    final public function setTranslationAPI(TranslationAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
    final protected function getTranslationAPI(): TranslationAPIInterface
    {
        return $this->translationAPI;
    }
}
