<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Services;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\WithInstanceManagerServiceTrait;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;

    private HooksAPIInterface $hooksAPI;
    private TranslationAPIInterface $translationAPI;

    #[Required]
    final public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    final protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI;
    }
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
