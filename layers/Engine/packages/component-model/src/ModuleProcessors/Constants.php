<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

class Constants
{
    public final const HOOK_QUERYDATA_WHITELISTEDPARAMS = __CLASS__ . ':request:whitelistedParams';
    public final const HOOK_DATALOAD_INIT_MODEL_PROPS = __CLASS__ . ':dataload:initModelProps';
}
