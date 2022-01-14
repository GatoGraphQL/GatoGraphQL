<?php

declare(strict_types=1);

namespace PoP\Root\Hooks;

use PoP\Root\App;
use PoP\Root\Constants\HookNames;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractAfterAppBootHookSet extends AbstractHookSet
{
    /**
     * Initialize the hooks when the CMS initializes
     */
    protected function init(): void
    {
        App::addAction(
            HookNames::AFTER_BOOT_APPLICATION,
            [$this, 'cmsBoot'],
            $this->getPriority()
        );
    }

    protected function getPriority(): int
    {
        return 10;
    }

    abstract public function cmsBoot(): void;
}
