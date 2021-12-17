<?php

declare(strict_types=1);

namespace PoP\Hooks\Services;

use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithHooksAPITrait
{
    protected HooksAPIInterface $translationAPI;

    #[Required]
    final public function setHooksAPI(HooksAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
    final protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->translationAPI;
    }
}
