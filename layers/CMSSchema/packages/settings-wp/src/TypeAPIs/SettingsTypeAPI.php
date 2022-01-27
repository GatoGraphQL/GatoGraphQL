<?php

declare(strict_types=1);

namespace PoPCMSSchema\SettingsWP\TypeAPIs;

use PoPCMSSchema\Settings\TypeAPIs\AbstractSettingsTypeAPI;

class SettingsTypeAPI extends AbstractSettingsTypeAPI
{
    protected function doGetOption(string $name): mixed
    {
        return \get_option($name);
    }
}
