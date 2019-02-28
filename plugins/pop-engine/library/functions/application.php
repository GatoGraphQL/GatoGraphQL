<?php

function popVersion()
{
    return apply_filters('PoP:version', POP_ENGINE_VERSION);
}

function popLoaded()
{
    return defined('POP_STARTUP_INITIALIZED') && POP_STARTUP_INITIALIZED;
}
