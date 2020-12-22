<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

class Constants
{
    public const HOOK_QUERYDATA_WHITELISTEDPARAMS = __CLASS__ . ':request:whitelistedParams';
    public const HOOK_DATALOAD_INIT_MODEL_PROPS = __CLASS__ . ':dataload:initModelProps';
}
