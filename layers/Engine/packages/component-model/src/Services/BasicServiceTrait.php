<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Services;

use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Root\Services\WithInstanceManagerServiceTrait;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;
    use WithHooksAPIServiceTrait;

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
