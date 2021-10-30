<?php

declare(strict_types=1);

namespace PoP\Hooks\Services;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithHooksAPIServiceTrait
{
    private ?HooksAPIInterface $hooksAPI = null;

    // #[Required]
    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= HooksAPIFacade::getInstance();
    }
}
