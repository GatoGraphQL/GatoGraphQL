<?php

declare(strict_types=1);

namespace PoP\Root\Module;

class ApplicationEvents
{
    public final const MODULE_LOADED = 'moduleLoaded';
    public final const BOOT = 'boot';
    public final const AFTER_BOOT = 'afterBoot';
}
