<?php

declare(strict_types=1);

namespace PoP\Root\Component;

class ApplicationEvents
{
    public final const COMPONENT_LOADED = 'componentLoaded';
    public final const BOOT = 'boot';
    public final const AFTER_BOOT = 'afterBoot';
}
