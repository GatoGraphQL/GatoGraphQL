<?php

declare(strict_types=1);

namespace PoP\Root\Component;

trait CanDisableComponentTrait
{
    protected ?bool $enabled = null;

    protected function resolveEnabled(): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        // This is needed for if asking if this component is enabled before it has been initialized
        if ($this->enabled === null) {
            $this->enabled = $this->resolveEnabled();
        }
        return $this->enabled;
    }
}
