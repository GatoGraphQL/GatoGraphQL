<?php

declare(strict_types=1);

namespace PoP\Root\Component;

class ApplicationEvents
{
    public const BEFORE_BOOT = 'beforeBoot';
    public const BOOT = 'boot';
    public const AFTER_BOOT = 'afterBoot';
}
