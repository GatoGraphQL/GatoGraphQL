<?php

declare(strict_types=1);

namespace PoP\Root\Constants;

class HookNames
{
    public final const AFTER_BOOT_APPLICATION = __CLASS__ . ':after-boot-application';
    public final const APPLICATION_READY = __CLASS__ . ':application-ready';

    public final const BEFORE_INITIALIZING_APP_STATE = __CLASS__ . ':before-initializing-app-state';
    public final const AFTER_INITIALIZING_APP_STATE = __CLASS__ . ':after-initializing-app-state';

    public final const APP_STATE_BEFORE_INITIALIZED = __CLASS__ . ':app-state-before-initialized';
    public final const APP_STATE_INITIALIZED = __CLASS__ . ':app-state-initialized';
    public final const APP_STATE_CONSOLIDATED = __CLASS__ . ':app-state-consolidated';
    public final const APP_STATE_AUGMENTED = __CLASS__ . ':app-state-augmented';
    public final const APP_STATE_COMPUTED = __CLASS__ . ':app-state-computed';
    public final const APP_STATE_EXECUTED = __CLASS__ . ':app-state-executed';
}
