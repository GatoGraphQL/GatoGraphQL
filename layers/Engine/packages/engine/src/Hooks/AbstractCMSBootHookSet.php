<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractCMSBootHookSet extends AbstractHookSet
{
    /**
     * Initialize the hooks when the CMS initializes
     */
    protected function init(): void
    {
        App::addAction(
            'popcms:boot',
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
