<?php

declare(strict_types=1);

namespace PoP\Engine\Services;

use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithHooksAPIServiceTrait
{
    protected HooksAPIInterface $hooksAPI;

    #[Required]
    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI;
    }
}
