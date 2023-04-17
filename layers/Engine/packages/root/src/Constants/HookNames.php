<?php

declare(strict_types=1);

namespace PoP\Root\Constants;

class HookNames
{
    public final const AFTER_BOOT_APPLICATION = __CLASS__ . ':after-boot-application';
    public final const APPLICATION_READY = __CLASS__ . ':application-ready';
}
