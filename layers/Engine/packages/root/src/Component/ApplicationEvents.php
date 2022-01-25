<?php

declare(strict_types=1);

namespace PoP\Root\Component;

class ApplicationEvents
{
    public const COMPONENT_LOADED = 'componentLoaded';
    public const BOOT = 'boot';
    public const AFTER_BOOT = 'afterBoot';
}
